<?php
/**
 * Created by PhpStorm.
 * User: Sascha
 * Date: 23.01.2018
 * Time: 15:13
 */

namespace EDI\Generator;

use EDI\Generator\Traits\ContactPerson;
use EDI\Generator\Traits\NameAndAddress;

/**
 * Class Ordrsp
 * @url http://www.unece.org/trade/untdid/d96b/trmd/ordrsp_s.htm
 *
 * @package EDI\Generator
 */
class Ordrsp extends Message
{
  use ContactPerson,
    NameAndAddress;

  /** @var array */
  protected $orderConfirmationNumber;
  /** @var array */
  protected $orderConfirmationDate;
  /** @var array */
  protected $deliveryDate;
  /** @var array */
  protected $orderNumber;
  /** @var array */
  protected $positionSeparator;
  /** @var array */
  protected $orderInstruction;
  /** @var array */
  protected $additionalReferenceNumber;
  /** @var array */
  protected $transportDocumentNumber;
  /** @var array */
  protected $projectNumber;
  /** @var array */
  protected $beneficiaryReference;
  /** @var array */
  protected $beneficiaryReference2;
  /** @var array */
  protected $allowanceOrCharge;
  /** @var array */
  protected $allowanceOrChargeMoa;

  /** @var array */
  protected $items = [];
  /** @var array */
  protected $composeKeys
    = [
      'orderConfirmationNumber',
      'orderConfirmationDate',
      'deliveryDate',
      'orderNumber',
      'orderInstruction',
      'additionalReferenceNumber',
      'transportDocumentNumber',
      'beneficiaryReference',
      'beneficiaryReference2',
      'orderInstruction',
      'manufacturerAddress',
      'manufacturerAddressContactPerson',
      'manufacturerAddressMailAddress',
      'manufacturerAddressPhoneNumber',
      'manufacturerAddressFaxNumber',
      'wholesalerAddress',
      'wholesalerAddressContactPerson',
      'wholesalerAddressMailAddress',
      'wholesalerAddressPhoneNumber',
      'wholesalerAddressFaxNumber',
      'deliveryAddress',
      'deliveryAddressContactPerson',
      'deliveryAddressMailAddress',
      'deliveryAddressPhoneNumber',
      'deliveryAddressFaxNumber',
      'allowanceOrCharge',
      'allowanceOrChargeMoa',
      'positionSeparator',
    ];

  /**
   * Ordrsp constructor.
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
    $identifier = 'ORDRSP',
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

    $this->messageContent[] = [
      'UNS',
      'S',
    ];
    parent::compose();
    return $this;
  }

  /**
   * @return array
   */
  public function getOrderConfirmationNumber()
  {
    return $this->orderConfirmationNumber;
  }


  /**
   * @param string $orderConfirmationNumber
   * @param string $documentType
   *
   * @return Ordrsp
   */
  public function setOrderConfirmationNumber($orderConfirmationNumber, $documentType = '231')
  {
    $this->orderConfirmationNumber = [
      'BGM',
      $documentType,
      $orderConfirmationNumber,
    ];
    return $this;
  }

  /**
   * @return array
   */
  public function getOrderConfirmationDate()
  {
    return $this->orderConfirmationDate;
  }

  /**
   * @param string|\DateTime $orderConfirmationDate
   *
   * @return Ordrsp
   * @throws EdifactException
   */
  public function setOrderConfirmationDate($orderConfirmationDate)
  {
    $this->orderConfirmationDate = $this->addDTMSegment($orderConfirmationDate, '4');
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
   * @param string|\DateTime $deliveryDate
   * @param int              $type
   * @param int              $formatQuantifier
   *
   * @return Ordrsp
   * @throws EdifactException
   */
  public function setDeliveryDate($deliveryDate, $type = EdifactDate::TYPE_DELIVERY_DATE_REQUESTED, $formatQuantifier = EdifactDate::DATE)
  {
    $this->deliveryDate = $this->addDTMSegment($deliveryDate, $type, $formatQuantifier);
    return $this;
  }

  /**
   * @return array
   */
  public function getOrderNumber()
  {
    return $this->orderNumber;
  }

  /**
   * @param string $orderNumber
   *
   * @return Ordrsp
   */
  public function setOrderNumber($orderNumber)
  {
    $this->orderNumber = $this->addRFFSegment('VN', $orderNumber);
    return $this;
  }

  /**
   * @return array
   */
  public function getPositionSeparator()
  {
    return $this->positionSeparator;
  }

  /**
   * @return Ordrsp
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
  public function getOrderInstruction()
  {
    return $this->orderInstruction;
  }

  /**
   * @param string $orderInstruction
   *
   * @return Ordrsp
   */
  public function setOrderInstruction($orderInstruction)
  {
    $this->orderInstruction = self::addFTXSegment($orderInstruction, 'ORI');
    return $this;
  }

  /**
   * @return array
   */
  public function getAdditionalReferenceNumber()
  {
    return $this->additionalReferenceNumber;
  }

  /**
   * @param string $additionalReferenceNumber
   *
   * @return Ordrsp
   */
  public function setAdditionalReferenceNumber($additionalReferenceNumber)
  {
    $this->additionalReferenceNumber = self::addRFFSegment('ACD', $additionalReferenceNumber);
    return $this;
  }

  /**
   * @return array
   */
  public function getTransportDocumentNumber()
  {
    return $this->transportDocumentNumber;
  }

  /**
   * @param string $transportDocumentNumber
   *
   * @return Ordrsp
   */
  public function setTransportDocumentNumber($transportDocumentNumber)
  {
    $this->transportDocumentNumber = self::addRFFSegment('AAS', $transportDocumentNumber);
    return $this;
  }

  /**
   * @return array
   */
  public function getProjectNumber()
  {
    return $this->projectNumber;
  }

  /**
   * @param string $projectNumber
   *
   * @return Ordrsp
   */
  public function setProjectNumber($projectNumber)
  {
    $this->projectNumber = self::addRFFSegment('AEP', $projectNumber);
    return $this;
  }

  /**
   * @return array
   */
  public function getBeneficiaryReference()
  {
    return $this->beneficiaryReference;
  }

  /**
   * @param string $beneficiaryReference
   *
   * @return Ordrsp
   */
  public function setBeneficiaryReference($beneficiaryReference)
  {
    $this->beneficiaryReference = self::addRFFSegment('AFO', $beneficiaryReference);
    return $this;
  }

  /**
   * @return array
   */
  public function getBeneficiaryReference2()
  {
    return $this->beneficiaryReference2;
  }

  /**
   * @param string $beneficiaryReference2
   *
   * @return Ordrsp
   */
  public function setBeneficiaryReference2($beneficiaryReference2)
  {
    $this->beneficiaryReference2 = self::addRFFSegment('AFP', $beneficiaryReference2);
    return $this;
  }

  /**
   * @return array
   */
  public function getAllowanceOrCharge()
  {
    return $this->allowanceOrCharge;
  }

  /**
   * @param float $value
   *
   * @return Ordrsp
   */
  public function setAllowanceOrCharge($value)
  {
    $this->allowanceOrCharge = [
      'ALC',
      floatval($value) > 0 ? 'C' : 'A',
      '',
      '',
      '',
      'DL',
    ];

    $this->allowanceOrChargeMoa = self::addMOASegment('8', abs($value));
    return $this;
  }


}
