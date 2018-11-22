<?php

namespace EDI\Generator;

class Cohaor extends Message
{
    private $aSegmentGroups = [];

    /**
     * Construct.
     *
     * @param mixed $sMessageReferenceNumber (0062)
     * @param string $sMessageType (0065)
     * @param string $sMessageVersionNumber (0052)
     * @param string $sMessageReleaseNumber (0054)
     * @param string $sMessageControllingAgencyCoded (0051)
     * @param string $sAssociationAssignedCode (0057)
     */
    public function __construct(
        $sMessageReferenceNumber = null,
        $sMessageType = 'COHAOR',
        $sMessageVersionNumber = 'D',
        $sMessageReleaseNumber = '17B',
        $sMessageControllingAgencyCoded = 'UN',
        $sAssociationAssignedCode = 'ITG12'
    ) {
        parent::__construct($sMessageType, $sMessageVersionNumber, $sMessageReleaseNumber,
            $sMessageControllingAgencyCoded, $sMessageReferenceNumber, $sAssociationAssignedCode);
    }

    /**
     * Add Segment Group.
     *
     * @param int $iSegmentGroupNumber
     * @param array $aSegment
     *
     * @return self $this
     */
    public function addSegmentGroup($iSegmentGroupNumber, $aSegments): self
    {
        $this->aSegmentGroups[$iSegmentGroupNumber][] = $aSegments;
        return $this;
    }

    /**
     * Compose.
     *
     * @param int $iMessageFunctionCode (1225)
     * @param int $iDocumentNameCode (1001)
     * @param mixed $sDocumentIdentifier (1004)
     *
     * @return self $this
     */
    public function compose($iMessageFunctionCode = 9, $iDocumentNameCode = 292, $sDocumentIdentifier = null): self
    {
        // BGM - Beginning of message

        $this->messageContent = [
            ['BGM', $iDocumentNameCode, $sDocumentIdentifier, $iMessageFunctionCode]
        ];

        // SEGMENT GROUP 1 .. 11

        if (count($this->aSegmentGroups) > 0) {
            foreach ($this->aSegmentGroups as $iSegmentGroupNumber => $aSegmentGroup) {
                foreach ($aSegmentGroup as $aSegments) {
                    foreach ($aSegments as $aSegment) {
                        $this->messageContent[] = $aSegment;
                    }
                }
            }
        }

        parent::compose();

        return $this;
    }
}
