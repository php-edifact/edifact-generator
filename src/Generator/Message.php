<?php

namespace EDI\Generator;

/**
 * Class Message
 * @package EDI\Generator
 */
class Message extends Base
{
    /** @var string */
    protected $messageID;

    protected $messageContent;

    /** @var array string */
    protected $messageType;

    public function __construct(
        string $identifier,
        string $version,
        ?string $release = null,
        ?string $controllingAgency = null,
        ?string $messageID = null,
        ?string $association = null
    ) {
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
            $this->messageID = 'M' . strtoupper(uniqid());
        } else {
            $this->messageID = $messageID;
        }
    }

    public function setMessageContent($messageContent)
    {
        $this->messageContent = $messageContent;
    }

    public function getMessageID()
    {
        return $this->messageID;
    }

    /**
     * Compose.
     * @throws \EDI\Generator\EdifactException
     */
    public function compose()
    {
        $aComposed = [];

        // Message Header
        $aComposed[] = ['UNH', $this->messageID, $this->messageType];

        if (count($this->messageContent) == 0) {
            throw new EdifactException('no content available for message');
        }

        // Segments
        foreach ($this->messageContent as $i) {
            $aComposed[] = $i;
        }

        $this->composed = $aComposed;

        return $this;
    }

    /**
     * DTM segment
     * $type = 7 (actual date time), 132 (estimated date time), 137 (message date time), 798 (weight date time)
     * $format = 203 (CCYYMMDDHHII)
     * @param $type
     * @param $dtmString
     * @param int $format
     * @return array
     */
    public static function dtmSegment($type, $dtmString, $format = 203)
    {
        return ['DTM', [$type, $dtmString, $format]];
    }

    /**
     * RFF segment
     * $functionCode = DE 1153
     * $identifier = max 35 alphanumeric chars
     * @param $functionCode
     * @param $identifier
     * @return array
     */
    public static function rffSegment($functionCode, $identifier)
    {
        return ['RFF', [$functionCode, $identifier]];
    }

    /**
     * LOC segment
     * $qualifier = DE 3227
     * $firstLoc = preferred [locode, 139, 6]
     * $secondaryLoc = preferred [locode, 139, 6] (if needed)
     * @param $qualifier
     * @param $firstLoc
     *@param $secondaryLoc
     * @return array
     */
    public static function locSegment($qualifier, $firstLoc, $secondaryLoc = null)
    {
        $loc = ['LOC', $qualifier, $firstLoc];
        if ($secondaryLoc !== null) {
            $loc[] = $secondaryLoc;
        }

        return $loc;
    }

    /**
     * EQD segment
     * $eqpType = DE 8053 (for a container CN)
     * $eqpIdentification = for a container [A-Z]{3}U\d{7}
     * $dimension = [XXXX, 102, 5]
     * $supplier = DE 8077, but usually empty
     * $statusCode = DE 8249
     * $fullEmptyIndicatorCode = DE 8169
     * @param $eqpType
     * @param $eqpIdentification
     * @param $dimension
     *@param $supplier
     *@param $statusCode
     *@param $fullEmptyIndicatorCode
     * @return array
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

    /**
     * TDT segment
     * $stageQualifier = DE 8051
     * $journeyIdentifier = max 17 alphanumeric chars
     * $modeOfTransport = DE 8067 (not used)
     * $transportMeans = DE 8179 (not used)
     * $carrier
     * $transitDirection = DE 8101 (not used)
     * $$excessTransportation = DE 8457 (not used)
     * $transportationIdentification
     * @param $stageQualifier
     * @param $journeyIdentifier
     * @param $modeOfTransport
     * @param $transportMeans
     * @param $carrier
     * @param $transitDirection
     * @param $excessTransportation
     * @param $transportationIdentification
     * @return array
     */
    public static function tdtSegment($stageQualifier, $journeyIdentifier, $modeOfTransport, $transportMeans, $carrier, $transitDirection, $excessTransportation, $transportationIdentification)
    {
        return ['TDT', $stageQualifier, $journeyIdentifier, $modeOfTransport, $transportMeans, $carrier, $transitDirection, $excessTransportation, $transportationIdentification];
    }

    /**
     * @param $stageQualifier
     * @param $journeyIdentifier
     * @param $modeOfTransport
     * @param $transportMeans
     * @return array
     */
    public static function tdtShortSegment($stageQualifier, $journeyIdentifier, $modeOfTransport, $transportMeans, $carrier = null)
    {
        $tdt = ['TDT', $stageQualifier, $journeyIdentifier, $modeOfTransport, $transportMeans];
        if ($carrier !== null) {
            $tdt[] = $carrier;
        }
        return $tdt;
    }

    /**
     * @param string $text
     * @param string $qualifier
     * @param string $reference
     * @return array
     */
    public static function addFTXSegment($text, $qualifier, $reference = '')
    {
        $textLines = str_split($text, 70);
        if (count($textLines) > 5) {
            $textLines = array_slice($textLines, 0, 5);
        }

        return ['FTX', $qualifier, '', [$reference, '89'], $textLines];
    }
}
