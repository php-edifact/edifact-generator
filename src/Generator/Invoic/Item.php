<?php
/**
 * Created by PhpStorm.
 * User: Sascha
 * Date: 08.02.2018
 * Time: 14:11
 */

namespace EDI\Generator\Invoic;


use EDI\Generator\Base;
use EDI\Generator\EdifactDate;
use EDI\Generator\EdifactException;
use EDI\Generator\EdiFactNumber;
use EDI\Generator\Message;

/**
 * Class Item
 *
 * @package EDI\Generator\Invoic
 */
class Item extends Base
{
  const DISCOUNT_TYPE_PERCENT = 'percent';
  const DISCOUNT_TYPE_ABSOLUTE = 'absolute';

  use \EDI\Generator\Traits\Item;

  /** @var array */
  protected $invoiceDescription;
  /** @var array */
  protected $grossPrice;
  /** @var array */
  protected $netPrice;

  /** @var array */
  protected $discount;

  /** @var array */
  protected $discountFactor;

  /** @var array */
  protected $productInformation;

  /** @var array */
  protected $deliveryDate;

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
   * @return Item
   */
  public function setInvoiceDescription($invoiceDescription)
  {
    $this->invoiceDescription = Message::addFTXSegment($invoiceDescription, 'INV');
    return $this;
  }


  /**
   * @param        $qualifier
   * @param        $value
   * @param int    $priceBase
   * @param string $priceBaseUnit
   *
   * @return array
   */
  public static function addPRISegment($qualifier, $value, $priceBase = 1, $priceBaseUnit = 'PCE')
  {
    return [
      'PRI',
      [
        $qualifier,
        EdiFactNumber::convert($value),
        '',
        '',
        $priceBase,
        $priceBaseUnit,
      ],
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
   *
   * @return Item
   */
  public function setGrossPrice($grossPrice)
  {
    $this->grossPrice = self::addPRISegment('GRP', $grossPrice);
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
   *
   * @return Item
   */
  public function setNetPrice($netPrice)
  {
    $this->netPrice = self::addPRISegment('NTP', $netPrice);

    return $this;
  }

  /**
   * @param float  $value Positive for extra charge and negative for discount
   * @param string $discountType
   * @param int    $valueBeforeDiscount
   * @param string $discountText
   *
   * @return Item
   */
  public function addDiscount(
    $value,
    $discountType = self::DISCOUNT_TYPE_PERCENT,
    $valueBeforeDiscount = 0,
    $discountText = ''
  ) {
    if (!is_array($this->discount)) {
      $this->discount = [];
    }


    $discountType == self::DISCOUNT_TYPE_PERCENT ? 'SF' : 'DI';
    if (!empty($discountText)) {
      $discountType = 'ZZZ';
    }

    array_push(
      $this->discount, [
        'ALC',
        floatval($value) > 0 ? 'C' : 'A',
        '',
        '',
        '',
        [
          $discountType,
          '',
          '',
          $discountText,
        ],
      ]
    );

    array_push(
      $this->discount, [
        'PCD',
        [
          '3',
          EdiFactNumber::convert(abs($value)),
        ],
      ]
    );


    array_push(
      $this->discount,
      self::addMOASegment(
        '8',
        $valueBeforeDiscount * (abs($value) / 100)
      )
    );

    return $this;
  }


  /**
   * @param $value
   * @param $valueBeforeDiscount
   *
   * @return $this
   * @throws EdifactException
   */
  public function addDiscountFactor($valueAfterDiscount, $valueBeforeDiscount)
  {
    $this->discountFactor = [];
    if ($valueBeforeDiscount == 0) {
      if ($valueAfterDiscount > 0){
        throw new EdifactException('valueBeforeDiscount cannot be 0, if valueAfterDiscount > 0');
      }

      return $this;
    }
    $factor = $valueAfterDiscount / $valueBeforeDiscount;

    array_push(
      $this->discountFactor, [
        'ALC',
        floatval($factor) > 1 ? 'C' : 'A',
        '',
        '',
        '',
        [
          'SF',
        ],
      ]
    );

    array_push(
      $this->discountFactor, [
        'PCD',
        [
          '1',
          EdiFactNumber::convert($factor, 4),
        ],
      ]
    );

    array_push(
      $this->discountFactor,
      self::addMOASegment(
        '8',
        $valueBeforeDiscount - $valueAfterDiscount
      )
    );

    return $this;
  }


  /**
   * @param     $total
   * @param int $segment
   */
  public function setTotal($total, $segment = 8)
  {
    $index = 'discount' . $this->discountIndex++;
    $this->{$index} = self::addMOASegment($segment, $total);
    $this->addKeyToCompose($index);
  }


  /**
   * EAN Nummer
   *
   * @param $ean
   *
   * @return self
   */
  public function addProductInformation($ean)
  {
    $this->productInformation = self::addPIASegment($ean);
    $this->addKeyToCompose('productInformation');

    return $this;
  }

  /**
   * @param     $deliveryDate
   * @param int $type
   * @param int $formatQuantifier
   *
   * @return $this
   * @throws \EDI\Generator\EdifactException
   */
  public function setDeliveryDate($deliveryDate, $type = EdifactDate::TYPE_DELIVERY_DATE_ACTUAL,
    $formatQuantifier = EdifactDate::DATE
  ) {
    $this->deliveryDate = $this->addDTMSegment($deliveryDate, $type, $formatQuantifier);

    return $this;
  }

}
