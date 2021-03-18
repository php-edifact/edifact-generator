<?php
/**
 * Created by PhpStorm.
 * User: Sascha
 * Date: 23.01.2018
 * Time: 16:15
 */

namespace EDI\Generator;

use EDI\Generator\Invoic\Item;
use EDI\Generator\Traits\ContactPerson;
use EDI\Generator\Traits\NameAndAddress;

/**
 * Class Invoic
 * @url http://www.unece.org/trade/untdid/d96b/trmd/invoic_s.htm
 * @url http://www.stylusstudio.com/edifact/D96A/INVOIC.htm
 *
 * @package EDI\Generator
 */
class Invoic extends Message
{
  use
    ContactPerson,
    NameAndAddress;

  /**
   *
   */
  const TYPE_INVOICE = '380';
  /**
   *
   */
  const TYPE_CREDIT_NOTE = '381';
  /**
   *
   */
  const TYPE_SERVICE_CREDIT = '31e';
  /**
   *
   */
  const TYPE_SERVICE_INVOICE = '32e';
  /**
   * Bonus
   */
  const TYPE_BONUS = '33i';

  /**
   * Storno
   */
  const TYPE_REVERSAL = 1;
  /**
   * Original
   */
  const TYPE_ORIGINAL = 9;
  /**
   * Kopie
   */
  const TYPE_COPY = 31;

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
  protected $excludingVatText;
  /** @var array */
  protected $invoiceDescription;
  /** @var array */
  protected $vatNumber;
  /** @var array */
  protected $customerVatNumber;
  /** @var array */
  protected $currency;

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

  /** @var integer */
  protected $index = 0;

  /** @var array */
  protected $charges;

  /** @var array */
  protected $composeKeys
    = [
      'invoiceNumber',
      'invoiceDate',
      'deliveryDate',
      'reductionOfFeesText',
      'excludingVatText',
      'invoiceDescription',
      'manufacturerAddress',
      'manufacturerAddressVatId',
      'wholesalerAddress',
      'wholesalerAddressVatId',
      'representativeAddress',
      'representativeAddressVatId',
      'deliveryAddress',
      'deliveryAddressVatId',
      'invoiceAddress',
      'invoiceAddressVatId',
      'contactPerson',
      'mailAddress',
      'phoneNumber',
      'faxNumber',
      'vatNumber',
      'customerVatNumber',
      'currency',
    ];

  protected $composeKeysAfterPositions
    = [
      'positionSeparator',
      'totalPositionsAmount',
      'taxableAmount',
      'basisAmount',
      'payableAmount',
      'tax',
      'taxAmount',
      'charges',
    ];


