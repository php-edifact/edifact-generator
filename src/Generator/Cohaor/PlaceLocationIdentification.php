<?php

namespace EDI\Generator\Cohaor;

/**
 * Place Location Identification.
 *
 * @see https://service.unece.org/trade/untdid/d17b/trsd/trsdloc.htm
 */
class PlaceLocationIdentification
{
    private $sLocationFunctionCodeQualifier = '';
    private $aLocationIdentification = [];
    private $aRelatedLocationOneIdentification = [];
    private $aRelatedLocationTwoIdentification = [];
    private $sRelationCode = '';

    /**
     * Set Location Function Code Qualifier.
     *
     * @param string $sLocationFunctionCodeQualifier (3227)
     *
     * @return self $this
     */
    public function setLocationFunctionCodeQualifier(string $sLocationFunctionCodeQualifier): self
    {
        $this->sLocationFunctionCodeQualifier = $sLocationFunctionCodeQualifier;
        return $this;
    }

    /**
     * Set Location Identification.
     *
     * @param string $sLocationIdentifier (3225)
     * @param string $sCodeListIdentificationCode (1131)
     * @param string $sCodeListResponsibleAgencyCode (3055)
     * @param string $sLocationName (3224)
     *
     * @return self $this
     */
    public function setLocationIdentification(
        string $sLocationIdentifier = '',
        string $sCodeListIdentificationCode = '',
        string $sCodeListResponsibleAgencyCode = '',
        string $sLocationName = ''
    ) {
        $aLocationIdentification = [];

        // Location Identifier
        $aLocationIdentification[] = $sLocationIdentifier;

        // Code List Identification Code
        $aLocationIdentification[] = $sCodeListIdentificationCode;

        // Code List Responsible Agency Code
        $aLocationIdentification[] = $sCodeListResponsibleAgencyCode;

        // Location Name
        $aLocationIdentification[] = $sLocationName;

        $this->aLocationIdentification = $aLocationIdentification;

        return $this;
    }

    /**
     * Set Related Location One Identification.
     *
     * @param string $sFirstRelatedLocationIdentifier (3223)
     * @param string $sCodeListIdentificationCode (1131)
     * @param string $sCodeListResponsibleAgencyCode (3055)
     * @param string $sFirstRelatedLocationName (3222)
     *
     * @return self $this
     */
    public function setRelatedLocationOneIdentification(
        string $sFirstRelatedLocationIdentifier = '',
        string $sCodeListIdentificationCode = '',
        string $sCodeListResponsibleAgencyCode = '',
        string $sFirstRelatedLocationName = ''
    ) {
        $aRelatedLocationOneIdentification = [];

        // First Related Location Identifier
        $aRelatedLocationOneIdentification[] = $sFirstRelatedLocationIdentifier;

        // Code List Identification Code
        $aRelatedLocationOneIdentification[] = $sCodeListIdentificationCode;

        // Code List Responsible Agency Code
        $aRelatedLocationOneIdentification[] = $sCodeListResponsibleAgencyCode;

        // First Related Location Name
        $aRelatedLocationOneIdentification[] = $sFirstRelatedLocationName;

        $this->aRelatedLocationOneIdentification = $aRelatedLocationOneIdentification;

        return $this;
    }

    /**
     * Set Related Location Two Identification.
     *
     * @param string $sSecondRelatedLocationIdentifier (3233)
     * @param string $sCodeListIdentificationCode (1131)
     * @param string $sCodeListResponsibleAgencyCode (3055)
     * @param string $sSecondRelatedLocationName (3232)
     *
     * @return self $this
     */
    public function setRelatedLocationTwoIdentification(
        string $sSecondRelatedLocationIdentifier = '',
        string $sCodeListIdentificationCode = '',
        string $sCodeListResponsibleAgencyCode = '',
        string $sSecondRelatedLocationName = ''
    ) {
        $aRelatedLocationTwoIdentification = [];

        // Second Related Location Identifier
        $aRelatedLocationTwoIdentification[] = $sSecondRelatedLocationIdentifier;

        // Code List Identification Code
        $aRelatedLocationTwoIdentification[] = $sCodeListIdentificationCode;

        // Code List responsible Agency Code
        $aRelatedLocationTwoIdentification[] = $sCodeListResponsibleAgencyCode;

        // Second Related Location Name
        $aRelatedLocationTwoIdentification[] = $sSecondRelatedLocationName;

        $this->aRelatedLocationTwoIdentification = $aRelatedLocationTwoIdentification;

        return $this;
    }

    /**
     * Set Relation Code.
     *
     * @param string $sRelationCode (5479)
     *
     * @return self $this
     */
    public function setRelationCode(string $sRelationCode): self
    {
        $this->sRelationCode = $sRelationCode;
        return $this;
    }

    /**
     * Compose.
     *
     * @return array $aComposed
     */
    public function compose(): array
    {
        $aComposed = ['LOC'];

        // Location Function Code Qualifier
        $aComposed[] = $this->sLocationFunctionCodeQualifier;

        // Location Identification
        $aComposed[] = $this->aLocationIdentification;

        // Related Location One Identification
        $aComposed[] = $this->aRelatedLocationOneIdentification;

        // Related Location Two Identification
        $aComposed[] = $this->aRelatedLocationTwoIdentification;

        // Relation Code
        $aComposed[] = $this->sRelationCode;

        return $aComposed;
    }
}
