<?php
namespace EDI\Generator;

class Calinf extends Message
{
    private $dtmSend;
    private $messageSender;
    private $messageReceiver;
    private $vessel;
    private $eta;
    private $etd;
    private $callsign;

    public function __construct($messageID = null, $identifier = 'CALINF', $version = 'D', $release = '00B', $controllingAgency = 'UN', $association = 'SMDG20')
    {
        parent::__construct($identifier, $version, $release, $controllingAgency, $messageID, $association);

        $this->dtmSend = self::dtmSegment(137, date('YmdHi'));

        $this->containers = [];
    }

    /*
     * Date of the message submission
     *
     */
    public function setDTMMessageSendingTime($dtm)
    {
        $this->dtmSend = self::dtmSegment(137, $dtm);
        return $this;
    }

    /*
     * Message sender (usually the vessel agent)
     *
     */
    public function setSender($code, $name)
    {
        $this->messageSender = ['NAD', 'MS', $code, $name];
        return $this;
    }

    /*
     * Message receiver (usually the terminal)
     *
     */
    public function setReceiver($code, $name)
    {
        $this->messageReceiver = ['NAD', 'MR', $code, $name];
        return $this;
    }

    /*
     * Vessel call information
     *
     */
    public function setVessel($extVoyage, $line, $imoNumber, $vslName, $callsign)
    {
        $this->vessel = ['TDT', 20, $extVoyage, '', '', [$line, 172, 20], '', '', [$imoNumber, 146, 54, $vslName]];
        $this->callsign = self::rffSegment('VM', $callsign);
        return $this;
    }

    /*
     * Estimated Time of Arrival
     *
     */
    public function setEta($dtm)
    {
        $this->eta = self::dtmSegment(132, $dtm);
        return $this;
    }

    /*
     * Estimated Time of Departure
     *
     */
    public function setEtd($dtm)
    {
        $this->etd = self::dtmSegment(133, $dtm);
        return $this;
    }

    public function compose($msgStatus = 5, $documentCode = 96)
    {
        $this->messageContent = [
            ['BGM', $documentCode, $this->messageID, $msgStatus]
        ];

        $this->messageContent[] = $this->dtmSend;
        $this->messageContent[] = $this->messageSender;
        $this->messageContent[] = $this->messageReceiver;
        $this->messageContent[] = $this->vessel;
        $this->messageContent[] = $this->eta;
        $this->messageContent[] = $this->etd;
        $this->messageContent[] = $this->callsign;

        parent::compose();
        return $this;
    }
}
