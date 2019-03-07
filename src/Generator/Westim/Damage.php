<?php

namespace EDI\Generator\Westim;

class Damage
{
    private $_damage;
    private $_work;
    private $_cost;

    public function __construct()
    {
    }

    /*
     * $line \d{2}
     */
    public function setDamage($line, $damageLocationCode, $componentCode, $damageTypeCode, $componentMaterialCode)
    {
        $this->_damage = ['DAM', $line, $damageLocationCode, $componentCode, $damageTypeCode, $componentMaterialCode];

        return $this;
    }

    /*
     *
     */
    public function setWork($repairMethodCode, $measureUnit, $length, $width, $height, $quantity)
    {
        $this->_work = ['WOR', $repairMethodCode, [$measureUnit, $length, $width, $height], $quantity];

        return $this;
    }

    /*
     *
     */
    public function setCost($manHours, $materialCost, $responsibility, $labourRate)
    {
        $this->_cost = ['COS', 00, $manHours, $materialCost, $responsibility, $labourRate, 'N'];

        return $this;
    }

    public function compose()
    {
        $composed = [];
        $composed[] = $this->_damage;
        $composed[] = $this->_work;
        $composed[] = $this->_cost;

        return $composed;
    }
}