  /**
   * Invoic constructor.
   *
   * @param null   $messageId
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
    $release = '96B',
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
   * @param null $msgStatus
   *
   * @return $this
   * @throws EdifactException
   */
  public function compose($msgStatus = null)
  {
    $this->composeByKeys();

    foreach ($this->items as $item) {
      $composed = $item->compose();
      foreach ($composed as $entry) {
        $this->messageContent[] = $entry;
      }
    }

    $this->setPositionSeparator();
    $this->composeByKeys($this->composeKeysAfterPositions);

    parent::compose();
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
   *
   * @return Invoic
   * @throws EdifactException
   */
  public function setInvoiceNumber($invoiceNumber, $documentType = self::TYPE_INVOICE, $type = self::TYPE_ORIGINAL)
  {
    $this->isAllowed(
      $documentType, [
        self::TYPE_INVOICE,
        self::TYPE_CREDIT_NOTE,
        self::TYPE_SERVICE_CREDIT,
        self::TYPE_SERVICE_INVOICE,
        self::TYPE_BONUS,
      ]
    );
    $this->invoiceNumber = self::addBGMSegment(
      $invoiceNumber,
      $documentType
    );

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
   *
   * @return Invoic
   * @throws EdifactException
   */
  public function setInvoiceDate($invoiceDate)
  {
    $this->invoiceDate = $this->addDTMSegment($invoiceDate, '3');
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
   * @param string $deliveryDate
   *
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
   *
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
  public function getExcludingVatText()
  {
    return $this->excludingVatText;
  }

  /**
   * @param string $excludingVatText
   *
   * @return Invoic
   */
  public function setExcludingVatText($excludingVatText)
  {
    $this->excludingVatText = self::addFTXSegment($excludingVatText, 'OSI', 'ROU');
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
   *
   * @return Invoic
   */
  public function setInvoiceDescription($invoiceDescription)
  {
    $this->invoiceDescription = self::addFTXSegment($invoiceDescription, 'OSI');
    return $this;
  }

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
   * @return Invoic
   */
  public function setVatNumber($vatNumber)
  {
    $this->vatNumber = self::addRFFSegment('VA', str_replace(' ', '', $vatNumber));
    return $this;
  }

  /**
   * @return array
   */
  public function getCustomerVatNumber()
  {
    return $this->vatNumber;
  }

  /**
   * @param string $vatNumber
   *
   * @return Invoic
   */
  public function setCustomerVatNumber($vatNumber)
  {
    $this->vatNumber = self::addRFFSegment('FC', str_replace(' ', '', $vatNumber));
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
   *
   * @return Invoic
   */
  public function setCurrency($currency = 'EUR')
  {
    $this->currency = [
      'CUX',
      [
        '2',
        $currency,
      ],
    ];
    return $this;
  }


  /**
   * @return Invoic
   */
  public function setPositionSeparator()
  {
    $this->positionSeparator = [
      'UNS',
      'S',
    ];
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
   *
   * @return Invoic
   */
  public function setTotalPositionsAmount($totalPositionsAmount)
  {
    $this->totalPositionsAmount = self::addMOASegment('79', $totalPositionsAmount);
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
   *
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
   *
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
   *
   * @return Invoic
   */
  public function setPayableAmount($payableAmount)
  {
    $this->payableAmount = self::addMOASegment('9', $payableAmount);

    return $this;
  }

  /**
   * @param string|float $value
   * @param string|float $amount
   *
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
        EdiFactNumber::convert($value, 2),
      ],
    ];
    $this->taxAmount = self::addMOASegment('150', $amount);
    return $this;
  }


  /**
   * @param $date
   * @param $value
   *
   * @return Invoic
   * @throws EdifactException
   */
  public function addCashDiscount($date, $value)
  {
    $index = 'cashDiscountPAT' . $this->index;
    $this->{$index} = self::addPATSegment(Base::PAT_SKONTO);
    $this->addKeyToCompose($index);

    $index = 'cashDiscountDTM' . $this->index;
    $this->{$index} = self::addDTMSegment($date, 343);
    $this->addKeyToCompose($index);


    $index = 'cashDiscountPCD' . $this->index;
    $this->{$index} = self::addPCDSegment($value);
    $this->addKeyToCompose($index);

    $this->index++;

    return $this;
  }

  /**
   * Nettosumme
   *
   * @param string $date
   * @param string $value
   *
   * @throws EdifactException
   */
  public function addNetAmount($days, $date)
  {
    $index = 'netAmountPAT' . $this->index;
    $this->{$index} = self::addPATSegment(Base::PAT_NET_PAYMENT_TARGET, Base::PAT_TIME_INVOICE_DATE, $days);
    $this->addKeyToCompose($index);

    $index = 'netAmountDTM' . $this->index;
    $this->{$index} = self::addDTMSegment($date, 13);
    $this->addKeyToCompose($index);

    $this->index++;

    return $this;
  }

  const CHARGES_TYPE_FEES = 'ABW';
  const CHARGES_TYPE_NOTARIZATION = 'AU';
  const CHARGES_TYPE_CARGO = 'DL';
  const CHARGES_TYPE_INSURANCE = 'IN';
  const CHARGES_TYPE_PACKING = 'PC';
  const CHARGES_TYPE_CUSTOM = 'ZZZ';

  /**
   * @param $value
   * @param $type
   *
   * @return $this
   */
  public function addCharges($value, $type = self::CHARGES_TYPE_CARGO)
  {
    if (!is_array($this->charges)) {
      $this->charges = [];
    }
    $this->charges[] = [
      'ALC',
      floatval($value) > 0 ? 'C' : 'A',
      '',
      '',
      '',
      $type,
    ];

    $this->charges[] = self::addMOASegment('8', EdiFactNumber::convert(abs($value)));

    return $this;
  }

}
