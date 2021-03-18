<?php

namespace EDI\Generator\Traits;

/**
 * Trait NameAndAddress
 *
 * @package EDI\Generator\Traits
 */
trait NameAndAddress
{
  /** @var array */
  protected $manufacturerAddress;
  /** @var array */
  protected $manufacturerAddressContactPerson;
  /** @var array */
  protected $manufacturerAddressMailAddress;
  /** @var array */
  protected $manufacturerAddressPhoneNumber;
  /** @var array */
  protected $manufacturerAddressFaxNumber;
  /** @var array */
  protected $manufacturerAddressVatId;

  /** @var array */
  protected $wholesalerAddress;
  /** @var array */
  protected $wholesalerAddressContactPerson;
  /** @var array */
  protected $wholesalerAddressMailAddress;
  /** @var array */
  protected $wholesalerAddressPhoneNumber;
  /** @var array */
  protected $wholesalerAddressFaxNumber;
  /** @var array */
  protected $wholesalerAddressVatId;

  /** @var array */
  protected $deliveryAddress;
  /** @var array */
  protected $deliveryAddressContactPerson;
  /** @var array */
  protected $deliveryAddressMailAddress;
  /** @var array */
  protected $deliveryAddressPhoneNumber;
  /** @var array */
  protected $deliveryAddressFaxNumber;
  /** @var array */
  protected $deliveryAddressVatId;

  /** @var array */
  protected $invoiceAddress;
  /** @var array */
  protected $invoiceAddressContactPerson;
  /** @var array */
  protected $invoiceAddressMailAddress;
  /** @var array */
  protected $invoiceAddressPhoneNumber;
  /** @var array */
  protected $invoiceAddressFaxNumber;
  /** @var array */
  protected $invoiceAddressVatId;

  /** @var array */
  protected $representativeAddress;
  /** @var array */
  protected $representativeAddressContactPerson;
  /** @var array */
  protected $representativeAddressMailAddress;
  /** @var array */
  protected $representativeAddressPhoneNumber;
  /** @var array */
  protected $representativeAddressFaxNumber;
  /** @var array */
  protected $representativeAddressVatId;


  /**
   * @param string $name1
   * @param string $name2
   * @param string $name3
   * @param string $street
   * @param string $zipCode
   * @param string $city
   * @param string $countryCode
   * @param string $managingOrganisation
   * @param string $type
   * @param string $sender
   *
   * @return array
   * @url http://www.unece.org/trade/untdid/d96b/trsd/trsdnad.htm
   *
   */
  public function addNameAndAddress($name1, $name2, $name3, $street, $zipCode, $city, $countryCode,
    $managingOrganisation, $type, $sender = ''
  ) {
    if (is_null($sender)) {
      $sender = $this->sender;
    }

    $partyId = '';
    if ($sender) {
      $partyId = [
        self::maxChars($sender),
        '',
        $managingOrganisation,
      ];
    }

    $name = [
      self::maxChars($name1),
    ];
    if ($name2) {
      $name[] = self::maxChars($name2);
    }
    if ($name3) {
      $name[] = self::maxChars($name3);
    }

    return [
      'NAD',
      $type,
      $partyId,
      '',
      $name,
      str_split($street, 35),
      str_split($city, 35),
      [
        '',
      ],
      [
        self::maxChars($zipCode, 9),
      ],
      [
        self::maxChars($countryCode, 2),
      ],
    ];
  }

  /**
   * @param $gln
   * @param $type
   *
   * @return array
   */
  public function addAddressGln($gln, $type)
  {
    return [
      'NAD',
      $type,
      [
        $gln,
        '',
        9,
      ],
    ];
  }


  /**
   * @return array
   */
  public function getManufacturerAddress()
  {
    return $this->manufacturerAddress;
  }

  /**
   * @return array
   */
  public function getWholesalerAddress()
  {
    return $this->wholesalerAddress;
  }

  public function getRepresentativeAddress()
  {
    return $this->representativeAddress;
  }


  /**
   * @param $gln
   */
  public function setManufacturerAddressGln($gln)
  {
    $this->manufacturerAddress = $this->addAddressGln($gln, 'SU');
  }

  public function setWholesalerAddressGln($gln)
  {
    $this->wholesalerAddress = $this->addAddressGln($gln, 'WS');
  }

  /**
   * @param $gln
   */
  public function setDeliveryAddressGln($gln)
  {
    $this->deliveryAddress = $this->addAddressGln($gln, 'ST');
  }

  /**
   * @param $gln
   */
  public function setInvoiceAddressGln($gln)
  {
    $this->invoiceAddress = $this->addAddressGln($gln, 'IV');
  }

  /**
   * @return array
   */
  public function getDeliveryAddress()
  {
    return $this->deliveryAddress;
  }

