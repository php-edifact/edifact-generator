<?php

namespace EDI\Generator;

/**
 * Class Coprar
 * @package EDI\Generator
 */
class Coprar extends Message
{
    private $vessel;
    private $port;
    private $messageCA;
    private $eta;
    private $etd;

    private $containers = [];

    /**
     * Construct.
     *
     * @param mixed  $sMessageReferenceNumber        (0062)
     * @param string $sMessageType                   (0065)
     * @param string $sMessageVersionNumber          (0052)
     * @param string $sMessageReleaseNumber          (0054)
     * @param string $sMessageControllingAgencyCoded (0051)
     * @param string $sAssociationAssignedCode       (0057)
     */
    public function __construct(
        $sMessageReferenceNumber = null,
        $sMessageType = 'COPRAR',
        $sMessageVersionNumber = 'D',
        $sMessageReleaseNumber = '95B',
        $sMessageControllingAgencyCoded = 'UN',
        $sAssociationAssignedCode = 'SMDG16'
    ) {
        parent::__construct($sMessageType, $sMessageVersionNumber, $sMessageReleaseNumber,
            $sMessageControllingAgencyCoded, $sMessageReferenceNumber, $sAssociationAssignedCode);
    }

    /**
     * $line: Master Liner Codes List
     * @param $line
     * @return \EDI\Generator\Coprar
     */
    public function setCarrier($line)
    {
        $this->messageCA = ['NAD', 'CA', [$line, 160, 20]];

        return $this;
    }

    /**
     * Vessel call information
     * @param $extVoyage
     * @param $line
     * @param $vslName
     * @param $callsign
     * @return \EDI\Generator\Coprar
     */
    public function setVessel($extVoyage, $line, $vslName, $callsign)
    {
        $this->vessel = self::tdtSegment(20, $extVoyage, 1, '', [$line, 172, 20], '', '', [$callsign, 103, '', $vslName]);
        $this->callsign = self::rffSegment('VM', $callsign);

        return $this;
    }

    /**
     * $type = 9 (port of loading), 11 (port of discharge)
     * @param $type
     * @param $locode
     * @param null $terminal
     * @return \EDI\Generator\Coprar
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

    /**
     * Estimated Time of Arrival
     * @param $dtm
     * @return \EDI\Generator\Coprar
     */
    public function setEta($dtm)
    {
        $this->eta = self::dtmSegment(132, $dtm);

        return $this;
    }

    /**
     * Estimated Time of Departure
     * @param $dtm
     * @return \EDI\Generator\Coprar
     */
    public function setEtd($dtm)
    {
        $this->etd = self::dtmSegment(133, $dtm);

        return $this;
    }

    /**
     * @param \EDI\Generator\Coprar\Container $container
     * @return $this
     */
    public function addContainer(Coprar\Container $container)
    {
        $this->containers[] = $container;

        return $this;
    }

    /**
     * @param $container
     * @return $this
     */
    public function addContainerSegments($container)
    {
        $this->containers[] = $container;

        return $this;
    }

    /**
     * Compose.
     *
     * @param mixed $sMessageFunctionCode (1225)
     * @param mixed $sDocumentNameCode (1001)
     * @param mixed $sDocumentIdentifier (1004)
     *
     * @return \EDI\Generator\Message ::compose()
     * @throws \EDI\Generator\EdifactException
     */
    public function compose(?string $sMessageFunctionCode = "9", ?string $sDocumentNameCode = "45", ?string $sDocumentIdentifier = null): parent
    {
        $this->messageContent = [
            ['BGM', $sDocumentNameCode, $this->messageID, $sMessageFunctionCode],
            self::rffSegment('XXX', 1),
        ];

        $this->messageContent[] = $this->vessel;
        $this->messageContent[] = $this->port;
        $this->messageContent[] = $this->eta;
        $this->messageContent[] = $this->etd;
        $this->messageContent[] = $this->messageCA;

        foreach ($this->containers as $cntr) {
            $content = $cntr;

            if (is_a($cntr, 'EDI\Generator\Coprar\Container')) {
                $content = $cntr->compose();
            }

            foreach ($content as $segment) {
                $this->messageContent[] = $segment;
            }
        }

        $this->messageContent[] = ['CNT', [16, count($this->containers)]];

        return parent::compose($sMessageFunctionCode, $sDocumentNameCode, $sDocumentIdentifier);
    }
}
