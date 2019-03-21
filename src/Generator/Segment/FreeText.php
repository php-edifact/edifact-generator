<?php

namespace EDI\Generator\Segment;

use EDI\Generator\Segment;

/**
 * Free Text.
 *
 * @see https://service.unece.org/trade/untdid/d17b/trsd/trsdftx.htm
 */
class FreeText extends Segment
{
    const SEGMENT_NAME = 'FTX';

    protected $sTextSubjectCodeQualifier;
    protected $sFreeTextFunctionCode;
    protected $aTextReference = [];
    protected $aTextLiteral = [];
    protected $sLanguageNameCode;
    protected $sFreeTextFormatCode;

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
     * Set Text Reference (C107).
     *
     * @param string $sFreeTextDescriptionCode       (4441)
     * @param mixed  $sCodeListIdentificationCode    (1131)
     * @param mixed  $sCodeListResponsibleAgencyCode (3055)
     *
     * @return self $this
     */
    public function setTextReference(
        string $sFreeTextDescriptionCode = '',
        ?string $sCodeListIdentificationCode = null,
        ?string $sCodeListResponsibleAgencyCode = null
    ): self {
        $aTextReference = [];

        // Free Text Description Code
        $aTextReference[] = $sFreeTextDescriptionCode;

        // Code List Identification Code

        if ($sCodeListIdentificationCode !== null) {
            $aTextReference[] = $sCodeListIdentificationCode;
        }

        // Code List Responsible Agency Code

        if ($sCodeListResponsibleAgencyCode !== null) {
            $aTextReference[] = $sCodeListResponsibleAgencyCode;
        }

        $this->aTextReference = $aTextReference;

        return $this;
    }

    /**
     * Set Text Literal (C108).
     *
     * @param array $aTextLiteral (4440)
     * @return \EDI\Generator\Segment\FreeText
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
     * @return self $this
     */
    public function compose(): self
    {
        $aComposed[] = self::SEGMENT_NAME;

        // Text Subject Code Qualifier
        $aComposed[] = $this->sTextSubjectCodeQualifier;

        // Free Text Function Code

        if ($this->sFreeTextFunctionCode !== null) {
            $aComposed[] = $this->sFreeTextFunctionCode;
        }

        // Text Reference

        if (count($this->aTextReference) > 0) {
            $aComposed[] = $this->aTextReference;
        }

        // Text Literal

        if (count($this->aTextLiteral) > 0) {
            $aComposed[] = $this->aTextLiteral;
        }

        // Language Name Code

        if ($this->sLanguageNameCode !== null) {
            $aComposed[] = $this->sLanguageNameCode;
        }

        // Free Text Format Code

        if ($this->sFreeTextFormatCode !== null) {
            $aComposed[] = $this->sFreeTextFormatCode;
        }

        // dd($aComposed);

        $this->setComposed($aComposed);

        return $this;
    }
}
