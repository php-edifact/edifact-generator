<?php
namespace EDIGen;

class Message
{
    protected $messageID;
    protected $messageContent;

    protected $messageType;
    protected $composed;

    public function __construct($identifier, $version, $release, $controllingAgency, $messageID, $association = null)
    {
        $this->messageType = [$identifier, $version, $release, $controllingAgency];
        if ($association !== null) {
            $this->messageType[] = $association;
        }
        $this->messageID = $messageID;
    }

    public function compose($msgStatus = null)
    {
        $temp=[];
        $temp[]=['UNH', $this->messageID, $this->messageType];

        //$temp=array_merge($temp, $this->messageContent);
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
    static function dtmSegment($type, $dtmString, $format = 203)
    {
        return ['DTM', [$type, $dtmString, $format]];
    }
}
