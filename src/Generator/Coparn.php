<?php

namespace EDI\Generator;

/**
 * Class Coparn
 * @package EDI\Generator
 */
class Coparn extends Message
{
    private $dtmSend;
    private $messageSender;
    private $messageReceiver;
    private $vessel;
    private $eta;
    private $etd;
    private $callsign;
    private $booking;
    private $bookingSequence;
    private $rffAcceptOrder;
    private $pol;
    private $pod;
    private $fnd;
    private $messageCF;
    private $cntr;
    private $cntrAmount;
    private $weight;
    private $tare;
    private $ventilation;
    private $humidity;
    private $weightTime;
    private $dangerous;
    private $temperature;
    private $cargo;
    private $dimensions;

    private $containers = [];

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
        $sMessageType = 'COPARN',
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
     * $line: Master Liner Codes List
     * @param $line
     * @return \EDI\Generator\Coparn
     */
    public function setCarrier($line)
    {
        $this->messageSender = ['NAD', 'MS', [$line, 160, 'ZZZ']];
        $this->messageCF = ['NAD', 'CF', [$line, 160, 166]];

        return $this;
    }

    /**
     * Date of the message submission
     * @param $dtm
     * @return \EDI\Generator\Coparn
     */
    public function setDTMMessageSendingTime($dtm)
    {
        $this->dtmSend = self::dtmSegment(137, $dtm);

        return $this;
    }

    /**
     * Date of the message submission
     * @param $booking
     * @param $sequence
     * @return \EDI\Generator\Coparn
     */
    public function setBooking($booking, $sequence = null)
    {
        $this->booking = self::rffSegment('BN', $booking);
        if ($sequence !== null) {
            $this->bookingSequence = self::rffSegment('SQ', $sequence);
        }

        return $this;
    }

    /**
     * Date of the message submission
     * @param $atx
     * @return \EDI\Generator\Coparn
     */
    public function setRFFOrder($atx)
    {
        $this->rffAcceptOrder = self::rffSegment('ATX', $atx);

        return $this;
    }

    /**
     * Vessel call information
     * @param $extVoyage
     * @param $line
     * @param $vslName
     * @param $callsign
     * @return \EDI\Generator\Coparn
     */
    public function setVessel($extVoyage, $line, $vslName, $callsign)
    {
        $this->vessel = self::tdtSegment(20, $extVoyage, '', '', [$line, 172, 20], '', '', [$callsign, 103, 11, $vslName]);
        $this->callsign = self::rffSegment('VM', $callsign);

        return $this;
    }

    /**
     * Estimated Time of Arrival
     * @param $dtm
     * @return \EDI\Generator\Coparn
     */
    public function setETA($dtm)
    {
        $this->eta = self::dtmSegment(132, $dtm);

        return $this;
    }

    /**
     * Estimated Time of Departure
     * @param $dtm
     * @return \EDI\Generator\Coparn
     */
    public function setETD($dtm)
    {
        $this->etd = self::dtmSegment(133, $dtm);

        return $this;
    }

    /**
     * Port of Loading
     * @param $loc
     * @return \EDI\Generator\Coparn
     */
    public function setPOL($loc)
    {
        $this->pol = self::locSegment(9, [$loc, 139, 6]);

        return $this;
    }

    /**
     * Port of Discharge
     * @param $loc
     * @return \EDI\Generator\Coparn
     */
    public function setPOD($loc)
    {
        $this->pod = self::locSegment(11, [$loc, 139, 6]);

        return $this;
    }

    /**
     * Final destination
     * @param $loc
     * @return \EDI\Generator\Coparn
     */
    public function setFND($loc)
    {
        $this->fnd = self::locSegment(7, [$loc, 139, 6]);

        return $this;
    }

    /**
     * $size = 22G1, 42G1, etc
     * @param $number
     * @param $size
     * @param $statusCode
     * @param $fullEmptyIndicator
     * @return \EDI\Generator\Coparn
     */
    public function setContainer($number, $size, $statusCode = 2, $fullEmptyIndicator = 5)
    {
        $this->cntr = self::eqdSegment('CN', $number, [$size, '102', '5'], '', $statusCode, $fullEmptyIndicator);

        return $this;
    }

    /**
     * How many containers need to be released
     * @param $total
     * @return \EDI\Generator\Coparn
     */
    public function setEquipmentQuantity($total)
    {
        $this->cntrAmount = ['EQN', $total];

        return $this;
    }

    /**
     * VGM information
     * @param $weight
     * @param $weightTime
     * @return \EDI\Generator\Coparn
     */
    public function setVGM($weight, $weightTime)
    {
        $this->weight = ['MEA', 'AAE', 'VGM', ['KGM', $weight]];
        $this->weightTime = self::dtmSegment(798, $weightTime);

        return $this;
    }

    /**
     * Weight information
     * @param $weight
     * @return \EDI\Generator\Coparn
     */
    public function setGrossWeight($weight)
    {
        $this->weight = ['MEA', 'AAE', 'G', ['KGM', $weight]];

        return $this;
    }

    /**
     * Weight information
     * $type = T (tare), AET (gross weight)
     * @param $weight
     * @return \EDI\Generator\Coparn
     */
    public function setTare($weight)
    {
        $this->tare = ['MEA', 'AAE', 'T', ['KGM', $weight]];

        return $this;
    }

