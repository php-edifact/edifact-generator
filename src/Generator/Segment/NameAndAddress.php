<?php

namespace EDI\Generator\Segment;

use EDI\Generator\Segment;

/**
 * Name And Address.
 *
 * @see https://service.unece.org/trade/untdid/d17b/trsd/trsdnad.htm
 */
class NameAndAddress extends Segment
{
    const SEGMENT_NAME = 'NAD';

    protected $sPartyFunctionCodeQualifier;
    protected $aPartyIdentificationDetails = [];
    protected $aNameAndAddress = [];
    protected $aPartyName = [];
    protected $sPartyNameFormatCode;
    protected $aStreet = [];
    protected $sCityName;
    protected $sCountrySubdivisionIdentifier;
    protected $sCodeListIdentificationCode;
    protected $sCodeListResponsibleAgencyCode;
    protected $sCountrySubdivisionName;
    protected $sPostalIdentificationCode;
    protected $sCountryIdentifier;

    /**
     * Set Party Function Code Qualifier.
     *
     * @param string $sPartyFunctionCodeQualifier (3035)
     *
     * @return self $this
     */
    public function setPartyFunctionCodeQualifier(string $sPartyFunctionCodeQualifier): self
    {
        $this->sPartyFunctionCodeQualifier = $sPartyFunctionCodeQualifier;
        return $this;
    }

    /**
     * Set Party Identification Details.
     *
     * @param string $sPartyIdentifier (3039)
     * @param mixed $sCodeListIdentificationCode (1131)
     * @param mixed $sCodeListResponsibleAgencyCode (3055)
     *
     * @return self $this
     */
    public function setPartyIdentificationDetails(
        string $sPartyIdentifier,
        ?string $sCodeListIdentificationCode = null,
        ?string $sCodeListResponsibleAgencyCode = null
    ): self {
        $aPartyIdentificationDetails = [];

        // Party Identifier
        $aPartyIdentificationDetails[] = $sPartyIdentifier;

        // Code List Identification Code

        if ($sCodeListIdentificationCode !== null) {
            $aPartyIdentificationDetails[] = $sCodeListIdentificationCode;
        }

        // Code List Responsible Agency Code

        if ($sCodeListResponsibleAgencyCode !== null) {
            $aPartyIdentificationDetails[] = $sCodeListResponsibleAgencyCode;
        }

        $this->aPartyIdentificationDetails = $aPartyIdentificationDetails;

        return $this;
    }

    /**
     * Set Name And Address.
     *
     * @param array $aNameAndAddressDescription (3124)
     *
     * @return self $this
     */
    public function setNameAndAddress(array $aNameAndAddressDescription): self
    {
        $this->aNameAndAddress = $aNameAndAddressDescription;
        return $this;
    }

    /**
     * Set Party Name.
     *
     * @param array $aPartyName (3036)
     *
     * @return self $this
     */
    public function setPartyName(array $aPartyName): self
    {
        $this->aPartyName = $aPartyName;
        return $this;
    }

    /**
     * Set Party Name Format Code.
     *
     * @param array $sPartyNameFormatCode (3045)
     *
     * @return self $this
     */
    public function setPartyNameFormatCode(string $sPartyNameFormatCode): self
    {
        $this->sPartyNameFormatCode = $sPartyNameFormatCode;
        return $this;
    }

    /**
     * Set Street.
     *
     * @param array $aStreet (3042)
     *
     * @return self $this
     */
    public function setStreet(array $aStreet): self
    {
        $this->aStreet = $aStreet;
        return $this;
    }

    /**
     * Set City Name.
     *
     * @param string $sCityName (3164)
     *
     * @return self $this
     */
    public function setCityName(string $sCityName): self
    {
        $this->sCityName = $sCityName;
        return $this;
    }

    /**
     * Set Country Subdivision Identifier.
     *
     * @param string $sCountrySubdivisionIdentifier (3229)
     *
     * @return self $this
     */
    public function setCountrySubdivisionIdentifier(string $sCountrySubdivisionIdentifier): self
    {
        $this->sCountrySubdivisionIdentifier = $sCountrySubdivisionIdentifier;
        return $this;
    }

