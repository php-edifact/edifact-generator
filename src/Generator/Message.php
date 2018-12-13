<?php
namespace EDI\Generator;

class Message
{
    protected $messageID;
    protected $messageContent;

    protected $messageType;
    protected $composed;

    public function __construct($identifier, $version, $release = null, $controllingAgency = null, $messageID = null, $association = null)
    {
        $this->messageType = [$identifier, $version];

        if ($release !== null) {
            $this->messageType[] = $release;
        }

        if ($release !== null) {
            $this->messageType[] = $controllingAgency;
        }

        if ($association !== null) {
            $this->messageType[] = $association;
        }

        if ($messageID === null) {
            $this->messageID = 'M'.strtoupper(uniqid());
        } else {
            $this->messageID = $messageID;
        }
    }

    /**
     * Get Composed.
     *
     * @return array $this->aComposed
     */
    public function getComposed(): array
    {
        return $this->aComposed;
    }

    /**
     * Set Composed.
     *
     * @param array $aComposed
     */
    public function setComposed(array $aComposed): void
    {
        $this->aComposed = $aComposed;
    }

    /**
     * Compose.
     *
     * @param mixed $sMessageFunctionCode (1225)
     * @param mixed $sDocumentNameCode (1001)
     * @param mixed $sDocumentIdentifier (1004)
     *
     * @return self $this
     */
    public function compose(?string $sMessageFunctionCode, ?string $sDocumentNameCode, ?string $sDocumentIdentifier): self
    {
        $aComposed = [];

        // Message Header
        $aComposed[] = ['UNH', $this->messageID, $this->messageType];

        // Segments

        foreach ($this->messageContent as $i) {
            $aComposed[] = $i;
        }

        // Message Trailer
        $aComposed[] = ['UNT', (2 + count($this->messageContent)), $this->messageID];

        $this->setComposed($aComposed);

        return $this;
    }

    /*
     * DTM segment
     * $type = 7 (actual date time), 132 (estimated date time), 137 (message date time), 798 (weight date time)
     * $format = 203 (CCYYMMDDHHII)
     */
    public static function dtmSegment($type, $dtmString, $format = 203)
    {
        return ['DTM', [$type, $dtmString, $format]];
    }

    /*
     * RFF segment
     * $functionCode = DE 1153
     * $identifier = max 35 alphanumeric chars
     */
    public static function rffSegment($functionCode, $identifier)
    {
        return ['RFF', [$functionCode, $identifier]];
    }

    /*
     * LOC segment
     * $qualifier = DE 3227
     * $firstLoc = preferred [locode, 139, 6]
     * $secondaryLoc = preferred [locode, 139, 6] (if needed)
     */
    public static function locSegment($qualifier, $firstLoc, $secondaryLoc = null)
    {
        $loc = ['LOC', $qualifier, $firstLoc];
        if ($secondaryLoc !== null) {
            $loc[] = $secondaryLoc;
        }
        return $loc;
    }

    /*
     * EQD segment
     * $eqpType = DE 8053 (for a container CN)
     * $eqpIdentification = for a container [A-Z]{3}U\d{7}
     * $dimension = [XXXX, 102, 5]
     * $supplier = DE 8077, but usually empty
     * $statusCode = DE 8249
     * $fullEmptyIndicatorCode = DE 8169
     */
    public static function eqdSegment($eqpType, $eqpIdentification, $dimension, $supplier = null, $statusCode = null, $fullEmptyIndicatorCode = null)
    {
        $eqd = ['EQD', $eqpType, $eqpIdentification, $dimension];
        if ($supplier !== null) {
            $eqd[] = $supplier;
        }
        if ($statusCode !== null) {
            $eqd[] = $statusCode;
        }
        if ($fullEmptyIndicatorCode !== null) {
            $eqd[] = $fullEmptyIndicatorCode;
        }
        return $eqd;
    }

    /*
     * TDT segment
     * $stageQualifier = DE 8051
     * $journeyIdentifier = max 17 alphanumeric chars
     * $modeOfTransport = DE 8067 (not used)
     * $transportMeans = DE 8179 (not used)
     * $carrier
     * $transitDirection = DE 8101 (not used)
     * $$excessTransportation = DE 8457 (not used)
     * $transportationIdentification
     */
    public static function tdtSegment($stageQualifier, $journeyIdentifier, $modeOfTransport, $transportMeans, $carrier, $transitDirection, $excessTransportation, $transportationIdentification)
    {
        return ['TDT', $stageQualifier, $journeyIdentifier, $modeOfTransport, $transportMeans, $carrier, $transitDirection, $excessTransportation, $transportationIdentification];
    }

    public static function tdtShortSegment($stageQualifier, $journeyIdentifier, $modeOfTransport, $transportMeans)
    {
        return ['TDT', $stageQualifier, $journeyIdentifier, $modeOfTransport, $transportMeans];
    }
}
