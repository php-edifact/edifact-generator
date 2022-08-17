<?php

namespace EDI\Generator\Traits;

use EDI\Generator\EdifactCurrency;

/**
 * Trait VatAndCurrency
 * @url http://www.unece.org/trade/untdid/d96b/uncl/uncl6343.htm
 * @package EDI\Generator\traits
 */
trait VatAndCurrency
{
    /** @var array */
    protected $vatNumber;

    /** @var array */
    protected $currency;

    /** @var array */
    protected $excludingVatText;

    /**
     * @return array
     */
    public function getVatNumber()
    {
        return $this->vatNumber;
    }

    /**
     * @param string $vatNumber
     *
     * @return $this
     */
    public function setVatNumber($vatNumber)
    {
        $this->vatNumber = self::addRFFSegment('VA', str_replace(' ', '', $vatNumber));

        return $this;
    }

    /**
     * @return array
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param string $currency
     * @param string $qualifier
     * @return $this
     */
    public function setCurrency($currency = 'EUR', $qualifier = EdifactCurrency::CURRENCY_ORDER)
    {
        $this->currency = [
            'CUX',
            [
                '2',
                $currency,
                $qualifier
            ]
        ];

        return $this;
    }

    /**
     * @param string $currency
     * @return $this
     */
    public function setSupplierCurrency($currency = 'EUR')
    {
        $this->setCurrency($currency, EdifactCurrency::CURRENCY_SUPPLIER);

        return $this;
    }

    /**
     * @param string $currency
     * @return $this
     */
    public function setInvoiceCurrency($currency = 'EUR')
    {
        $this->setCurrency($currency, EdifactCurrency::CURRENCY_INVOICING);

        return $this;
    }

    /**
     * @param string $currency
     * @return $this
     */
    public function setQuotationCurrency($currency = 'EUR')
    {
        $this->setCurrency($currency, EdifactCurrency::CURRENCY_QUOTATION);

        return $this;
    }

    /**
     * @param string $currency
     * @return $this
     */
    public function setAccountCurrency($currency = 'EUR')
    {
        $this->setCurrency($currency, EdifactCurrency::CURRENCY_ACCOUNT);

        return $this;
    }

    /**
     * @param string $currency
     * @return $this
     */
    public function setPaymentCurrency($currency = 'EUR')
    {
        $this->setCurrency($currency, EdifactCurrency::CURRENCY_PAYMENT);

        return $this;
    }

    /**
     * @return array
     */
    public function getExcludingVatText()
    {
        return $this->excludingVatText;
    }

    /**
     * @param string $excludingVatText
     *
     * @return $this
     */
    public function setExcludingVatText($excludingVatText)
    {
        $this->excludingVatText = self::addFTXSegment($excludingVatText, 'OSI', 'ROU');

        return $this;
    }
}
