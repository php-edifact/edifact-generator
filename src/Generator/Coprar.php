<?php
namespace EDI\Generator;

class Coprar extends Message
{
    private $vessel;
    private $port;
    private $messageCA;
    private $eta;
    private $etd;

    private $containers;

    public function __construct($messageID = null, $identifier = 'COPRAR', $version = 'D', $release = '95B', $controllingAgency = 'UN', $association = 'SMDG16')
    {
        parent::__construct($identifier, $version, $release, $controllingAgency, $messageID, $association);

        $this->containers = [];
    }

    /*
     * $line: Master Liner Codes List
     */
    public function setCarrier($line)
    {
        $this->messageCA = ['NAD', 'CA', [$line, 160, 20]];
        return $this;
    }

    /*
     * Vessel call information
     *
     */
    public function setVessel($extVoyage, $line, $vslName, $callsign)
    {
        $this->vessel = self::tdtSegment(20, $extVoyage, 1, '', [$line, 172, 20], '', '', [$callsign, 103, '', $vslName]);
        $this->callsign = self::rffSegment('VM', $callsign);
        return $this;
    }

    /*
     * $type = 9 (port of loading), 11 (port of discharge)
     */
    public function setPort($type, $locode, $terminal = null)
    {
        if ($terminal === null) {
            $this->port = self::locSegment($type, [$locode, 139, 6]);
        } else {
            $this->port = self::locSegment($type, [$locode, 139, 6], [$terminal, TER, ZZZ]);
        }
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

    public function addContainer(Coprar\Container $container)
    {
        $this->containers[] = $container;
        return $this;
    }

    /*
     * $documentCode = 43 (discharge), 45 (loading)
     * $msgStatus = 9 (original), 5 (replacement)
     */
    public function compose($msgStatus = 9, $documentCode = 45)
    {
        $this->messageContent = [
            ['BGM', $documentCode, $this->messageID, $msgStatus],
            self::rffSegment('XXX', 1)
        ];

        $this->messageContent[] = $this->vessel;
        $this->messageContent[] = $this->port;
        $this->messageContent[] = $this->eta;
        $this->messageContent[] = $this->etd;
        $this->messageContent[] = $this->messageCA;

        foreach ($this->containers as $cntr) {
            $content = $cntr->compose();
            foreach ($content as $segment) {
                $this->messageContent[] = $segment;
            }
        }

        $this->messageContent[] = ['CNT', [16, count($this->containers)]];
        parent::compose();
        return $this;
    }
}
