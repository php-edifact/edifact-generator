<?php
/**
 * Created by PhpStorm.
 * User: Sascha
 * Date: 23.01.2018
 * Time: 16:21
 */

namespace EDI\Generator\Traits;


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
     * @param string $contactPerson
     * @param string $section unused
     * @return $this
     */
    public function setContactPerson($contactPerson, $section = '')
    {
        $this->contactPerson = ['CTA', $section, $contactPerson];
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
     * @param string $mailAddress
     * @return $this
     */
    public function setMailAddress($mailAddress)
    {
        $this->mailAddress = ['COM', $mailAddress, 'EM'];
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
     * @param string $phoneNumber
     * @return $this
     */
    public function setPhoneNumber($phoneNumber)
    {
        $this->phoneNumber = ['COM', $phoneNumber, 'TE'];
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
     * @param string $faxNumber
     * @return $this
     */
    public function setFaxNumber($faxNumber)
    {
        $this->faxNumber = ['COM', $faxNumber, 'FX'];
        return $this;
    }
}