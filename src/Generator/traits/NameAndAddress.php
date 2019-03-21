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
    protected $invoiceAddress;
    /** @var array */
    protected $invoiceAddressContactPerson;
    /** @var array */
    protected $invoiceAddressMailAddress;
    /** @var array */
    protected $invoiceAddressPhoneNumber;
    /** @var array */
    protected $invoiceAddressFaxNumber;

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
     * @url http://www.unece.org/trade/untdid/d96b/trsd/trsdnad.htm
     *
     * @return array
     */
    public function addNameAndAddress(
        $name1,
        $name2,
        $name3,
        $street,
        $zipCode,
        $city,
        $countryCode,
        $managingOrganisation,
        $type,
        $sender = ''
    ) {
        if ($sender === null) {
            $sender = $this->sender;
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
            [
                self::maxChars($sender),
                '',
                $managingOrganisation,
            ],
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
     * @return array
     */
    public function getManufacturerAddress()
    {
        return $this->manufacturerAddress;
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
     *
     * @return $this
     */
    public function setManufacturerAddress(
        $name1,
        $name2 = '',
        $name3 = '',
        $street = '',
        $zipCode = '',
        $city = '',
        $countryCode = 'DE',
        $managingOrganisation = 'ZZZ',
        $sender = null
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
            $sender ?? ''
        );


        return $this;
    }


    /**
     * @return array
     */
    public function getWholesalerAddress()
    {
        return $this->wholesalerAddress;
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
     *
     * @return $this
     */
    public function setWholesalerAddress(
        $name1,
        $name2 = '',
        $name3 = '',
        $street = '',
        $zipCode = '',
        $city = '',
        $countryCode = 'DE',
        $managingOrganisation = 'ZZZ',
        $sender = null
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
        return $this;
    }

    /**
     * @return array
     */
    public function getDeliveryAddress()
    {
        return $this->deliveryAddress;
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
     *
     * @return $this
     */
    public function setDeliveryAddress(
        $name1,
        $name2 = '',
        $name3 = '',
        $street = '',
        $zipCode = '',
        $city = '',
        $countryCode = 'DE',
        $managingOrganisation = 'ZZZ',
        $sender = null
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
     *
     * @return $this
     */
    public function setInvoiceAddress(
        $name1,
        $name2 = '',
        $name3 = '',
        $street = '',
        $zipCode = '',
        $city = '',
        $countryCode = 'DE',
        $managingOrganisation = 'ZZZ'
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
            'IV'
        );
        return $this;
    }
}
