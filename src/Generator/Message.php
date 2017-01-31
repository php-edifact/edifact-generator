<?php
namespace EDI\Generator;

class Message
{
    protected $messageID;
    protected $messageContent;

    protected $messageType;
    protected $composed;

    public function __construct($identifier, $version, $release, $controllingAgency, $messageID = null, $association = null)
    {
        $this->messageType = [$identifier, $version, $release, $controllingAgency];
        if ($association !== null) {
            $this->messageType[] = $association;
        }

        if ($messageID === null) {
            $this->messageID = 'M'.uniqid();
        } else {
            $this->messageID = $messageID;
        }
    }

    public function compose($msgStatus = null)
    {
        $temp=[];
        $temp[]=['UNH', $this->messageID, $this->messageType];

        foreach ($this->messageContent as $i) {
            $temp[] = $i;
        }

        $temp[]=['UNT', (2 + count($this->messageContent)), $this->messageID];

        $this->composed = $temp;
        return $this;
    }

    public function getComposed()
    {
        return $this->composed;
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
    public static function eqdSegment($eqpType, $eqpIdentification, $dimension, $supplier, $statusCode, $fullEmptyIndicatorCode)
    {
        return ['EQD', $eqpType, $eqpIdentification, $dimension, $supplier, $statusCode, $fullEmptyIndicatorCode];
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
