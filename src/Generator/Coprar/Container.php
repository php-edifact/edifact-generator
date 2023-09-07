<?php

namespace EDI\Generator\Coprar;

/**
 * Class Container
 * @package EDI\Generator\Coprar
 */
class Container
{
    private $cntr;
    private $bkg;
    private $pol;
    private $pod;
    private $fnd;
    private $weight;
    private $weightTime;
    private $tare;
    private $seal;
    private $cargo;
    private $specialInstructions;
    private $dangerous;
    private $dgsAac;
    private $temperature;
    private $dimensions;
    private $containerOperator;
    private $handling;

    public function __construct()
    {
    }

    /**
     * $size = 22G1, 42G1, etc; 306 = smdg, 6436 = ISO spec
     * $statusCode = 1 (Continental), 2 (Export), 3 (Import)
     * $fullEmptyIndicator = 4 (Empty), 5 (Full)
     * @param $number
     * @param $size
     * @param $statusCode
     * @param $fullEmptyIndicator
     * @return \EDI\Generator\Coprar\Container
     */
    public function setContainer($number, $size, $statusCode, $fullEmptyIndicator)
    {
        $this->cntr = \EDI\Generator\Message::eqdSegment('CN', $number, [$size, '102', '5'], '', $statusCode, $fullEmptyIndicator);

        return $this;
    }

    /**
     * @param $booking
     *@param $sequence
     * @return \EDI\Generator\Coprar\Container
     */
    public function setBooking($booking, $sequence = null)
    {
        $this->bkg = \EDI\Generator\Message::rffSegment('BN', $booking);

        return $this;
    }

    /**
     * @param $bl
     * @return \EDI\Generator\Coprar\Container
     */
    public function setBillOfLading($bl)
    {
        $this->bkg = \EDI\Generator\Message::rffSegment('BM', $bl);

        return $this;
    }

    /**
     * Port of Discharge
     * @param $loc
     * @return \EDI\Generator\Coprar\Container
     */
    public function setPOL($loc)
    {
        $this->pol = \EDI\Generator\Message::locSegment(9, [$loc, 139, 6]);

        return $this;
    }

    /**
     * Port of Discharge
     * @param $loc
     * @return \EDI\Generator\Coprar\Container
     */
    public function setPOD($loc)
    {
        $this->pod = \EDI\Generator\Message::locSegment(11, [$loc, 139, 6]);

        return $this;
    }

    /**
     * Final destination
     * @param $loc
     * @return \EDI\Generator\Coprar\Container
     */
    public function setFND($loc)
    {
        $this->fnd = \EDI\Generator\Message::locSegment(7, [$loc, 139, 6]);

        return $this;
    }

    /**
     * VGM information
     * @param $weight
     * @param $weightTime
     * @return \EDI\Generator\Coprar\Container
     */
    public function setVGM($weight, $weightTime)
    {
        $this->weight = ['MEA', 'AAE', 'VGM', ['KGM', $weight]];
        $this->weightTime = \EDI\Generator\Message::dtmSegment(798, $weightTime);

        return $this;
    }

    /**
     * Weight information
     * @param $weight
     * @return \EDI\Generator\Coprar\Container
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
     * @return \EDI\Generator\Coreor
     */
    public function setTare($weight)
    {
        $this->tare = ['MEA', 'AAE', 'T', ['KGM', $weight]];

        return $this;
    }

    /**
     * $seal = free text
     * $sealIssuer = DE 9303
     * @param $seal
     * @param $sealIssuer
     * @return \EDI\Generator\Coprar\Container
     */
    public function setSeal($seal, $sealIssuer)
    {
        $this->seal = ['SEL', [$seal, $sealIssuer]];

        return $this;
    }

    /**
     * Cargo category
     * @param $text
     * @return \EDI\Generator\Coprar\Container
     */
    public function setCargoCategory($text)
    {
        $this->cargo = ['FTX', 'AAA', '', '', $text];

        return $this;
    }

    /**
     * Special instructions
     * @param $text
     * @return \EDI\Generator\Coprar\Container
     */
    public function setSpecialInstructions($text)
    {
        $this->specialInstructions = ['FTX', 'SIN', '', '', $text];

        return $this;
    }

    /**
     * DEPRECATED
     * @param $hazardClass
     * @param $hazardCode
     * @return \EDI\Generator\Coprar\Container
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
     * @param $addInfo
     * @return $this
     */
    public function setDangerousAdditionalInfo($addInfo)
    {
        $this->dgsAac = ['FTX', 'AAC', '', '', $addInfo];

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
     * @param string $front
     * @param string $back
     * @param string $right
     * @param string $left
     * @param string $height
     * @return $this
     */
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

    /**
     * $line: Master Liner Codes List
     * @param $line
     * @return \EDI\Generator\Coprar\Container
     */
    public function setContainerOperator($line)
    {
        $this->containerOperator = ['NAD', 'CF', [$line, 160, 20]];

        return $this;
    }

    /**
     * @param $handlingCode
     * @return $this
     */
    public function addHandling($handlingCode)
    {
        if ($this->handling === null) {
            $this->handling = [];
        }

        $this->dangerous[] = ['HAN', $handlingCode];

        return $this;
    }

    /**
     * @return array
     */
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
        if ($this->tare !== null) {
            $composed[] = $this->tare;
        }
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
        if ($this->specialInstructions !== null) {
            $composed[] = $this->specialInstructions;
        }
        if ($this->dangerous !== null) {
            foreach ($this->dangerous as $segment) {
                $composed[] = $segment;
            }
        }
        if ($this->dgsAac !== null) {
            $composed[] = $this->dgsAac;
        }

        if ($this->handling !== null) {
            foreach ($this->handling as $segment) {
                $composed[] = $segment;
            }
        }

        $composed[] = $this->containerOperator;

        return $composed;
    }
}
