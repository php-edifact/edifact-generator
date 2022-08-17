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
    protected $buyerAddress;
    /** @var array */
    protected $buyerAddressContactPerson;
    /** @var array */
    protected $buyerAddressMailAddress;
    /** @var array */
    protected $buyerAddressPhoneNumber;
    /** @var array */
    protected $buyerAddressFaxNumber;

    /** @var array */
    protected $consigneeAddress;
    /** @var array */
    protected $consigneeAddressContactPerson;
    /** @var array */
    protected $consigneeAddressMailAddress;
    /** @var array */
    protected $consigneeAddressPhoneNumber;
    /** @var array */
    protected $consigneeAddressFaxNumber;

    /** @var array */
    protected $deliveryPartyAddress;
    /** @var array */
    protected $deliveryPartyAddressContactPerson;
    /** @var array */
    protected $deliveryPartyAddressMailAddress;
    /** @var array */
    protected $deliveryPartyAddressPhoneNumber;
    /** @var array */
    protected $deliveryPartyAddressFaxNumber;

    /** @var array */
    protected $messageRecipientAddress;
    /** @var array */
    protected $messageRecipientAddressContactPerson;
    /** @var array */
    protected $messageRecipientAddressMailAddress;
    /** @var array */
    protected $messageRecipientAddressPhoneNumber;
    /** @var array */
    protected $messageRecipientAddressFaxNumber;

    /** @var array */
    protected $documentMessageSenderAddress;
    /** @var array */
    protected $documentMessageSenderAddressContactPerson;
    /** @var array */
    protected $documentMessageSenderAddressMailAddress;
    /** @var array */
    protected $documentMessageSenderAddressPhoneNumber;
    /** @var array */
    protected $documentMessageSenderAddressFaxNumber;

    /** @var array */
    protected $storeKeeperAddress;
    /** @var array */
    protected $storeKeeperAddressContactPerson;
    /** @var array */
    protected $storeKeeperSenderAddressMailAddress;
    /** @var array */
    protected $storeKeeperSenderAddressPhoneNumber;
    /** @var array */
    protected $storeKeeperSenderAddressFaxNumber;

    /** @var array */
    protected $supplierAddress;
    /** @var array */
    protected $supplierAddressContactPerson;
    /** @var array */
    protected $supplierAddressMailAddress;
    /** @var array */
    protected $supplierAddressPhoneNumber;
    /** @var array */
    protected $supplierAddressFaxNumber;

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
    public function getBuyerAddress()
    {
        return $this->buyerAddress;
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
    public function setBuyerAddress(
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
        $this->buyerAddress = $this->addNameAndAddress(
            $name1,
            $name2,
            $name3,
            $street,
            $zipCode,
            $city,
            $countryCode,
            $managingOrganisation,
            'BY',
            $sender ?? ''
        );

        return $this;
    }

    /**
     * @return array
     */
    public function getConsigneeAddress()
    {
        return $this->consigneeAddress;
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
    public function setConsigneeAddress(
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
        $this->consigneeAddress = $this->addNameAndAddress(
            $name1,
            $name2,
            $name3,
            $street,
            $zipCode,
            $city,
            $countryCode,
            $managingOrganisation,
            'CN',
            $sender ?? ''
        );

        return $this;
    }

    /**
     * @return array
     */
    public function getDeliveryPartyAddress()
    {
        return $this->deliveryPartyAddress;
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
    public function setDeliveryPartyAddress(
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
        $this->deliveryPartyAddress = $this->addNameAndAddress(
            $name1,
            $name2,
            $name3,
            $street,
            $zipCode,
            $city,
            $countryCode,
            $managingOrganisation,
            'DP',
            $sender ?? ''
        );

        return $this;
    }

    /**
     * @return array
     */
    public function getMessageRecipientAddress()
    {
        return $this->messageRecipientAddress;
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
    public function setMessageRecipientAddress(
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
        $this->messageRecipientAddress = $this->addNameAndAddress(
            $name1,
            $name2,
            $name3,
            $street,
            $zipCode,
            $city,
            $countryCode,
            $managingOrganisation,
            'MR',
            $sender ?? ''
        );

        return $this;
    }

    /**
     * @return array
     */
    public function getDocumentMessageSenderAddress()
    {
        return $this->documentMessageSenderAddress;
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
    public function setDocumentMessageSenderAddress(
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
        $this->documentMessageSenderAddress = $this->addNameAndAddress(
            $name1,
            $name2,
            $name3,
            $street,
            $zipCode,
            $city,
            $countryCode,
            $managingOrganisation,
            'MS',
            $sender ?? ''
        );

        return $this;
    }

    /**
     * @return array
     */
    public function getStoreKeeperAddress()
    {
        return $this->storeKeeperAddress;
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
    public function setStoreKeeperAddress(
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
        $this->storeKeeperAddress = $this->addNameAndAddress(
            $name1,
            $name2,
            $name3,
            $street,
            $zipCode,
            $city,
            $countryCode,
            $managingOrganisation,
            'SN',
            $sender ?? ''
        );

        return $this;
    }

    /**
     * @return array
     */
    public function getSupplierAddress()
    {
        return $this->supplierAddress;
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
    public function setSupplierAddress(
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
        $this->supplierAddress = $this->addNameAndAddress(
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