    /**
     * Set Code List Identification Code.
     *
     * @param string $sCodeListIdentificationCode (1131)
     *
     * @return self $this
     */
    public function setCodeListIdentificationCode(string $sCodeListIdentificationCode): self
    {
        $this->sCodeListIdentificationCode = $sCodeListIdentificationCode;
        return $this;
    }

    /**
     * Set Code List Responsible Agency Code.
     *
     * @param string $sCodeListResponsibleAgencyCode (3055)
     *
     * @return self $this
     */
    public function setCodeListResponsibleAgencyCode(string $sCodeListResponsibleAgencyCode): self
    {
        $this->sCodeListResponsibleAgencyCode = $sCodeListResponsibleAgencyCode;
        return $this;
    }

    /**
     * Set Country Subdivision Name.
     *
     * @param string $sPostalIdentificationCode (3228)
     *
     * @return self $this
     */
    public function setCountrySubdivisionName(string $sCountrySubdivisionName): self
    {
        $this->sCountrySubdivisionName = $sCountrySubdivisionName;
        return $this;
    }

    /**
     * Set Postal Identification Code.
     *
     * @param string $sPostalIdentificationCode (3251)
     *
     * @return self $this
     */
    public function setPostalIdentificationCode(string $sPostalIdentificationCode): self
    {
        $this->sPostalIdentificationCode = $sPostalIdentificationCode;
        return $this;
    }

    /**
     * Set Country Identifier.
     *
     * @param string $sCountryIdentifier (3207)
     *
     * @comment ISO 3166-1 two alpha country code
     *
     * @return self $this
     */
    public function setCountryIdentifier(string $sCountryIdentifier): self
    {
        $this->sCountryIdentifier = $sCountryIdentifier;
        return $this;
    }

    /**
     * Compose.
     *
     * @return self $this
     */
    public function compose(): self
    {
        $aComposed[] = self::SEGMENT_NAME;

        // Party Function Code Qualifier

        if ($this->sPartyFunctionCodeQualifier !== null) {
            $aComposed[] = $this->sPartyFunctionCodeQualifier;
        }

        // Party Identification Details

        if (count($this->aPartyIdentificationDetails) > 0) {
            $aComposed[] = $this->aPartyIdentificationDetails;
        }

        // Name And Address

        if (count($this->aNameAndAddress) > 0) {
            $aComposed[] = $this->aNameAndAddress;
        }

        // Party Name

        if (count($this->aPartyName) > 0) {
            $aPartyName = $this->aPartyName;

            if ($this->sPartyNameFormatCode !== null) {
                $aPartyName[] = $this->sPartyNameFormatCode;
            }

            $aComposed[] = $aPartyName;
        }

        // Street

        if (count($this->aStreet) > 0) {
            $aComposed[] = $this->aStreet;
        }

        // City Name

        if ($this->sCityName !== null) {
            $aComposed[] = $this->sCityName;
        }

        // Country Subdivision Details

        $aCountrySubdivisionDetails = [];

        if ($this->sCountrySubdivisionIdentifier !== null) {
            $aCountrySubdivisionDetails[] = $this->sCountrySubdivisionIdentifier;
        }

        if ($this->sCodeListIdentificationCode !== null) {
            $aCountrySubdivisionDetails[] = $this->sCodeListIdentificationCode;
        }

        if ($this->sCodeListResponsibleAgencyCode !== null) {
            $aCountrySubdivisionDetails[] = $this->sCodeListResponsibleAgencyCode;
        }

        if ($this->sCountrySubdivisionName !== null) {
            $aCountrySubdivisionDetails[] = $this->sCountrySubdivisionName;
        }

        if (count($aCountrySubdivisionDetails) > 0) {
            $aComposed[] = $aCountrySubdivisionDetails;
        }

        // Postal Identification Code

        if ($this->sPostalIdentificationCode !== null) {
            $aComposed[] = $this->sPostalIdentificationCode;
        }

        // Country Identifier

        if ($this->sCountryIdentifier !== null) {
            $aComposed[] = $this->sCountryIdentifier;
        }

        $this->setComposed($aComposed);

        return $this;
    }
}
