<?php

namespace EDI\Generator\Segment;

use EDI\Generator\Segment;

/**
 * Reference.
 *
 * @see https://service.unece.org/trade/untdid/d17b/trsd/trsddtm.htm
 */
class Reference extends Segment
{
    protected $sReferenceCodeQualifier = '';
    protected $sReferenceIdentifier = '';
    protected $sDocumentLineIdentifier = '';
    protected $sVersionIdentifier = '';
    protected $sRevisionIdentifier = '';

    /**
     * Set Reference Code Qualifier.
     *
     * @param int $sReferenceCodeQualifier (1153)
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
     * @param int $sReferenceIdentifier (1154)
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
     * @param int $sDocumentLineIdentifier (1156)
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
     * @param int $sVersionIdentifier (1056)
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
     * @param int $sRevisionIdentifier (1060)
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
        $aComposed = ['RFF'];

        // Reference Code Qualifier
        $aComposed[] = $this->sReferenceCodeQualifier;

        // Reference Identifier
        $aComposed[] = $this->sReferenceIdentifier;

        // Document Line Identifier
        $aComposed[] = $this->sDocumentLineIdentifier;

        // Version Identifier
        $aComposed[] = $this->sVersionIdentifier;

        // Revision Identifier
        $aComposed[] = $this->sRevisionIdentifier;

        $this->setComposed($aComposed);

        return $this;
    }
}
