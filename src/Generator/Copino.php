<?php

namespace EDI\Generator;

/**
 * Class Copino
 * @package EDI\Generator
 */
class Copino extends Message
{
    private $sender;
    private $receiver;
    private $transporter;
    private $vessel;
    private $port;
    private $destination;
    private $dtm;
    private $cntr = [];
    private $measures = [];

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
        $sMessageType = 'COPINO',
        $sMessageVersionNumber = 'D',
        $sMessageReleaseNumber = '95B',
        $sMessageControllingAgencyCoded = 'UN',
        $sAssociationAssignedCode = 'ITG13'
    ) {
        parent::__construct($sMessageType, $sMessageVersionNumber, $sMessageReleaseNumber,
            $sMessageControllingAgencyCoded, $sMessageReferenceNumber, $sAssociationAssignedCode);
    }

    /**
     * @param $sender
     * @param $receiver
     * @return $this
     */
    public function setSenderAndReceiver($sender, $receiver)
    {
        $this->sender = ['NAD', 'MS', $sender];
        $this->receiver = ['NAD', 'MR', $receiver];

        return $this;
    }

    /**
     * trucker
     * @param $transRef
     * @param $modeOfTransport
     * @param $meansOfTransport
     * @param $carrierName
     * @param $plate
     * @param $driver
     * @return \EDI\Generator\Copino
     */
    public function setTransporter($transRef, $modeOfTransport, $meansOfTransport, $carrierName, $plate, $driver)
    {
        $this->transporter = self::tdtSegment(1, $transRef, $modeOfTransport, $meansOfTransport, $carrierName, '', '', [$plate, 146, '', $driver]);

        return $this;
    }

    /**
     * vessel
     * @param $carrierName
     * @param $callsign
     * @param $vesselName
     * @return \EDI\Generator\Copino
     */

    public function setVessel($carrierName, $callsign, $vesselName)
    {
        $this->vessel = self::tdtSegment(20, '', 1, 13, $carrierName, '', '', [$callsign, 103, '', $vesselName]);

        return $this;
    }

    /**
     *$type = 7 (actual date time), 132 (estimated date time)
     * @param $dtm
     * @return \EDI\Generator\Copino
     */
    public function setDTM($dtm)
    {
        $this->dtm = self::dtmSegment(132, $dtm);

        return $this;
    }

    /**
     * $size = 22G1, 42G1, ecc
     * 2 = export, 5 = full
     * @param $number
     * @param $size
     * @param $booking
     * @param $sequence
     * @return \EDI\Generator\Copino
     */
    public function setContainer($number, $size, $booking, $sequence)
    {
        $cntr = [];
        $cntr[] = ['SGP', $number];
        $cntr[] = self::eqdSegment('CN', $number, [$size, '102', '5'], '', 2, 5);
        $cntr[] = self::rffSegment('BN', $booking);
        $cntr[] = self::rffSegment('SQ', $sequence);
        $this->cntr = $cntr;

        return $this;
    }

    /**
     * $weightMode = DE 6313
     * $weight = free text
     * $unit = KGM or LBS
     * @param $weightMode
     * @param $weight
     * @param string $unit
     * @return \EDI\Generator\Copino
     */
    public function setMeasures($weightMode, $weight, $unit = 'KGM')
    {
        $this->measures = ['MEA', 'AAE', $weightMode, [$unit, $weight]];

        return $this;
    }

    /**
     * $type = 88 (place of receipt)
     * @param $locode
     * @param $terminal
     * @return \EDI\Generator\Copino
     */
    public function setPort($locode, $terminal)
    {
        $this->port = self::locSegment(88, [$locode, 139, 6], [$terminal, 72, 306]);

        return $this;
    }

    /**
     * $type = 7 (place of delivery)
     * @param $locode
     * @return \EDI\Generator\Copino
     */
    public function setDestination($locode)
    {
        $this->destination = self::locSegment(7, [$locode, 139, 6]);

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
    public function compose(?string $sMessageFunctionCode = "9", ?string $sDocumentNameCode = "661", ?string $sDocumentIdentifier = null): parent
    {
        $this->messageContent = [
            ['BGM', $sDocumentNameCode, $this->messageID, $sMessageFunctionCode],
            self::rffSegment('XXX', 1),
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

        return parent::compose($sMessageFunctionCode, $sDocumentNameCode, $sDocumentIdentifier);
    }
}
