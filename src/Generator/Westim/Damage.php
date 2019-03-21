<?php

namespace EDI\Generator\Westim;

/**
 * Class Damage
 * @package EDI\Generator\Westim
 */
class Damage
{
    private $_damage;
    private $_work;
    private $_cost;

    public function __construct()
    {
    }

    /**
     * $line \d{2}
     * @param $line
     * @param $damageLocationCode
     * @param $componentCode
     * @param $damageTypeCode
     * @param $componentMaterialCode
     * @return \EDI\Generator\Westim\Damage
     */
    public function setDamage($line, $damageLocationCode, $componentCode, $damageTypeCode, $componentMaterialCode)
    {
        $this->_damage = ['DAM', $line, $damageLocationCode, $componentCode, $damageTypeCode, $componentMaterialCode];

        return $this;
    }

    /**
     * @param $repairMethodCode
     * @param $measureUnit
     * @param $length
     * @param $width
     * @param $height
     * @param $quantity
     * @return \EDI\Generator\Westim\Damage
     */
    public function setWork($repairMethodCode, $measureUnit, $length, $width, $height, $quantity)
    {
        $this->_work = ['WOR', $repairMethodCode, [$measureUnit, $length, $width, $height], $quantity];

        return $this;
    }

    /**
     * @param $manHours
     * @param $materialCost
     * @param $responsibility
     * @param $labourRate
     * @return \EDI\Generator\Westim\Damage
     */
    public function setCost($manHours, $materialCost, $responsibility, $labourRate)
    {
        $this->_cost = ['COS', 00, $manHours, $materialCost, $responsibility, $labourRate, 'N'];

        return $this;
    }

    /**
     * @return array
     */
    public function compose()
    {
        $composed = [];
        $composed[] = $this->_damage;
        $composed[] = $this->_work;
        $composed[] = $this->_cost;

        return $composed;
    }
}
