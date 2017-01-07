<?php
namespace EDI\Generator;

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
    private $weightTime;
    private $dangerous;
    private $temperature;
    private $dimensions;

    public function __construct($identifier, $version, $release, $controllingAgency, $messageID, $association = null)
    {
        parent::__construct($identifier, $version, $release, $controllingAgency, $messageID, $association);

        $this->dtmSend = $this->dtmSegment(137, date('YmdHi'));

        $this->containers = [];
    }

    /*
     * $line: Master Liner Codes List
     */
    public function setCarrier($line)
    {
        $this->messageSender = ['NAD', 'MS', [$line, 160, 'ZZZ']];
        $this->messageCF = ['NAD', 'CF', [$line, 160, 166]];
        return $this;
    }

    /*
     * Date of the message submission
     *
     */
    public function setDTMMessageSendingTime($dtm)
    {
        $this->dtmSend = $this->dtmSegment(137, $dtm);
        return $this;
    }

    /*
     * Date of the message submission
     *
     */
    public function setBooking($booking, $seq)
    {
        $this->booking = ['RFF', 'BN', $booking];
        $this->bookingSequence = ['RFF', 'SQ', $seq];
        return $this;
    }

    /*
     * Date of the message submission
     *
     */
    public function setRFFOrder($atx)
    {
        $this->rffAcceptOrder = ['RFF', 'ATX', $atx];
        return $this;
    }

    /*
     * Vessel call information
     *
     */
    public function setVessel($extVoyage, $line, $vslName, $callsign)
    {
        $this->vessel = ['TDT', 20, $extVoyage, '', '', [$line, 172, 20], '', '', [$callsign, 146, 11, $vslName]];
        $this->callsign = ['RFF', 'VM', $callsign];
        return $this;
    }

    /*
     * Estimated Time of Arrival
     *
     */
    public function setETA($dtm)
    {
        $this->eta = $this->dtmSegment(132, $dtm);
        return $this;
    }

    /*
     * Estimated Time of Departure
     *
     */
    public function setETD($dtm)
    {
        $this->etd = $this->dtmSegment(133, $dtm);
        return $this;
    }

    /*
     * Port of Loading
     *
     */
    public function setPOL($loc)
    {
        $this->pol = ['LOC', 9, [$loc, 139, 6]];
        return $this;
    }

    /*
     * Port of Discharge
     *
     */
    public function setPOD($loc)
    {
        $this->pod = ['LOC', 11, [$loc, 139, 6]];
        return $this;
    }

    /*
     * Final destination
     *
     */
    public function setFND($loc)
    {
        $this->fnd = ['LOC', 7, [$loc, 139, 6]];
        return $this;
    }

    /*
     * $size = 22G1, 42G1, etc
     */
    public function setContainer($number, $size)
    {
        $this->cntr = ['EQD', 'CN', $number, [$size, '102', '5'], '', 2, 5];
        return $this;
    }

    /*
     * How many containers need to be released
     *
     */
    public function setEquipmentQuantity($total)
    {
        $this->cntrAmount = ['EQN', $total];
        return $this;
    }

    /*
     * VGM information
     *
     */
    public function setVGM($weight, $weightTime)
    {
        $this->weight = ['MEA', 'AAE', 'VGM', ['KGM', $weight]];
        $this->weightTime = $this->dtmSegment(798, $weightTime);
        return $this;
    }

    /*
     * Weight information
     *
     */
    public function setGrossWeight($weight)
    {
        $this->weight = ['MEA', 'AAE', 'G', ['KGM', $weight]];
        return $this;
    }

    /*
     * Cargo category
     *
     */
    public function setCargoCategory($text)
    {
        $this->cargo = ['FTX', 'AAA', '', '', $text];
        return $this;
    }

    public function setDangerous($hazardCode, $hazardClass)
    {
        $this->dangerous = ['DGS', 'IMD', $hazardCode, $hazardClass];
        return $this;
    }

    public function setTemperature($setDegrees)
    {
        $this->temperature = ['TMP', '2', [$setDegrees, 'CEL']];
        return $this;
    }

    public function setOverDimensions($front = '', $back = '', $right = '', $left = '', $height = '')
    {
        $this->dim = [];
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

    public function compose($msgStatus = 5)
    {
        $this->messageContent = [
            ['BGM', '126', $this->messageID, $msgStatus, 'AB']
        ];

        $this->messageContent[] = $this->dtmSend;
        if ($this->rffAcceptOrder !== null) {
            $this->messageContent[] = $this->rffAcceptOrder;
        }
        $this->messageContent[] = $this->booking;
        $this->messageContent[] = $this->vessel;
        $this->messageContent[] = $this->callsign;
        $this->messageContent[] = $this->pol;
        $this->messageContent[] = $this->eta;
        $this->messageContent[] = $this->etd;
        $this->messageContent[] = $this->messageSender;
        $this->messageContent[] = $this->messageCF;
        $this->messageContent[] = $this->cntr;
        $this->messageContent[] = $this->bookingSequence;
        if ($this->cntr  === '') {
            $this->messageContent[] = $this->cntrAmount;
        }
        $this->messageContent[] = ['TMD', '3'];
        if ($this->weightTime !== null) {
            $this->messageContent[] = $this->weightTime;
        }
        $this->messageContent[] = $this->fnd;
        $this->messageContent[] = $this->pol;
        $this->messageContent[] = $this->pod;
        $this->messageContent[] = $this->weight;
        if ($this->dimensions !== null) {
            foreach ($this->dimensions as $segment) {
                $this->messageContent[] = $segment;
            }
        }
        if ($this->temperature !== null) {
            $this->messageContent[] = $this->temperature;
        }
        if ($this->dangerous !== null) {
            $this->messageContent[] = $this->dangerous;
        }
        $this->messageContent[] = $this->cargo;
        $this->messageContent[] = ['TDT', 1, '', 3];
        $this->messageContent[] = ['CNT', [16, 1]];
        parent::compose();
        return $this;
    }
}
