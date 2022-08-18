<?php

namespace EDI\Generator\Segment;

use EDI\Generator\Segment;

/**
 * Reference.
 *
 * @see https://service.unece.org/trade/untdid/d17b/trsd/trsdrff.htm
 */
class Reference extends Segment
{
    public const SEGMENT_NAME = 'RFF';

    protected $sReferenceCodeQualifier;
    protected $sReferenceIdentifier;
    protected $sDocumentLineIdentifier;
    protected $sVersionIdentifier;
    protected $sRevisionIdentifier;

    /**
     * Set Reference Code Qualifier.
     *
     * @param string $sReferenceCodeQualifier (1153)
     *
     * @return self $this
     */
    public function setReferenceCodeQualifier(string $sReferenceCodeQualifier): self
    {
        $this->sReferenceCodeQualifier = $sReferenceCodeQualifier;

        return $this;
    }

    /**
     * Set Reference Identifier.
     *
     * @param string $sReferenceIdentifier (1154)
     *
     * @return self $this
     */
    public function setReferenceIdentifier(string $sReferenceIdentifier): self
    {
        $this->sReferenceIdentifier = $sReferenceIdentifier;

        return $this;
    }

    /**
     * Set Document Line Identifier.
     *
     * @param string $sDocumentLineIdentifier (1156)
     *
     * @return self $this
     */
    public function setDocumentLineIdentifier(string $sDocumentLineIdentifier): self
    {
        $this->sDocumentLineIdentifier = $sDocumentLineIdentifier;

        return $this;
    }

    /**
     * Set Version Identifier.
     *
     * @param string $sVersionIdentifier (1056)
     *
     * @return self $this
     */
    public function setVersionIdentifier(string $sVersionIdentifier): self
    {
        $this->sVersionIdentifier = $sVersionIdentifier;

        return $this;
    }

    /**
     * Set Revision Identifier.
     *
     * @param string $sRevisionIdentifier (1060)
     *
     * @return self $this
     */
    public function setRevisionIdentifier(string $sRevisionIdentifier): self
    {
        $this->sRevisionIdentifier = $sRevisionIdentifier;

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

        // Reference Code Qualifier
        $aReference[] = $this->sReferenceCodeQualifier;

        // Reference Identifier

        if ($this->sReferenceIdentifier !== null) {
            $aReference[] = $this->sReferenceIdentifier;
        }

        // Document Line Identifier

        if ($this->sDocumentLineIdentifier !== null) {
            $aReference[] = $this->sDocumentLineIdentifier;
        }

        // Version Identifier

        if ($this->sVersionIdentifier !== null) {
            $aReference[] = $this->sVersionIdentifier;
        }

        // Revision Identifier

        if ($this->sRevisionIdentifier !== null) {
            $aReference[] = $this->sRevisionIdentifier;
        }

        if (count($aReference) > 0) {
            $aComposed[] = $aReference;
        }

        $this->setComposed($aComposed);

        return $this;
    }
}
