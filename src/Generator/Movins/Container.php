<?php

namespace EDI\Generator\Movins;

/**
 * Class Container
 * @package EDI\Generator\Movins
 */
class Container
{
    private $bayPosition;
    private $weight;
    private $freeText;
    private $cntr;
    private $carrier;
    private $pol;
    private $pod;
    private $fl;
    private $fnd;
    private $dsi;
    private $ventilation;
    private $humidity;
    private $dangerous;
    private $temperature;
    private $range;
    private $dimensions;
    private $attachedEquipment;

    public function __construct()
    {
    }

    /**
     * $type = 147
     * @param $position
     * @return \EDI\Generator\Movins\Container
     */
    public function setBayPosition($position)
    {
        $this->bayPosition = \EDI\Generator\Message::locSegment(147, $position);

        return $this;
    }

    /**
     * Weight information
     * @param $weight
     * @return \EDI\Generator\Movins\Container
     */
    public function addWeight($weight, $qualifier = "WT")
    {
        if ($this->weight === null) {
            $this->weight = [];
        }

        $this->weight[] = ['MEA', $qualifier, '', ['KGM', $weight]];

        return $this;
    }

    /**
     * Weight information
     * @param $ftx
     * @return \EDI\Generator\Movins\Container
     */
    public function addFreeText($freeText)
    {
        if ($this->freeText === null) {
            $this->freeText = [];
        }

        $this->freeText[] = ['FTX', 'AAA', '', '', $freeText];

        return $this;
    }

    /**
     * $size = 22G1, 42G1, etc; 306 = smdg, 6436 = ISO spec
     * $statusCode = 1 (Continental), 2 (Export), 3 (Import)
     * $fullEmptyIndicator = 4 (Empty), 5 (Full)
     * @param $number
     * @param $size
     * @param $statusCode
     * @param $fullEmptyIndicator
     * @return \EDI\Generator\Movins\Container
     */
    public function setContainer($number, $size, $statusCode, $fullEmptyIndicator)
    {
        $this->cntr = \EDI\Generator\Message::eqdSegment('CN', $number, [$size, '6346', '306'], '', $statusCode, $fullEmptyIndicator);

        return $this;
    }

    /**
     * $line: Master Liner Codes List
     * @param $line
     * @return \EDI\Generator\Movins\Container
     */
    public function setCarrier($line)
    {
        $this->carrier = ['NAD', 'CA', $line];

        return $this;
    }

    /**
     * $type = 9 (place of load)
     * @param $locode
     * @return \EDI\Generator\Movins\Container
     */
    public function setPOL($locode)
    {
        $this->pol = \EDI\Generator\Message::locSegment(9, $locode);

        return $this;
    }

    /**
     * $type = 11 (place of unload)
     * @param $locode
     * @return \EDI\Generator\Movins\Container
     */
    public function setPOD($locode)
    {
        $this->pod = \EDI\Generator\Message::locSegment(11, $locode);

        return $this;
    }

    /**
     * $type = 76 (place of first load)
     * @param $locode
     * @return \EDI\Generator\Movins\Container
     */
    public function setFL($locode)
    {
        $this->fl = \EDI\Generator\Message::locSegment(76, $locode);

        return $this;
    }

    /**
     * $type = 83 (place of final delivery)
     * @param $locode
     * @return \EDI\Generator\Movins\Container
     */
    public function setFND($locode)
    {
        $this->fnd = \EDI\Generator\Message::locSegment(83, $locode);

        return $this;
    }

    /**
     * $type = ISO stowage location
     * @param $position
     * @return \EDI\Generator\Movins\Container
     */
    public function setDestinationStowageISO($position)
    {
        $this->dsi = \EDI\Generator\Message::rffSegment('DSI', $position);

        return $this;
    }

    /**
     * @param $hazardClass
     * @param $hazardCode
     * @param $flashpoint
     * @param $packingGroup
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
     * @param $min
     * @param $max
     * @return $this
     */
    public function setRange($min, $max)
    {
        $this->range = ['RNG', '4', ['CEL', $min, $max]];

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

    public function addAttachedEquipment($eqpType, $eqpNumber)
    {
        if ($this->attachedEquipment === null) {
            $this->attachedEquipment = [];
        }

        $eqp = ['EQA', $eqpType, $eqpNumber];

        $this->attachedEquipment[] = $eqp;

        return $this;
    }

    /**
     * @return array
     */
    public function compose()
    {
        $composed = [
            $this->bayPosition
        ];

        if ($this->freeText !== null) {
            foreach ($this->freeText as $segment) {
                $composed[] = $segment;
            }
        }

        if ($this->weight !== null) {
            foreach ($this->weight as $segment) {
                $composed[] = $segment;
            }
        }

        if ($this->ventilation !== null) {
            $composed[] = $this->ventilation;
        }

        if ($this->humidity !== null) {
            $composed[] = $this->humidity;
        }

        if ($this->dimensions !== null) {
            foreach ($this->dimensions as $segment) {
                $composed[] = $segment;
            }
        }

        if ($this->temperature !== null) {
            $composed[] = $this->temperature;
        }

        if ($this->range !== null) {
            $composed[] = $this->range;
        }

        if ($this->pol !== null) {
            $composed[] = $this->pol;
        }
        if ($this->pod !== null) {
            $composed[] = $this->pod;
        }
        if ($this->fl !== null) {
            $composed[] = $this->fl;
        }
        if ($this->fnd !== null) {
            $composed[] = $this->fnd;
        }

        $composed[] = \EDI\Generator\Message::rffSegment('BM', '1');

        if ($this->dsi !== null) {
            $composed[] = $this->dsi;
        }

        if ($this->cntr !== null) {
            $composed[] = $this->cntr;
        }

        if ($this->attachedEquipment !== null) {
            foreach ($this->attachedEquipment as $segment) {
                $composed[] = $segment;
            }
        }

        if ($this->carrier !== null) {
            $composed[] = $this->carrier;
        }

        if ($this->dangerous !== null) {
            foreach ($this->dangerous as $segment) {
                $composed[] = $segment;
            }
        }

        return $composed;
    }
}
