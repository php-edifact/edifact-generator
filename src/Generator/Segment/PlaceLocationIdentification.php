<?php

namespace EDI\Generator\Segment;

use EDI\Generator\Segment;

/**
 * Place Location Identification.
 *
 * @see https://service.unece.org/trade/untdid/d17b/trsd/trsdloc.htm
 */
class PlaceLocationIdentification extends Segment
{
    const SEGMENT_NAME = 'LOC';

    protected $sLocationFunctionCodeQualifier;
    protected $aLocationIdentification = [];
    protected $aRelatedLocationOneIdentification = [];
    protected $aRelatedLocationTwoIdentification = [];
    protected $sRelationCode;

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
     * Set Location Identification (C517).
     *
     * @param mixed $sLocationIdentifier            (3225)
     * @param mixed $sCodeListIdentificationCode    (1131)
     * @param mixed $sCodeListResponsibleAgencyCode (3055)
     * @param mixed $sLocationName                  (3224)
     *
     * @return self $this
     */
    public function setLocationIdentification(
        ?string $sLocationIdentifier = null,
        ?string $sCodeListIdentificationCode = null,
        ?string $sCodeListResponsibleAgencyCode = null,
        ?string $sLocationName = null
    ) {
        $aLocationIdentification = [];

        // Location Identifier

        if ($sLocationIdentifier !== null) {
            $aLocationIdentification[] = $sLocationIdentifier;
        }

        // Code List Identification Code

        if ($sCodeListIdentificationCode !== null) {
            $aLocationIdentification[] = $sCodeListIdentificationCode;
        }

        // Code List Responsible Agency Code

        if ($sCodeListResponsibleAgencyCode !== null) {
            $aLocationIdentification[] = $sCodeListResponsibleAgencyCode;
        }

        // Location Name

        if ($sLocationName !== null) {
            $aLocationIdentification[] = $sLocationName;
        }

        $this->aLocationIdentification = $aLocationIdentification;

        return $this;
    }

    /**
     * Set Related Location One Identification (C519).
     *
     * @param mixed $sFirstRelatedLocationIdentifier (3223)
     * @param mixed $sCodeListIdentificationCode     (1131)
     * @param mixed $sCodeListResponsibleAgencyCode  (3055)
     * @param mixed $sFirstRelatedLocationName       (3222)
     *
     * @return self $this
     */
    public function setRelatedLocationOneIdentification(
        ?string $sFirstRelatedLocationIdentifier = null,
        ?string $sCodeListIdentificationCode = null,
        ?string $sCodeListResponsibleAgencyCode = null,
        ?string $sFirstRelatedLocationName = null
    ) {
        $aRelatedLocationOneIdentification = [];

        // First Related Location Identifier

        if ($sFirstRelatedLocationIdentifier !== null) {
            $aRelatedLocationOneIdentification[] = $sFirstRelatedLocationIdentifier;
        }

        // Code List Identification Code

        if ($sCodeListIdentificationCode !== null) {
            $aRelatedLocationOneIdentification[] = $sCodeListIdentificationCode;
        }

        // Code List Responsible Agency Code

        if ($sCodeListResponsibleAgencyCode !== null) {
            $aRelatedLocationOneIdentification[] = $sCodeListResponsibleAgencyCode;
        }

        // First Related Location Name

        if ($sFirstRelatedLocationName !== null) {
            $aRelatedLocationOneIdentification[] = $sFirstRelatedLocationName;
        }

        $this->aRelatedLocationOneIdentification = $aRelatedLocationOneIdentification;

        return $this;
    }

    /**
     * Set Related Location Two Identification (C553).
     *
     * @param mixed $sSecondRelatedLocationIdentifier (3233)
     * @param mixed $sCodeListIdentificationCode      (1131)
     * @param mixed $sCodeListResponsibleAgencyCode   (3055)
     * @param mixed $sSecondRelatedLocationName       (3232)
     *
     * @return self $this
     */
    public function setRelatedLocationTwoIdentification(
        ?string $sSecondRelatedLocationIdentifier = null,
        ?string $sCodeListIdentificationCode = null,
        ?string $sCodeListResponsibleAgencyCode = null,
        ?string $sSecondRelatedLocationName = null
    ) {
        $aRelatedLocationTwoIdentification = [];

        // Second Related Location Identifier

        if ($sSecondRelatedLocationIdentifier !== null) {
            $aRelatedLocationTwoIdentification[] = $sSecondRelatedLocationIdentifier;
        }

        // Code List Identification Code

        if ($sCodeListIdentificationCode !== null) {
            $aRelatedLocationTwoIdentification[] = $sCodeListIdentificationCode;
        }

        // Code List responsible Agency Code

        if ($sCodeListResponsibleAgencyCode !== null) {
            $aRelatedLocationTwoIdentification[] = $sCodeListResponsibleAgencyCode;
        }

        // Second Related Location Name

        if ($sSecondRelatedLocationName !== null) {
            $aRelatedLocationTwoIdentification[] = $sSecondRelatedLocationName;
        }

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
     * @return self $this
     */
    public function compose(): self
    {
        $aComposed[] = self::SEGMENT_NAME;

        // Location Function Code Qualifier
        $aComposed[] = $this->sLocationFunctionCodeQualifier;

        // Location Identification

        if (count($this->aLocationIdentification) > 0) {
            $aComposed[] = $this->aLocationIdentification;
        }

        // Related Location One Identification

        if (count($this->aRelatedLocationOneIdentification) > 0) {
            $aComposed[] = $this->aRelatedLocationOneIdentification;
        }

        // Related Location Two Identification

        if (count($this->aRelatedLocationTwoIdentification) > 0) {
            $aComposed[] = $this->aRelatedLocationTwoIdentification;
        }

        // Relation Code

        if ($this->sRelationCode !== null) {
            $aComposed[] = $this->sRelationCode;
        }

        $this->setComposed($aComposed);

        return $this;
    }
}
