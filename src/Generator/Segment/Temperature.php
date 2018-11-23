<?php

namespace EDI\Generator\Segment;

use EDI\Generator\Segment;

/**
 * Temperature.
 *
 * @see https://service.unece.org/trade/untdid/d17b/trsd/trsdtmp.htm
 */
class Temperature extends Segment
{
    protected $sTemperatureTypeCodeQualifier = '';
    protected $aTemperatureSetting = [];

    /**
     * Set Temperature Type Code Qualifier.
     *
     * @param string $sTemperatureTypeCodeQualifier (6245)
     *
     * @return self $this
     */
    public function setTemperatureTypeCodeQualifier(string $sTemperatureTypeCodeQualifier): self
    {
        $this->sTemperatureTypeCodeQualifier = $sTemperatureTypeCodeQualifier;
        return $this;
    }

    /**
     * Set Temperature Setting.
     *
     * @param string $sTemperatureDegree (6246)
     * @param string $sMeasurementUnitCode (6411)
     *
     * @return self $this
     */
    public function setTemperatureSetting(string $sTemperatureDegree = '', string $sMeasurementUnitCode = '')
    {
        $aTemperatureSetting = [];

        // Temperature Degree
        $aTemperatureSetting[] = $sTemperatureDegree;

        // Measurement Unit Code
        $aTemperatureSetting[] = $sMeasurementUnitCode;

        $this->aTemperatureSetting = $aTemperatureSetting;

        return $this;
    }

    /**
     * Compose.
     *
     * @return self $this
     */
    public function compose(): self
    {
        $aComposed = ['TMP'];

        // Temperature Type Code Qualifier
        $aComposed[] = $this->sTemperatureTypeCodeQualifier;

        // Temperature Settings
        $aComposed[] = $this->aTemperatureSetting;

        $this->setComposed($aComposed);

        return $this;
    }
}
