<?php

namespace EDI\Generator\Invoic;

use EDI\Generator\Base;
use EDI\Generator\EdiFactNumber;
use EDI\Generator\Message;
use EDI\Generator\Traits\Item as ItemTrait;

/**
 * Class Item
 * @package EDI\Generator\Invoic
 */
class Item extends Base
{
    use ItemTrait;

    public const DISCOUNT_TYPE_PERCENT = 'percent';
    public const DISCOUNT_TYPE_ABSOLUTE = 'absolute';

    /** @var array */
    protected $invoiceDescription;
    /** @var array */
    protected $grossPrice;
    /** @var array */
    protected $netPrice;
    /** @var int */
    protected $discountIndex = 0;

    /**
     * @var string
     */
    protected $rowTotal;

    /**
     * @var string
     */
    protected $rowTax;

    /**
     * @var string
     */
    protected $rowPO;

    /**
     * @return array
     */
    public function getInvoiceDescription()
    {
        return $this->invoiceDescription;
    }

    /**
     * @param string $invoiceDescription
     * @return Item
     */
    public function setInvoiceDescription($invoiceDescription)
    {
        $this->invoiceDescription = Message::addFTXSegment($invoiceDescription, 'INV');
        $this->addKeyToCompose('invoiceDescription');

        return $this;
    }


    /**
     * @param $qualifier
     * @param $value
     * @param int $priceBase
     * @param string $priceBaseUnit
     * @return array
     */
    public static function addPRISegment($qualifier, $value, $priceBase = 1, $priceBaseUnit = 'PCE')
    {
        return [
            'PRI',
            [
                $qualifier,
                EdiFactNumber::convert($value),
                $priceBase,
                $priceBaseUnit
            ]
        ];
    }

    /**
     * @return array
     */
    public function getGrossPrice()
    {
        return $this->grossPrice;
    }

    /**
     * @param string $grossPrice
     * @return Item
     */
    public function setGrossPrice($grossPrice)
    {
        $this->grossPrice = self::addPRISegment('GRP', $grossPrice);
        $this->addKeyToCompose('grossPrice');
        return $this;
    }

    /**
     * @return array
     */
    public function getNetPrice()
    {
        return $this->netPrice;
    }

    /**
     * @param string $netPrice
     * @return Item
     */
    public function setNetPrice($netPrice)
    {
        $this->netPrice = self::addPRISegment('AAA', $netPrice, 'CT','NTP');
        $this->addKeyToCompose('netPrice');
        return $this;
    }

    /**
     * @param $value
     * @param string $discountType
     * @return Item
     */
    public function addDiscount($value, $discountType = self::DISCOUNT_TYPE_PERCENT)
    {
        if ($discountType == self::DISCOUNT_TYPE_PERCENT) {
            $index = 'discount' . $this->discountIndex++;
            $this->{$index} = [
                'PCD',
                [
                    '',
                    '3',
                    EdiFactNumber::convert(abs($value))
                ]
            ];
            $this->addKeyToCompose($index);
        }

        $index = 'discount' . $this->discountIndex++;
        $this->{$index} = self::addMOASegment('8', abs($value));
        $this->addKeyToCompose($index);

        return $this;
    }


    public function setRowTotal($qualifier, $price) {
        $this->rowTotal = [
            'MOA',
            [
                $qualifier,
                EdiFactNumber::convert($price),
                'EUR',
                '4'
            ]
        ];
        $this->addKeyToCompose('rowTotal');

        return $this;
    }

    public function getRowTotal()
    {
        return $this->rowTotal;
    }

    public function setRowTax($price) {
        $this->rowTax = [
            'TAX',
            7,
            'VAT',
            '',
            '',
            [
                '',
                '',
                '',
                EdiFactNumber::convert($price, 0)
            ],
        ];

        $this->addKeyToCompose('rowTax');

        return $this;
    }

    public function getRowTax()
    {
        return $this->rowTax;
    }

    public function setRowPO($po) {
        $this->rowPO = [
            'RFF',
            [
                'ON',
                self::maxChars($po, 35),
            ],
        ];

        $this->addKeyToCompose('rowPO');

        return $this;
    }

    public function getRowPO()
    {
        return $this->rowPO;
    }
}
