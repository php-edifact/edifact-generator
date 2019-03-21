<?php

namespace EDI\Generator\Traits;

/**
 * Trait ContactPerson
 *
 * @package EDI\Generator\Traits
 */
trait ContactPerson
{
    /** @var array */
    protected $contactPerson;
    /** @var array */
    protected $mailAddress;
    /** @var array */
    protected $phoneNumber;
    /** @var array */
    protected $faxNumber;

    /**
     * @return array
     */
    public function getContactPerson()
    {
        return $this->contactPerson;
    }

    /**
     * @param string      $contactPerson
     * @param string      $section
     * @param null|string $prefix used for prefixing the variableName deliveryAddressContactPerson
     *
     * @return $this
     */
    public function setContactPerson($contactPerson, $section = '', $prefix = null)
    {
        $var = "contactPerson";
        if ($prefix) {
            $var = $prefix . ucfirst($var);
        }

        $this->{$var} = [
      'CTA',
      $section,
      [
        $section,
        $contactPerson,
      ],
    ];

        return $this;
    }

    /**
     * @return array
     */
    public function getMailAddress()
    {
        return $this->mailAddress;
    }

    /**
     * @param string      $mailAddress
     *
     * @param null|string $prefix
     *
     * @return $this
     */
    public function setMailAddress($mailAddress, $prefix = null)
    {
        $var = "mailAddress";
        if ($prefix) {
            $var = $prefix . ucfirst($var);
        }
        $this->{$var} = [
      'COM',
      [
        $mailAddress,
        'EM',
      ],
    ];
        return $this;
    }

    /**
     * @return array
     */
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    /**
     * @param string      $phoneNumber
     *
     * @param null|string $prefix
     *
     * @return $this
     */
    public function setPhoneNumber($phoneNumber, $prefix = null)
    {
        $var = "phoneNumber";
        if ($prefix) {
            $var = $prefix . ucfirst($var);
        }
        $this->{$var} = [
      'COM',
      [
        $phoneNumber,
        'TE',
      ],
    ];
        return $this;
    }

    /**
     * @return array
     */
    public function getFaxNumber()
    {
        return $this->faxNumber;
    }

    /**
     * @param string      $faxNumber
     *
     * @param null|string $prefix
     *
     * @return $this
     */
    public function setFaxNumber($faxNumber, $prefix = null)
    {
        $var = "faxNumber";
        if ($prefix) {
            $var = $prefix . ucfirst($var);
        }

        $this->{$var} = [
      'COM',
      [
        $faxNumber,
        'FX',
      ],
    ];
        return $this;
    }
}
