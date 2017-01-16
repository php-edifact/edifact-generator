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
}
