<?php
namespace EDI\Generator;

class Copino extends Message
{
    private $sender;
    private $receiver;
    private $transporter;
    private $vessel;
    private $port;
    private $destination;
    private $dtm;
    private $cntr;
    private $measures;

    public function __construct($messageID = null, $identifier = 'COPINO', $version = 'D', $release = '95B', $controllingAgency = 'UN', $association = 'ITG13')
    {
        parent::__construct($identifier, $version, $release, $controllingAgency, $messageID, $association);

        $this->cntr = [];
        $this->measures = [];
    }

    public function setSenderAndReceiver($sender, $receiver)
    {
        $this->sender = ['NAD', 'MS', $sender];
        $this->receiver = ['NAD', 'MR', $receiver];
        return $this;
    }

    /*
     * trucker
     */
    public function setTransporter($transRef, $modeOfTransport, $meansOfTransport, $carrierName, $plate, $driver)
    {
        $this->transporter = self::tdtSegment(1, $transRef, $modeOfTransport, $meansOfTransport, $carrierName, '', '', [$plate, 146, '', $driver]);
        return $this;
    }

    /*
     * vessel
     */

    public function setVessel($carrierName, $callsign, $vesselName)
    {
        $this->vessel=self::tdtSegment(20, '', 1, 13, $carrierName, '', '', [$callsign, 103, '', $vesselName]);
        return $this;
    }

    /*
     *$type = 7 (actual date time), 132 (estimated date time)
     */
    public function setDTM($dtm)
    {
        $this->dtm = self::dtmSegment(132, $dtm);
        return $this;
    }

    /*
     * $size = 22G1, 42G1, ecc
     * 2 = export, 5 = full
     */
    public function setContainer($number, $size, $booking, $sequence)
    {
        $cntr = [];
        $cntr[] = ['SGP', $number];
        $cntr[] = self::eqdSegment('CN', $number, [$size, '102', '5'], '', 2, 5);
        $cntr[]= self::rffSegment('BN', $booking);
        $cntr[]= self::rffSegment('SQ', $sequence);
        $this->cntr = $cntr;
        return $this;
    }

    /*
     * $weightMode = DE 6313
     * $weight = free text
     * $unit = KGM or LBS
     */
    public function setMeasures($weightMode, $weight, $unit = 'KGM')
    {
        $this->measures = ['MEA', 'AAE', $weightMode, [$unit, $weight]];
        return $this;
    }

    /*
     * $type = 88 (place of receipt)
     */
    public function setPort($locode, $terminal)
    {
        $this->port = self::locSegment(88, [$locode, 139, 6], [$terminal, 72, 306]);
        return $this;
    }

    /*
     * $type = 7 (place of delivery)
     */
    public function setDestination($locode)
    {
        $this->destination = self::locSegment(7, [$locode, 139, 6]);
        return $this;
    }

    /*
     * $documentCode = 660 (delivery) / 661 (pickup)
     */
    public function compose($msgStatus = 9, $documentCode = 661)
    {
        $this->messageContent = [
            ['BGM', $documentCode, $this->messageID, $msgStatus],
            self::rffSegment('XXX', 1)
        ];

        if (count($this->transporter) > 0) {
            $this->messageContent[] = $this->transporter;
        }
        $this->messageContent[] = $this->port;
        $this->messageContent[] = $this->dtm;
        $this->messageContent[] = $this->sender;
        $this->messageContent[] = $this->receiver;
        $this->messageContent[] = ['GID', 1];
        foreach ($this->cntr as $segment) {
            $this->messageContent[] = $segment;
        }
        $this->messageContent[] = $this->measures;
        $this->messageContent[] = $this->vessel;
        $this->messageContent[] = $this->destination;
        $this->messageContent[] = ['CNT', [16, 1]];

        parent::compose();
        return $this;
    }
}