    /**
     * Cargo category
     * @param $text
     * @return \EDI\Generator\Coparn
     */
    public function setCargoCategory($text)
    {
        $this->cargo = ['FTX', 'AAA', '', '', $text];

        return $this;
    }

    /**
     * DEPRECATED
     * @param $hazardClass
     * @param $hazardCode
     * @return \EDI\Generator\Coparn
     */
    public function setDangerous($hazardClass, $hazardCode)
    {
        $this->addDangerous($hazardClass, $hazardCode);

        return $this;
    }

    /**
     * @param $hazardClass
     * @param $hazardCode
     *@param $flashpoint
     *@param $packingGroup
     * @return $this
     */
    public function addDangerous($hazardClass, $hazardCode, $flashpoint = null, $packingGroup = null)
    {
        if ($this->dangerous === null) {
            $this->dangerous = [];
        }

        $dgs = ['DGS', 'IMD', $hazardClass, $hazardCode];
        if ($flashpoint !== null) {
            if ($flashpoint != '') {
                $flashpoint = [$flashpoint, 'CEL'];
            }
            $dgs[] = $flashpoint;
            if ($packingGroup !== null) {
                $dgs[] = $packingGroup;
            }
        }

        $this->dangerous[] = $dgs;

        return $this;
    }

    /**
     * @param $setDegrees
     * @return $this
     */
    public function setTemperature($setDegrees)
    {
        $this->temperature = ['TMP', '2', [$setDegrees, 'CEL']];

        return $this;
    }

    /**
     * @param $ventilation
     * @return $this
     */
    public function setVentilation($ventilation)
    {
        $this->ventilation = ['MEA', 'AAE', 'AAS', ['CBM', $ventilation]];

        return $this;
    }

    /**
     * @param $humidity
     * @return $this
     */
    public function setHumidity($humidity)
    {
        $this->humidity = ['MEA', 'AAE', 'AAO', ['PCT', $humidity]];

        return $this;
    }

    /**
     * @param string $front
     * @param string $back
     * @param string $right
     * @param string $left
     * @param string $height
     * @return $this
     */
    public function setOverDimensions($front = '', $back = '', $right = '', $left = '', $height = '')
    {
        $this->dimensions = [];
        if ($front !== '') {
            $this->dimensions[] = ['DIM', '5', ['CMT', $front]];
        }
        if ($back !== '') {
            $this->dimensions[] = ['DIM', '6', ['CMT', $back]];
        }
        if ($right !== '') {
            $this->dimensions[] = ['DIM', '7', ['CMT', '', $right]];
        }
        if ($left !== '') {
            $this->dimensions[] = ['DIM', '8', ['CMT', '', $left]];
        }
        if ($height !== '') {
            $this->dimensions[] = ['DIM', '13', ['CMT', '', '', $height]];
        }

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
    public function compose(?string $sMessageFunctionCode = "5", ?string $sDocumentNameCode = "126", ?string $sDocumentIdentifier = null): parent
    {
        $this->messageContent = [
            ['BGM', $sDocumentNameCode, $this->messageID, $sMessageFunctionCode, 'AB'],
        ];

        $this->messageContent[] = $this->dtmSend;

        if ($this->rffAcceptOrder !== null) {
            $this->messageContent[] = $this->rffAcceptOrder;
        }

        $this->messageContent[] = $this->booking;
        if ($this->vessel !== null) {
            $this->messageContent[] = $this->vessel;
            $this->messageContent[] = $this->callsign;
        }
        $this->messageContent[] = $this->pol;
        if ($this->eta !== null) {
            $this->messageContent[] = $this->eta;
            $this->messageContent[] = $this->etd;
        }
        $this->messageContent[] = $this->messageSender;
        $this->messageContent[] = $this->messageCF;
        $this->messageContent[] = $this->cntr;
        if ($this->bookingSequence !== null) {
            $this->messageContent[] = $this->bookingSequence;
        }

        if ($this->cntr === '') {
            $this->messageContent[] = $this->cntrAmount;
        }

        $this->messageContent[] = ['TMD', '3'];

        if ($this->weightTime !== null) {
            $this->messageContent[] = $this->weightTime;
        }

        if ($this->fnd !== null) {
            $this->messageContent[] = $this->fnd;
        }
        $this->messageContent[] = $this->pol;
        if ($this->pod !== null) {
            $this->messageContent[] = $this->pod;
        }
        $this->messageContent[] = $this->weight;
        if ($this->tare !== null) {
            $this->messageContent[] = $this->tare;
        }

        if ($this->ventilation !== null) {
            $this->messageContent[] = $this->ventilation;
        }

        if ($this->humidity !== null) {
            $this->messageContent[] = $this->humidity;
        }

        if ($this->dimensions !== null) {
            foreach ($this->dimensions as $segment) {
                $this->messageContent[] = $segment;
            }
        }

        if ($this->temperature !== null) {
            $this->messageContent[] = $this->temperature;
        }

        if ($this->cargo !== null) {
            $this->messageContent[] = $this->cargo;
        }

        if ($this->dangerous !== null) {
            foreach ($this->dangerous as $segment) {
                $this->messageContent[] = $segment;
            }
        }

        $this->messageContent[] = ['TDT', 1, '', 3];
        $this->messageContent[] = ['CNT', [16, 1]];

        return parent::compose();
    }
}
