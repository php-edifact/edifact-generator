<?php

namespace EDI\Generator\Cohaor;

/**
 * Name And Address.
 *
 * @see https://service.unece.org/trade/untdid/d17b/trsd/trsdnad.htm
 */
class NameAndAddress
{
    private $sPartyFunctionCodeQualifier = '';
    private $aPartyIdentificationDetails = [];
    private $aNameAndAddress = [];
    private $sCityName = '';
    private $sCountryIdentifier = '';

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
     * @param string $sCodeListIdentificationCode (1131)
     * @param string $sCodeListResponsibleAgencyCode (3055)
     *
     * @return self $this
     */
    public function setPartyIdentificationDetails(
        string $sPartyIdentifier,
        string $sCodeListIdentificationCode = '',
        string $sCodeListResponsibleAgencyCode = ''
    ): self {
        $aPartyIdentificationDetails = [];

        // Party Identifier
        $aPartyIdentificationDetails[] = $sPartyIdentifier;

        // Code List Identification Code
        $aPartyIdentificationDetails[] = $sCodeListIdentificationCode;

        // Code List Responsible Agency Code
        $aPartyIdentificationDetails[] = $sCodeListResponsibleAgencyCode;

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
     * @return array $aComposed
     */
    public function compose(): array
    {
        $aComposed = ['NAD'];

        // Party Function Code Qualifier
        $aComposed[] = $this->sPartyFunctionCodeQualifier;

        // Party Identification Details
        $aComposed[] = $this->aPartyIdentificationDetails;

        // Name And Address
        $aComposed[] = $this->aNameAndAddress;

        // Party Name
        $aComposed[] = ''; // @todo

        // Street
        $aComposed[] = ''; // @todo

        // City Name
        $aComposed[] = $this->sCityName;

        // Country Subdivision Details
        $aComposed[] = ''; // @todo

        // Postal Identification Code
        $aComposed[] = $this->sPostalIdentificationCode;

        // Country Identifier
        $aComposed[] = $this->sCountryIdentifier;

        return $aComposed;
    }
}
