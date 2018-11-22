<?php
namespace EDI\Generator\Cohaor;

/**
 * Equipment Details.
 *
 * @see https://service.unece.org/trade/untdid/d17b/trsd/trsdeqd.htm
 */
class EquipmentDetails
{
    private $sEquipmentTypeCodeQualifier = '';
    private $aEquipmentIdentification = [];
    private $aEquipmentSizeAndType = [];
    private $sEquipmentSupplierCode = '';
    private $sEquipmentStatusCode = '';
    private $sFullOrEmptyIndicatorCode = '';
    private $sMarkingInstructionsCode = '';

    /**
     * Set Equipment Type CodeQualifier.
     *
     * @param string $sEquipmentTypeCodeQualifier (8053)
     *
     * @return self $this
     */
    public function setEquipmentTypeCodeQualifier(string $sEquipmentTypeCodeQualifier) : self
    {
        $this->sEquipmentTypeCodeQualifier = $sEquipmentTypeCodeQualifier;
        return $this;
    }

    /**
     * Set Equipment Identification.
     *
     * @param string $sEquipmentIdentifier (8260)
     * @param string $sCodeListIdentificationCode (1131)
     * @param string $sCodeListResponsibleAgencyCode (3055)
     * @param string $sCountryIdentifier (3207)
     *
     * @return self $this
     */
    public function setEquipmentIdentification(string $sEquipmentIdentifier = '', string $sCodeListIdentificationCode = '', string $sCodeListResponsibleAgencyCode = '', string $sCountryIdentifier = '') : self
    {
        $aEquipmentIdentification = [];

        // Equipment Identifier
        $aEquipmentIdentification[] = $sEquipmentIdentifier;

        // Code List Identification Code
        $aEquipmentIdentification[] = $sCodeListIdentificationCode;

        // Code List Responsible Agency Code
        $aEquipmentIdentification[] = $sCodeListResponsibleAgencyCode;

        // Country Identifier
        $aEquipmentIdentification[] = $sCountryIdentifier;

        $this->aEquipmentIdentification = $aEquipmentIdentification;

        return $this;
    }

    /**
     * Set Equipment Size And Type.
     *
     * @param string $sEquipmentSizeAndTypeDescriptionCode (8155)
     * @param string $sCodeListIdentificationCode (1131)
     * @param string $sCodeListResponsibleAgencyCode (3055)
     * @param string $sEquipmentSizeAndTypeDescription (8154)
     *
     * @return self $this
     */
    public function setEquipmentSizeAndType(string $sEquipmentSizeAndTypeDescriptionCode = '', string $sCodeListIdentificationCode = null, string $sCodeListResponsibleAgencyCode = null, string $sEquipmentSizeAndTypeDescription = '')
    {
        $aEquipmentSizeAndType = [];

        // Equipment Size and Type Description Code
        $aEquipmentSizeAndType[] = $sEquipmentSizeAndTypeDescriptionCode;

        // Code List Identification Code
        $aEquipmentSizeAndType[] = $sCodeListIdentificationCode;

        // Code List Responsible Agency Code
        $aEquipmentSizeAndType[] = $sCodeListResponsibleAgencyCode;

        // Equipment Size and Type Description
        $aEquipmentSizeAndType[] = $sEquipmentSizeAndTypeDescription;

        $this->aEquipmentSizeAndType = $aEquipmentSizeAndType;

        return $this;
    }

    /**
     * Set Equipment Supplier Code.
     *
     * @param array $sEquipmentSupplierCode (8077)
     *
     * @return self $this
     */
    public function setEquipmentSupplierCode(string $sEquipmentSupplierCode) : self
    {
        $this->sEquipmentSupplierCode = $sEquipmentSupplierCode;
        return $this;
    }

    /**
     * Set Equipment Status Code.
     *
     * @param array $sEquipmentStatusCode (8249)
     *
     * @return self $this
     */
    public function setEquipmentStatusCode(string $sEquipmentStatusCode) : self
    {
        $this->sEquipmentStatusCode = $sEquipmentStatusCode;
        return $this;
    }

    /**
     * Set Full Or Empty Indicator Code.
     *
     * @param array $sFullOrEmptyIndicatorCode (8169)
     *
     * @return self $this
     */
    public function setFullOrEmptyIndicatorCode(string $sFullOrEmptyIndicatorCode) : self
    {
        $this->sFullOrEmptyIndicatorCode = $sFullOrEmptyIndicatorCode;
        return $this;
    }

    /**
     * Set Marking Instructions Code.
     *
     * @param array $sMarkingInstructionsCode (4233)
     *
     * @return self $this
     */
    public function setMarkingInstructionsCode(string $sMarkingInstructionsCode) : self
    {
        $this->sMarkingInstructionsCode = $sMarkingInstructionsCode;
        return $this;
    }

    /**
     * Compose.
     *
     * @return array $aComposed
     */
    public function compose() : array
    {
        $aComposed = ['EQD'];

        // Equipment Type Code Qualifier
        $aComposed[] = $this->sEquipmentTypeCodeQualifier;

        // Equipment Identification
        $aComposed[] = $this->aEquipmentIdentification;

        // Equipment Size and Type
        $aComposed[] = $this->aEquipmentSizeAndType;

        // Equipment Supplier Code
        $aComposed[] = $this->sEquipmentSupplierCode;

        // Equipment Status Code
        $aComposed[] = $this->sEquipmentStatusCode;

        // Full Or Empty Indicator Code
        $aComposed[] = $this->sFullOrEmptyIndicatorCode;

        // Marking Instructions Code
        $aComposed[] = $this->sMarkingInstructionsCode;

        return $aComposed;
    }
}
