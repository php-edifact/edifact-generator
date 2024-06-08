<?php

namespace EDI\Generator;

use EDI\Generator\Invoic\Item;
use EDI\Generator\Traits\ContactPerson;
use EDI\Generator\Traits\NameAndAddress;
use EDI\Generator\Traits\VatAndCurrency;

/**
 * Class Invoic
 * @url http://www.unece.org/trade/untdid/d96b/trmd/invoic_s.htm
 * @package EDI\Generator
 */
class Invoic extends Message
{
    use ContactPerson;
    use NameAndAddress;
    use VatAndCurrency;

    public const TYPE_INVOICE = '380';
    public const TYPE_CREDIT_NOTE = '381';
    public const TYPE_SERVICE_CREDIT = '31e';
    public const TYPE_SERVICE_INVOICE = '32e';
    public const TYPE_BONUS = '33i';

    /** @var array */
    protected $invoiceNumber;
    /** @var array */
    protected $invoiceDate;
    /** @var array */
    protected $deliveryDate;
    /** @var array */
    protected $items;
    /** @var array */
    protected $reductionOfFeesText;
    /** @var array */
    protected $composeKeys = [
        'invoiceNumber',
        'invoiceDate',
        'deliveryDate',
        'reductionOfFeesText',
        'excludingVatText',
        'invoiceDescription',
        'manufacturerAddress',
        'vatNumber',
        'buyerAddress',
        'deliveryPartyAddress',
        'invoiceAddress',
        'vatNumberAmz',
        'wholesalerAddress',
        'deliveryAddress',
        'contactPerson',
        'mailAddress',
        'phoneNumber',
        'faxNumber',
        'currency',
        'paymentTerms'
    ];

    /** @var array */
    protected $invoiceDescription;

    /** @var array */
    protected $positionSeparator;
    /** @var array */
    protected $totalPositionsAmount;
    /** @var array */
    protected $basisAmount;
    /** @var array */
    protected $taxableAmount;
    /** @var array */
    protected $payableAmount;
    /** @var array */
    protected $tax;
    /** @var array */
    protected $taxAmount;

    protected $itemsCount;


    /**
     * Invoic constructor.
     *@param $messageId
     * @param string $identifier
     * @param string $version
     * @param string $release
     * @param string $controllingAgency
     * @param string $association
     */
    public function __construct(
        $messageId = null,
        $identifier = 'INVOIC',
        $version = 'D',
        $release = '96A',
        $controllingAgency = 'UN',
        $association = 'ITEK35'
    ) {
        parent::__construct(
            $identifier,
            $version,
            $release,
            $controllingAgency,
            $messageId,
            $association
        );
        $this->items = [];
    }

    /**
     * @param $item Item
     */
    public function addItem($item)
    {
        $this->items[] = $item;
    }


    /**
     * @return $this
     * @throws EdifactException
     */
    public function compose()
    {
        $this->composeByKeys();

        foreach ($this->items as $item) {
            $composed = $item->compose();
            foreach ($composed as $entry) {
                $this->messageContent[] = $entry;
            }
        }

        $this->setPositionSeparator();
        $this->setCountItems();
        $this->composeByKeys([
            'positionSeparator',
            'itemsCount',
            'payableAmount',
            'totalPositionsAmount',
            'basisAmount',
            'taxableAmount',
            'tax',
            'taxAmount',
        ]);
        parent::compose();
        $this->setPositionSeparator();

        return $this;
    }

    /**
     * @return array
     */
    public function getInvoiceNumber()
    {
        return $this->invoiceNumber;
    }

    /**
     * @param string $invoiceNumber
     * @param string $documentType
     * @return Invoic
     * @throws EdifactException
     */
    public function setInvoiceNumber($invoiceNumber, $documentType = self::TYPE_INVOICE)
    {
        $this->isAllowed($documentType, [
            self::TYPE_INVOICE,
            self::TYPE_CREDIT_NOTE,
            self::TYPE_SERVICE_CREDIT,
            self::TYPE_SERVICE_INVOICE,
            self::TYPE_BONUS
        ]);
        $this->invoiceNumber = self::addBGMSegment($invoiceNumber, $documentType);
        return $this;
    }