  /**
   * @param string      $name1
   * @param string      $name2
   * @param string      $name3
   * @param string      $street
   * @param string      $zipCode
   * @param string      $city
   * @param string      $countryCode
   * @param string      $managingOrganisation
   * @param string      $sender
   * @param string|null $vatId
   *
   * @return $this
   */
  public function setManufacturerAddress($name1, $name2 = '', $name3 = '', $street = '', $zipCode = '',
    $city = '', $countryCode = 'DE', $managingOrganisation = 'ZZZ', $sender = null, $vatId = null
  ) {
    $this->manufacturerAddress = $this->addNameAndAddress(
      $name1,
      $name2,
      $name3,
      $street,
      $zipCode,
      $city,
      $countryCode,
      $managingOrganisation,
      'SU',
      $sender
    );
    if ($vatId) {
      $this->manufacturerAddressVatId = self::addRFFSegment('VA', str_replace(' ', '', $vatId));
    }

    return $this;
  }

  /**
   * @param string $name1
   * @param string $name2
   * @param string $name3
   * @param string $street
   * @param string $zipCode
   * @param string $city
   * @param string $countryCode
   * @param string $managingOrganisation
   * @param string $sender
   * @param null   $vatId
   *
   * @return $this
   */
  public function setWholesalerAddress($name1, $name2 = '', $name3 = '', $street = '', $zipCode = '',
    $city = '', $countryCode = 'DE', $managingOrganisation = 'ZZZ', $sender = null, $vatId = null
  ) {
    $this->wholesalerAddress = $this->addNameAndAddress(
      $name1,
      $name2,
      $name3,
      $street,
      $zipCode,
      $city,
      $countryCode,
      $managingOrganisation,
      'WS',
      $sender
    );

    if ($vatId) {
      $this->wholesalerAddressVatId = self::addRFFSegment('VA', str_replace(' ', '', $vatId));
    }

    return $this;
  }

  /**
   * @param string $name1
   * @param string $name2
   * @param string $name3
   * @param string $street
   * @param string $zipCode
   * @param string $city
   * @param string $countryCode
   * @param string $managingOrganisation
   * @param string $sender
   * @param null   $vatId
   *
   * @return $this
   */
  public function setRepresentativeAddress($name1, $name2 = '', $name3 = '', $street = '', $zipCode = '',
    $city = '', $countryCode = 'DE', $managingOrganisation = 'ZZZ', $sender = null, $vatId = null
  ) {
    if (!is_array($this->representativeAddress)) {
      $this->representativeAddress = [];
    }

    $this->representativeAddress[] = $this->addNameAndAddress(
      $name1,
      $name2,
      $name3,
      $street,
      $zipCode,
      $city,
      $countryCode,
      $managingOrganisation,
      'AB',
      $sender
    );
    if ($vatId) {
      $this->representativeAddress[] = self::addRFFSegment('VA', str_replace(' ', '', $vatId));
    }

    return $this;
  }

  /**
   * @param $taxNumber
   *
   * @return NameAndAddress
   */
  public function setRepresentativeAddressTaxNumber($taxNumber)
  {
    $this->representativeAddress[] = self::addRFFSegment('FC', str_replace(' ', '', $taxNumber));

    return $this;
  }

  /**
   * @param string $name1
   * @param string $name2
   * @param string $name3
   * @param string $street
   * @param string $zipCode
   * @param string $city
   * @param string $countryCode
   * @param string $managingOrganisation
   * @param string $sender
   * @param null   $vatId
   *
   * @return $this
   */
  public function setDeliveryAddress($name1, $name2 = '', $name3 = '', $street = '',
    $zipCode = '', $city = '', $countryCode = 'DE', $managingOrganisation = 'ZZZ', $sender = null, $vatId = null
  ) {
    $this->deliveryAddress = $this->addNameAndAddress(
      $name1,
      $name2,
      $name3,
      $street,
      $zipCode,
      $city,
      $countryCode,
      $managingOrganisation,
      'ST',
      $sender
    );

    if ($vatId) {
      $this->deliveryAddressVatId = self::addRFFSegment('VA', str_replace(' ', '', $vatId));
    }

    return $this;
  }

  /**
   * @param string $name1
   * @param string $name2
   * @param string $name3
   * @param string $street
   * @param string $zipCode
   * @param string $city
   * @param string $countryCode
   * @param string $managingOrganisation
   * @param string $sender
   * @param string $vatId
   *
   * @return $this
   */
  public function setInvoiceAddress($name1, $name2 = '', $name3 = '', $street = '', $zipCode = '',
    $city = '', $countryCode = 'DE', $managingOrganisation = 'ZZZ', $sender = null, $vatId = null
  ) {
    $this->invoiceAddress = $this->addNameAndAddress(
      $name1,
      $name2,
      $name3,
      $street,
      $zipCode,
      $city,
      $countryCode,
      $managingOrganisation,
      'IV',
      $sender
    );
    if ($vatId) {
      $this->invoiceAddressVatId = self::addRFFSegment('VA', str_replace(' ', '', $vatId));
    }
    return $this;
  }

}
