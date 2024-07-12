<?php

namespace EDI\Generator\Segment;

use EDI\Generator\Segment;

/**
 * Beginning of Message.
 *
 * @see https://service.unece.org/trade/untdid/d17b/trsd/trsdbgm.htm
 */
class BeginningOfMessage extends Segment
{
    public const SEGMENT_NAME = 'BGM';

    protected $aDocument = [];
    protected $aDocumentIdentification = [];
    protected $sMessageFunctionCode;
    protected $sResponseTypeCode;
    protected $sDocumentStatusCode;
    protected $sLanguageNameCode;

    /**
     * Set Document (C002).
     *
     * @param string|null $sDocumentNameCode
     * @param string|null $sCodeListIdentificationCode
     * @param string|null $sCodeListResponsibleAgencyCode
     * @param string|null $sDocumentName
     *
     * @return self
     */
    public function setDocument(
        ?string $sDocumentNameCode = null,
        ?string $sCodeListIdentificationCode = null,
        ?string $sCodeListResponsibleAgencyCode = null,
        ?string $sDocumentName = null
    ): self {
        $aDocument = [];

        if ($sDocumentNameCode !== null) {
            $aDocument[] = $sDocumentNameCode;
        }

        if ($sCodeListIdentificationCode !== null) {
            $aDocument[] = $sCodeListIdentificationCode;
        }

        if ($sCodeListResponsibleAgencyCode !== null) {
            $aDocument[] = $sCodeListResponsibleAgencyCode;
        }

        if ($sDocumentName !== null) {
            $aDocument[] = $sDocumentName;
        }

        $this->aDocument = $aDocument;

        return $this;
    }

    /**
     * Set Document Identification (C106).
     *
     * @param string|null $sDocumentIdentifier
     * @param string|null $sVersionIdentifier
     * @param string|null $sRevisionIdentifier
     *
     * @return self
     */
    public function setDocumentIdentification(
        ?string $sDocumentIdentifier = null,
        ?string $sVersionIdentifier = null,
        ?string $sRevisionIdentifier = null
    ): self {
        $aDocumentIdentification = [];

        if ($sDocumentIdentifier !== null) {
            $aDocumentIdentification[] = $sDocumentIdentifier;
        }

        if ($sVersionIdentifier !== null) {
            $aDocumentIdentification[] = $sVersionIdentifier;
        }

        if ($sRevisionIdentifier !== null) {
            $aDocumentIdentification[] = $sRevisionIdentifier;
        }

        $this->aDocumentIdentification = $aDocumentIdentification;

        return $this;
    }

    /**
     * Set Message Function Code.
     *
     * @param string $sMessageFunctionCode
     *
     * @return self
     */
    public function setMessageFunctionCode(string $sMessageFunctionCode): self
    {
        $this->sMessageFunctionCode = $sMessageFunctionCode;

        return $this;
    }

    /**
     * Set Response Type Code.
     *
     * @param string $sResponseTypeCode
     *
     * @return self
     */
    public function setResponseTypeCode(string $sResponseTypeCode): self
    {
        $this->sResponseTypeCode = $sResponseTypeCode;

        return $this;
    }

    /**
     * Set Document Status Code.
     *
     * @param string $sDocumentStatusCode
     *
     * @return self
     */
    public function setDocumentStatusCode(string $sDocumentStatusCode): self
    {
        $this->sDocumentStatusCode = $sDocumentStatusCode;

        return $this;
    }

    /**
     * Set Language Name Code.
     *
     * @param string $sLanguageNameCode
     *
     * @return self
     */
    public function setLanguageNameCode(string $sLanguageNameCode): self
    {
        $this->sLanguageNameCode = $sLanguageNameCode;

        return $this;
    }

    /**
     * Compose.
     *
     * @return self
     */
    public function compose(): self
    {
        $aComposed = [self::SEGMENT_NAME];

        if (!empty($this->aDocument)) {
            $aComposed[] = $this->aDocument;
        }

        if (!empty($this->aDocumentIdentification)) {
            $aComposed[] = $this->aDocumentIdentification;
        }

        if ($this->sMessageFunctionCode !== null) {
            $aComposed[] = $this->sMessageFunctionCode;
        }

        if ($this->sResponseTypeCode !== null) {
            $aComposed[] = $this->sResponseTypeCode;
        }

        if ($this->sDocumentStatusCode !== null) {
            $aComposed[] = $this->sDocumentStatusCode;
        }

        if ($this->sLanguageNameCode !== null) {
            $aComposed[] = $this->sLanguageNameCode;
        }

        $this->setComposed($aComposed);

        return $this;
    }
}