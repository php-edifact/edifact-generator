<?php

namespace EDI\Generator;

class Coreor extends Message
{
    private $dtmSend;
    private $releaseNumber;
    private $dtmExpiration;
    private $previousMessage;
    private $vessel;
    private $pol;
    private $pod;
    private $eta;
    private $sender;
    private $carrier;
    private $forwarder;
    private $customsBroker;
    private $container;
    private $bkg;
    private $tare;
    private $cargoWeight;
    private $seal;
    private $cargoCategory;
    private $emptyDepot;
    private $freightPayer;

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
        $sMessageType = 'COREOR',
        $sMessageVersionNumber = 'D',
        $sMessageReleaseNumber = '00B',
        $sMessageControllingAgencyCoded = 'UN',
        $sAssociationAssignedCode = 'SMDG20'
    ) {
        parent::__construct($sMessageType, $sMessageVersionNumber, $sMessageReleaseNumber,
            $sMessageControllingAgencyCoded, $sMessageReferenceNumber, $sAssociationAssignedCode);

        $this->dtmSend = self::dtmSegment(137, date('YmdHi'));
    }

    /*
     * $size = 22G1, 42G1, ecc
     * 2 = export, 5 = full
     */
    public function setReleaseNumberAndExpiration($number, $expiration)
    {
        $this->releaseNumber = self::rffSegment('RE', $number);
        $this->dtmExpiration = self::dtmSegment(36, $expiration);

        return $this;
    }

    public function setPreviousMessage($number)
    {
        $this->previousMessage = self::rffSegment('ACW', $number);

        return $this;
    }

    /*
     * Vessel information
     *
     */
    public function setVessel($extVoyage, $line, $vslName, $callsign)
    {
        $this->vessel = self::tdtSegment(20, $extVoyage, '', '', [$line, 172, 20], '', '', [$callsign, 146, 11, $vslName]);

        return $this;
    }

    /*
     * Port of Loading
     *
     */
    public function setPOL($loc)
    {
        $this->pol = self::locSegment(9, [$loc, 139, 6]);

        return $this;
    }

    /*
     * Release terminal
     *
     */
    public function setPOD($loc, $terminal)
    {
        $this->pod = self::locSegment(11, [$loc, 139, 6], [$terminal, 72, 'ZZZ']);

        return $this;
    }

    /*
     * Estimated Time of Arrival
     *
     */
    public function setETA($dtm)
    {
        $this->eta = self::dtmSegment(132, $dtm);

        return $this;
    }

    /*
     *
     */
    public function setSender($sender)
    {
        $this->sender = ['NAD', 'MS', $sender];

        return $this;
    }

    /*
     * $line: Master Liner Codes List
     */
    public function setCarrier($line)
    {
        $this->carrier = ['NAD', 'CA', [$line, 172, 20]];

        return $this;
    }

    public function setForwarder($code, $name, $address, $postalCode)
    {
        $name = str_split($name, 35);
        $address = str_split($address, 35);

        $this->forwarder = ['NAD', 'FW', [$code, 160, 'ZZZ'], array_merge($name, $address), '', '', '', '', $postalCode];

        return $this;
    }

    public function setCustomsBroker($code, $name, $address, $postalCode)
    {
        $name = str_split($name, 35);
        $address = str_split($address, 35);

        $this->customsBroker = ['NAD', 'CB', [$code, 160, 'ZZZ'], array_merge($name, $address), '', '', '', '', $postalCode];

        return $this;
    }

    /*
     *
     */
    public function setContainer($number, $size)
    {
        $this->container = \EDI\Generator\Message::eqdSegment('CN', $number, [$size, '102', '5'], '', '', 5);

        return $this;
    }

    /*
     *
     */
    public function setBillOfLading($bl)
    {
        $this->bkg = \EDI\Generator\Message::rffSegment('BM', $bl);

        return $this;
    }

    /*
     * Weight information
     * $type = T (tare), AET (gross weight)
     *
     */
    public function setTare($weight)
    {
        $this->tare = ['MEA', 'AAE', 'T', ['KGM', $weight]];

        return $this;
    }

    public function setCargoWeight($weight)
    {
        $this->cargoWeight = ['MEA', 'AAE', 'AET', ['KGM', $weight]];

        return $this;
    }

    /*
     * $seal = free text
     * $sealIssuer = DE 9303
     */
    public function setSeal($seal)
    {
        $this->seal = ['SEL', $seal];

        return $this;
    }

    /*
     * Cargo category
     *
     */
    public function setCargoCategory($text)
    {
        $this->cargoCategory = ['FTX', 'AAA', '', '', $text];

        return $this;
    }

    /*
     * Redelivery facility
     *
     */
    public function setEmptyDepot($loc, $terminal)
    {
        $this->emptyDepot = self::locSegment(99, [$loc, 139, 6], [$terminal, 72, 'ZZZ']);

        return $this;
    }

    public function setFreightPayer($code, $name, $address, $postalCode)
    {
        $name = str_split($name, 35);
        $address = str_split($address, 35);

        $this->freightPayer = ['NAD', 'FP', [$code, 160, 'ZZZ'], array_merge($name, $address), '', '', '', '', $postalCode];

        return $this;
    }

    /**
     * Compose.
     *
     * @param mixed $sMessageFunctionCode (1225)
     * @param mixed $sDocumentNameCode    (1001)
     * @param mixed $sDocumentIdentifier  (1004)
     *
     * @return parent::compose()
     */
    public function compose(?string $sMessageFunctionCode = "9", ?string $sDocumentNameCode = "129", ?string $sDocumentIdentifier = null): parent
    {
        $this->messageContent = [
            ['BGM', $sDocumentNameCode, $this->messageID, $sMessageFunctionCode],
        ];

        $this->messageContent[] = $this->dtmSend;
        $this->messageContent[] = $this->releaseNumber;
        $this->messageContent[] = $this->dtmExpiration;

        if ($this->previousMessage !== null) {
            $this->messageContent[] = $this->previousMessage;
        }

        $this->messageContent[] = $this->vessel;
        $this->messageContent[] = $this->pol;
        $this->messageContent[] = $this->pod;
        $this->messageContent[] = $this->eta;
        $this->messageContent[] = $this->sender;
        $this->messageContent[] = $this->carrier;
        $this->messageContent[] = $this->forwarder;
        $this->messageContent[] = $this->customsBroker;
        $this->messageContent[] = $this->container;
        $this->messageContent[] = $this->bkg;
        $this->messageContent[] = $this->tare;
        $this->messageContent[] = $this->cargoWeight;
        $this->messageContent[] = $this->seal;
        $this->messageContent[] = $this->cargoCategory;
        $this->messageContent[] = $this->emptyDepot;
        $this->messageContent[] = $this->freightPayer;
        $this->messageContent[] = ['CNT', [16, 1]];

        return parent::compose($sMessageFunctionCode, $sDocumentNameCode, $sDocumentIdentifier);
    }
}