    /**
     * @return array
     */
    public function getInvoiceDate()
    {
        return $this->invoiceDate;
    }

    /**
     * @param string $invoiceDate
     * @return Invoic
     * @throws EdifactException
     */
    public function setInvoiceDate($invoiceDate)
    {
        $this->invoiceDate = $this->addDTMSegment($invoiceDate, '137');
        return $this;
    }

    /**
     * @return array
     */
    public function getDeliveryDate()
    {
        return $this->deliveryDate;
    }

    /**
     * @param $deliveryDate
     * @return Invoic
     * @throws EdifactException
     */
    public function setDeliveryDate($deliveryDate)
    {
        $this->deliveryDate = $this->addDTMSegment($deliveryDate, '35');
        return $this;
    }

    /**
     * @return array
     */
    public function getReductionOfFeesText()
    {
        return $this->reductionOfFeesText;
    }

    /**
     * @param string $reductionOfFeesText
     * @return Invoic
     */
    public function setReductionOfFeesText($reductionOfFeesText)
    {
        $this->reductionOfFeesText = self::addFTXSegment($reductionOfFeesText, 'OSI', 'HAE');
        return $this;
    }



    /**
     * @return array
     */
    public function getInvoiceDescription()
    {
        return $this->invoiceDescription;
    }

    /**
     * @param string $invoiceDescription
     * @return Invoic
     */
    public function setInvoiceDescription($invoiceDescription)
    {
        $this->invoiceDescription = self::addFTXSegment($invoiceDescription, 'OSI');
        return $this;
    }

    /**
     * @return Invoic
     */
    public function setPositionSeparator()
    {
        $this->positionSeparator = ['UNS', 'S'];
        return $this;
    }

    /**
     * @return Invoic
     */
    public function setCountItems()
    {
        $this->itemsCount = ['CNT', ['2', (string)count($this->items)]];
        return $this;
    }

    /**
     * @return array
     */
    public function getTotalPositionsAmount()
    {
        return $this->totalPositionsAmount;
    }

    /**
     * @param string|float $totalPositionsAmount
     * @return Invoic
     */
    public function setTotalPositionsAmount($totalPositionsAmount)
    {
        $this->totalPositionsAmount = self::addMOASegment('125', $totalPositionsAmount);
        return $this;
    }

    /**
     * @return array
     */
    public function getBasisAmount()
    {
        return $this->basisAmount;
    }

    /**
     * @param string|float $basisAmount
     * @return Invoic
     */
    public function setBasisAmount($basisAmount)
    {
        $this->basisAmount = self::addMOASegment('56', $basisAmount);
        return $this;
    }

    /**
     * @return array
     */
    public function getTaxableAmount()
    {
        return $this->taxableAmount;
    }

    /**
     * @param string|float $taxableAmount
     * @return Invoic
     */
    public function setTaxableAmount($taxableAmount)
    {
        $this->taxableAmount = self::addMOASegment('125', $taxableAmount);
        return $this;
    }

    /**
     * @return array
     */
    public function getPayableAmount()
    {
        return $this->payableAmount;
    }

    /**
     * @param string|float $payableAmount
     * @return Invoic
     */
    public function setPayableAmount($payableAmount)
    {
        $this->payableAmount = self::addMOASegment('77', $payableAmount);
        return $this;
    }

    /**
     * @param string|float $value
     * @param string|float $amount
     * @return $this
     */
    public function setTax($value, $amount)
    {
        $this->tax = [
            'TAX',
            '7',
            'VAT',
            '',
            '',
            [
                '',
                '',
                '',
                EdiFactNumber::convert($value, 0)
            ],
        ];
        $this->taxAmount = self::addMOASegment('124', $amount);
        return $this;
    }
}
