<?php

namespace EDI\Generator\Cohaor;

/**
 * Free Text.
 *
 * @see https://service.unece.org/trade/untdid/d17b/trsd/trsdftx.htm
 */
class FreeText
{
    private $sTextSubjectCodeQualifier = '';
    private $sFreeTextFunctionCode = '';
    private $aTextReference = [];
    private $aTextLiteral = [];
    private $sLanguageNameCode = '';
    private $sFreeTextFormatCode = '';

    /**
     * Set Text Subject Code Qualifier.
     *
     * @param string $sTextSubjectCodeQualifier (4451)
     *
     * @return self $this
     */
    public function setTextSubjectCodeQualifier(string $sTextSubjectCodeQualifier): self
    {
        $this->sTextSubjectCodeQualifier = $sTextSubjectCodeQualifier;
        return $this;
    }

    /**
     * Set Free Text Function Code.
     *
     * @param string $sFreeTextFunctionCode (4453)
     *
     * @return self $this
     */
    public function setFreeTextFunctionCode(string $sFreeTextFunctionCode): self
    {
        $this->sFreeTextFunctionCode = $sFreeTextFunctionCode;
        return $this;
    }

    /**
     * Set Text Reference.
     *
     * @param string $sFreeTextDescriptionCode (4441)
     * @param string $sCodeListIdentificationCode (1131)
     * @param string $sCodeListResponsibleAgencyCode (3055)
     *
     * @return self $this
     */
    public function setTextReference(
        string $sFreeTextDescriptionCode = '',
        string $sCodeListIdentificationCode = '',
        string $sCodeListResponsibleAgencyCode = ''
    ): self {
        $aTextReference = [];

        // Free Text Description Code
        $aTextReference[] = $sFreeTextDescriptionCode;

        // Code List Identification Code
        $aTextReference[] = $sCodeListIdentificationCode;

        // Code List Responsible Agency Code
        $aTextReference[] = $sCodeListResponsibleAgencyCode;

        $this->aTextReference = $aTextReference;

        return $this;
    }

    /**
     * Set Text Literal.
     *
     * @param array $aTextLiteral (4440)
     */
    public function setTextLiteral(array $aTextLiteral): self
    {
        $this->aTextLiteral = $aTextLiteral;

        return $this;
    }

    /**
     * Set Language Name Code.
     *
     * @param string $sLanguageNameCode (4451)
     *
     * @comment ISO 639-1
     *
     * @return self $this
     */
    public function setLanguageNameCode(string $sLanguageNameCode): self
    {
        $this->sLanguageNameCode = $sLanguageNameCode;
        return $this;
    }

    /**
     * Set Free Text Format Code.
     *
     * @param string $sFreeTextFormatCode (4447)
     *
     * @return self $this
     */
    public function setFreeTextFormatCode(string $sFreeTextFormatCode): self
    {
        $this->sFreeTextFormatCode = $sFreeTextFormatCode;
        return $this;
    }

    /**
     * Compose.
     *
     * @return array $aComposed
     */
    public function compose(): array
    {
        $aComposed = ['FTX'];

        // Text Subject Code Qualifier
        $aComposed[] = $this->sTextSubjectCodeQualifier;

        // Free Text Function Code
        $aComposed[] = $this->sFreeTextFunctionCode;

        // Text Reference
        $aComposed[] = $this->aTextReference;

        // Text Literal
        $aComposed[] = $this->aTextLiteral;

        // Language Name Code
        $aComposed[] = $this->sLanguageNameCode;

        // Free Text Format Code
        $aComposed[] = $this->sFreeTextFormatCode;

        return $aComposed;
    }
}
