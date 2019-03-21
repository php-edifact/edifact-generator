<?php

namespace EDI\Generator\Coprar;

class Container
{
    private $cntr;
    private $bkg;
    private $pol;
    private $pod;
    private $fnd;
    private $weight;
    private $weightTime;
    private $seal;
    private $cargo;
    private $dangerous;
    private $dgsAac;
    private $temperature;
    private $dimensions;
    private $containerOperator;

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
    public function setPOL($loc)
    {
        $this->pol = \EDI\Generator\Message::locSegment(9, [$loc, 139, 6]);

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
     * $seal = free text
     * $sealIssuer = DE 9303
     */
    public function setSeal($seal, $sealIssuer)
    {
        $this->seal = ['SEL', [$seal, $sealIssuer]];

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

    /*
     * DEPRECATED
     */
    public function setDangerous($hazardClass, $hazardCode)
    {
        $this->addDangerous($hazardClass, $hazardCode);

        return $this;
    }

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

    public function setDangerousAdditionalInfo($addInfo)
    {
        $this->dgsAac = ['FTX', 'AAC', '', '', $addInfo];

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

    /*
     * $line: Master Liner Codes List
     */
    public function setContainerOperator($line)
    {
        $this->containerOperator = ['NAD', 'CF', [$line, 160, 20]];

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
        if ($this->pol !== null) {
            $composed[] = $this->pol;
        }
        if ($this->pod !== null) {
            $composed[] = $this->pod;
        }
        $composed[] = $this->fnd;
        $composed[] = $this->weight;
        if ($this->seal !== null) {
            $composed[] = $this->seal;
        }
        if ($this->dimensions !== null) {
            foreach ($this->dimensions as $segment) {
                $composed[] = $segment;
            }
        }
        if ($this->temperature !== null) {
            $composed[] = $this->temperature;
        }
        if ($this->cargo !== null) {
            $composed[] = $this->cargo;
        }
        if ($this->dangerous !== null) {
            foreach ($this->dangerous as $segment) {
                $composed[] = $segment;
            }
        }
        if ($this->dgsAac !== null) {
            $composed[] = $this->dgsAac;
        }
        $composed[] = $this->containerOperator;

        return $composed;
    }
}
