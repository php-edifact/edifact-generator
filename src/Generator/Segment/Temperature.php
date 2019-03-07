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
    const SEGMENT_NAME = 'TMP';

    protected $sTemperatureTypeCodeQualifier;
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
     * Set Temperature Setting (C239).
     *
     * @param mixed $sTemperatureDegree   (6246)
     * @param mixed $sMeasurementUnitCode (6411)
     *
     * @return self $this
     */
    public function setTemperatureSetting(
        ?string $sTemperatureDegree = null,
        ?string $sMeasurementUnitCode = null
    ) {
        $aTemperatureSetting = [];

        // Temperature Degree

        if ($sTemperatureDegree !== null) {
            $aTemperatureSetting[] = $sTemperatureDegree;
        }

        // Measurement Unit Code

        if ($sMeasurementUnitCode !== null) {
            $aTemperatureSetting[] = $sMeasurementUnitCode;
        }

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
        $aComposed[] = self::SEGMENT_NAME;

        // Temperature Type Code Qualifier

        $aComposed[] = $this->sTemperatureTypeCodeQualifier;

        // Temperature Setting

        if (count($this->aTemperatureSetting) > 0) {
            $aComposed[] = $this->aTemperatureSetting;
        }

        $this->setComposed($aComposed);

        return $this;
    }
}
