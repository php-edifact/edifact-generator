<?php
namespace EDI\Generator\Coprar;

class Container
{

    private $cntr;
    private $bkg;
    private $pod;
    private $fnd;
    private $weight;
    private $dangerous;
    private $temperature;
    private $dimensions;

    public function __construct()
    {

    }

    /*
     * $size = 22G1, 42G1, etc; 306 = smdg, 6436 = ISO spec
     * $statusCode = 1 (Continental), 2 (Export), 3 (Import)
     * $fullEmptyIndicator = 4 (Empty), 5 (Full)
     */
    public function setContainer($number, $size, $statusCode, $fullEmptyIndicator)
    {
        $this->cntr = \EDI\Generator\Message::eqdSegment('CN', $number, [$size, '102', '5'], '', $statusCode, $fullEmptyIndicator);
        return $this;
    }

    /*
     *
     */
    public function setBooking($booking, $sequence = null)
    {
        $this->bkg = \EDI\Generator\Message::rffSegment('BN', $booking);
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
     * Port of Discharge
     *
     */
    public function setPOD($loc)
    {
        $this->pod = \EDI\Generator\Message::locSegment(11, [$loc, 139, 6]);
        return $this;
    }

    /*
     * Final destination
     *
     */
    public function setFND($loc)
    {
        $this->fnd = \EDI\Generator\Message::locSegment(7, [$loc, 139, 6]);
        return $this;
    }

    /*
     * VGM information
     *
     */
    public function setVGM($weight, $weightTime)
    {
        $this->weight = ['MEA', 'AAE', 'VGM', ['KGM', $weight]];
        $this->weightTime = \EDI\Generator\Message::dtmSegment(798, $weightTime);
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

    public function compose()
    {
        $composed = [$this->cntr];
        if ($this->bkg !== null) {
            $composed[] = $this->bkg;
        }
        if ($this->weightTime !== null) {
            $composed[] = $this->weightTime;
        }
        $composed[] = $this->pod;
        $composed[] = $this->fnd;
        $composed[] = $this->weight;
        if ($this->dimensions !== null) {
            foreach ($this->dimensions as $segment) {
                $composed[] = $segment;
            }
        }
        if ($this->temperature !== null) {
            $composed[] = $this->temperature;
        }
        $composed[] = $this->cargo;
        if ($this->dangerous !== null) {
            $composed[] = $this->dangerous;
        }
        return $composed;
    }
}
