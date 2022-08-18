<?php

namespace EDI\Generator;

/**
 * Class Coreor
 * @package EDI\Generator
 */
class Coreor extends Message
{
    /**
     * @var array
     */
    private $dtmSend;
    /**
     * @var
     */
    private $releaseNumber;
    /**
     * @var
     */
    private $dtmExpiration;
    /**
     * @var
     */
    private $previousMessage;
    /**
     * @var
     */
    private $vessel;
    /**
     * @var
     */
    private $pol;
    /**
     * @var
     */
    private $pod;
    /**
     * @var
     */
    private $eta;
    /**
     * @var
     */
    private $messageSender;
    /**
     * @var
     */
    private $carrier;
    /**
     * @var
     */
    private $forwarder;
    /**
     * @var
     */
    private $customsBroker;
    /**
     * @var
     */
    private $container;
    /**
     * @var
     */
    private $bkg;
    /**
     * @var
     */
    private $tare;
    /**
     * @var
     */
    private $cargoWeight;
    /**
     * @var
     */
    private $seal;
    /**
     * @var
     */
    private $cargoCategory;
    /**
     * @var
     */
    private $emptyDepot;
    /**
     * @var
     */
    private $freightPayer;

    /**
     * Construct.
     *
     * @param mixed $sMessageReferenceNumber (0062)
     * @param string $sMessageType (0065)
     * @param string $sMessageVersionNumber (0052)
     * @param string $sMessageReleaseNumber (0054)
     * @param string $sMessageControllingAgencyCoded (0051)
     * @param string $sAssociationAssignedCode (0057)
     */
    public function __construct(
        $sMessageReferenceNumber = null,
        $sMessageType = 'COREOR',
        $sMessageVersionNumber = 'D',
        $sMessageReleaseNumber = '00B',
        $sMessageControllingAgencyCoded = 'UN',
        $sAssociationAssignedCode = 'SMDG20'
    ) {
        parent::__construct(
            $sMessageType,
            $sMessageVersionNumber,
            $sMessageReleaseNumber,
            $sMessageControllingAgencyCoded,
            $sMessageReferenceNumber,
            $sAssociationAssignedCode
        );

        $this->dtmSend = self::dtmSegment(137, date('YmdHi'));
    }

    /**
     * $size = 22G1, 42G1, ecc
     * 2 = export, 5 = full
     *
     * @param $number
     * @param $expiration
     * @return $this
     */
    public function setReleaseNumberAndExpiration($number, $expiration)
    {
        $this->releaseNumber = self::rffSegment('RE', $number);
        $this->dtmExpiration = self::dtmSegment(36, $expiration);

        return $this;
    }

    /**
     * @param $number
     * @return $this
     */
    public function setPreviousMessage($number)
    {
        $this->previousMessage = self::rffSegment('ACW', $number);

        return $this;
    }

    /**
     * Vessel information
     *
     * @param $extVoyage
     * @param $line
     * @param $vslName
     * @param $callsign
     * @return $this
     */
    public function setVessel($extVoyage, $line, $vslName, $callsign)
    {
        $this->vessel = self::tdtSegment(20, $extVoyage, '1', '', [$line, 172, 20], '', '', [$callsign, 146, 11, $vslName]);

        return $this;
    }

    /**
     * Port of Loading
     *
     * @param $loc
     * @return $this
     */
    public function setPOL($loc)
    {
        $this->pol = self::locSegment(9, [$loc, 139, 6]);

        return $this;
    }

    /**
     * Release terminal
     *
     * @param $loc
     * @param $terminal
     * @return $this
     */
    public function setPOD($loc, $terminal, $descTerm)
    {
        $this->pod = self::locSegment(11, [$loc, 139, 6], [$terminal, 72, 'ZZZ', $descTerm]);

        return $this;
    }

    /**
     * Estimated Time of Arrival
     *
     * @param $dtm
     * @return $this
     */
    public function setETA($dtm)
    {
        $this->eta = self::dtmSegment(132, $dtm);

        return $this;
    }

    /**
     *
     */
    /**
     * @param $sender
     * @return $this
     */
    public function setMessageSender($sender)
    {
        $this->messageSender = ['NAD', 'MS', $sender];

        return $this;
    }

    /**
     * $line: Master Liner Codes List
     */
    /**
     * @param $line
     * @return $this
     */
    public function setCarrier($line)
    {
        $this->carrier = ['NAD', 'CA', [$line, 172, 20]];

        return $this;
    }

    /**
     * @param $code
     * @param $name
     * @param $address
     * @param $postalCode
     * @return $this
     */
    public function setForwarder($code, $name, $city, $postalCode)
    {
        $this->forwarder = ['NAD', 'FW', [$code, 160, 'ZZZ'], $name, '', '', $city, '', $postalCode];

        return $this;
    }

    /**
     * @param $code
     * @param $name
     * @param $address
     * @param $postalCode
     * @return $this
     */
    public function setCustomsBroker($code, $name, $city, $postalCode)
    {
        $this->customsBroker = ['NAD', 'CB', [$code, 160, 'ZZZ'], $name, '', '', $city, '', $postalCode];

        return $this;
    }


    /**
     * @param $number
     * @param $size
     * @return $this
     */
    public function setContainer($number, $size)
    {
        $this->container = Message::eqdSegment('CN', $number, [$size, '102', '5'], '', '', 5);

        return $this;
    }

    /**
     * @param $bl
     * @return $this
     */
    public function setBillOfLading($bl)
    {
        $this->bkg = Message::rffSegment('BM', $bl);

        return $this;
    }

    /**
     * Weight information
     * $type = T (tare), AET (gross weight)
     * @param $weight
     * @return \EDI\Generator\Coreor
     */
    public function setTare($weight)
    {
        $this->tare = ['MEA', 'AAE', 'T', ['KGM', $weight]];

        return $this;
    }

    /**
     * @param $weight
     * @return $this
     */
    public function setCargoWeight($weight)
    {
        $this->cargoWeight = ['MEA', 'AAE', 'AET', ['KGM', $weight]];

        return $this;
    }

    /**
     * $seal = free text
     * $sealIssuer = DE 9303
     * @param $seal
     * @return \EDI\Generator\Coreor
     */
    public function setSeal($seal)
    {
        $this->seal = ['SEL', $seal];

        return $this;
    }

    /**
     * Cargo category
     *
     * @param $text
     * @return $this
     */
    public function setCargoCategory($text)
    {
        $this->cargoCategory = ['FTX', 'AAA', '', '', $text];

        return $this;
    }

    /**
     * Redelivery facility
     *
     * @param $loc
     * @param $terminal
     * @return $this
     */
    public function setEmptyDepot($loc, $terminal = null, $desc = null)
    {
        $locCode = [$loc, 139, 6];
        if ($desc !== null) {
            $locCode = [$loc, 72, 'ZZZ', $desc];
        }
        $this->emptyDepot = self::locSegment(99, $locCode, $terminal !== null ? [$terminal, 72, 'ZZZ'] : null);

        return $this;
    }

    /**
     * @param $code
     * @param $name
     * @param $address
     * @param $postalCode
     * @return $this
     */
    public function setFreightPayer($code, $name, $city, $postalCode)
    {
        $this->freightPayer = ['NAD', 'FP', [$code, 160, 'ZZZ'], $name, '', '', $city, '', $postalCode];

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
        $this->messageContent[] = $this->messageSender;
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
