<?php

namespace EDI\Generator\Segment;

use EDI\Generator\Segment;

/**
 * Equipment Details.
 *
 * @see https://service.unece.org/trade/untdid/d17b/trsd/trsdeqd.htm
 */
class EquipmentDetails extends Segment
{
    const SEGMENT_NAME = 'EQD';

    protected $sEquipmentTypeCodeQualifier;
    protected $aEquipmentIdentification = [];
    protected $aEquipmentSizeAndType = [];
    protected $sEquipmentSupplierCode;
    protected $sEquipmentStatusCode;
    protected $sFullOrEmptyIndicatorCode;
    protected $sMarkingInstructionsCode;

    /**
     * Set Equipment Type CodeQualifier.
     *
     * @param string $sEquipmentTypeCodeQualifier (8053)
     *
     * @return self $this
     */
    public function setEquipmentTypeCodeQualifier(string $sEquipmentTypeCodeQualifier): self
    {
        $this->sEquipmentTypeCodeQualifier = $sEquipmentTypeCodeQualifier;

        return $this;
    }

    /**
     * Set Equipment Identification (C237).
     *
     * @param mixed $sEquipmentIdentifier           (8260)
     * @param mixed $sCodeListIdentificationCode    (1131)
     * @param mixed $sCodeListResponsibleAgencyCode (3055)
     * @param mixed $sCountryIdentifier             (3207)
     *
     * @return self $this
     */
    public function setEquipmentIdentification(
        ?string $sEquipmentIdentifier = null,
        ?string $sCodeListIdentificationCode = null,
        ?string $sCodeListResponsibleAgencyCode = null,
        ?string $sCountryIdentifier = null
    ): self {
        $aEquipmentIdentification = [];

        // Equipment Identifier

        if ($sEquipmentIdentifier !== null) {
            $aEquipmentIdentification[] = $sEquipmentIdentifier;
        }

        // Code List Identification Code

        if ($sCodeListIdentificationCode !== null) {
            $aEquipmentIdentification[] = $sCodeListIdentificationCode;
        }

        // Code List Responsible Agency Code

        if ($sCodeListResponsibleAgencyCode !== null) {
            $aEquipmentIdentification[] = $sCodeListResponsibleAgencyCode;
        }

        // Country Identifier

        if ($sCountryIdentifier !== null) {
            $aEquipmentIdentification[] = $sCountryIdentifier;
        }

        $this->aEquipmentIdentification = $aEquipmentIdentification;

        return $this;
    }

    /**
     * Set Equipment Size And Type (C224).
     *
     * @param mixed $sEquipmentSizeAndTypeDescriptionCode (8155)
     * @param mixed $sCodeListIdentificationCode          (1131)
     * @param mixed $sCodeListResponsibleAgencyCode       (3055)
     * @param mixed $sEquipmentSizeAndTypeDescription     (8154)
     *
     * @return self $this
     */
    public function setEquipmentSizeAndType(
        ?string $sEquipmentSizeAndTypeDescriptionCode = null,
        ?string $sCodeListIdentificationCode = null,
        ?string $sCodeListResponsibleAgencyCode = null,
        ?string $sEquipmentSizeAndTypeDescription = null
    ) {
        if (empty($sEquipmentSizeAndTypeDescriptionCode)) {
            return $this;
        }

        $aEquipmentSizeAndType = [];

        // Equipment Size and Type Description Code

        if ($sEquipmentSizeAndTypeDescriptionCode !== null) {
            $aEquipmentSizeAndType[] = $sEquipmentSizeAndTypeDescriptionCode;
        }

        // Code List Identification Code

        if ($sCodeListIdentificationCode !== null) {
            $aEquipmentSizeAndType[] = $sCodeListIdentificationCode;
        }

        // Code List Responsible Agency Code

        if ($sCodeListResponsibleAgencyCode !== null) {
            $aEquipmentSizeAndType[] = $sCodeListResponsibleAgencyCode;
        }

        // Equipment Size and Type Description

        if ($sEquipmentSizeAndTypeDescription !== null) {
            $aEquipmentSizeAndType[] = $sEquipmentSizeAndTypeDescription;
        }

        $this->aEquipmentSizeAndType = $aEquipmentSizeAndType;

        return $this;
    }

    /**
     * Set Equipment Supplier Code.
     *
     * @param string $sEquipmentSupplierCode (8077)
     *
     * @return self $this
     */
    public function setEquipmentSupplierCode(string $sEquipmentSupplierCode): self
    {
        $this->sEquipmentSupplierCode = $sEquipmentSupplierCode;

        return $this;
    }

    /**
     * Set Equipment Status Code.
     *
     * @param string $sEquipmentStatusCode (8249)
     *
     * @return self $this
     */
    public function setEquipmentStatusCode(string $sEquipmentStatusCode): self
    {
        $this->sEquipmentStatusCode = $sEquipmentStatusCode;

        return $this;
    }

    /**
     * Set Full Or Empty Indicator Code.
     *
     * @param string $sFullOrEmptyIndicatorCode (8169)
     *
     * @return self $this
     */
    public function setFullOrEmptyIndicatorCode(string $sFullOrEmptyIndicatorCode): self
    {
        $this->sFullOrEmptyIndicatorCode = $sFullOrEmptyIndicatorCode;

        return $this;
    }

    /**
     * Set Marking Instructions Code.
     *
     * @param string $sMarkingInstructionsCode (4233)
     *
     * @return self $this
     */
    public function setMarkingInstructionsCode(string $sMarkingInstructionsCode): self
    {
        $this->sMarkingInstructionsCode = $sMarkingInstructionsCode;

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

        // Equipment Type Code Qualifier

        if ($this->sEquipmentTypeCodeQualifier !== null) {
            $aComposed[] = $this->sEquipmentTypeCodeQualifier;
        }

        // Equipment Identification

        if (count($this->aEquipmentIdentification) > 0) {
            $aComposed[] = $this->aEquipmentIdentification;
        }

        // Equipment Size and Type

        if (count($this->aEquipmentSizeAndType) > 0) {
            $aComposed[] = $this->aEquipmentSizeAndType;
        }

        // Equipment Supplier Code

        if ($this->sEquipmentSupplierCode !== null) {
            $aComposed[] = $this->sEquipmentSupplierCode;
        }

        // Equipment Status Code

        if ($this->sEquipmentStatusCode !== null) {
            $aComposed[] = $this->sEquipmentStatusCode;
        }

        // Full Or Empty Indicator Code

        if ($this->sFullOrEmptyIndicatorCode !== null) {
            $aComposed[] = $this->sFullOrEmptyIndicatorCode;
        }

        // Marking Instructions Code

        if ($this->sMarkingInstructionsCode !== null) {
            $aComposed[] = $this->sMarkingInstructionsCode;
        }

        $this->setComposed($aComposed);

        return $this;
    }
}
