<?php

namespace EDI\Generator\Segment;

use EDI\Generator\Segment;

/**
 * Range Details.
 *
 * @see https://service.unece.org/trade/untdid/d17b/trsd/trsdrng.htm
 */
class RangeDetails extends Segment
{
    const SEGMENT_NAME = 'RNG';

    protected $sRangeTypeCodeQualifier;
    protected $sMeasurementUnitCode;
    protected $sRangeMinimumQuantity;
    protected $sRangeMaximumQuantity;

    /**
     * Set Range Type Code Qualifier.
     *
     * @param string $sRangeTypeCodeQualifier (6167)
     *
     * @return self $this
     */
    public function setRangeTypeCodeQualifier(string $sRangeTypeCodeQualifier): self
    {
        $this->sRangeTypeCodeQualifier = $sRangeTypeCodeQualifier;

        return $this;
    }

    /**
     * Set Measurement Unit Code.
     *
     * @param string $sMeasurementUnitCode (6411)
     *
     * @return self $this
     */
    public function setMeasurementUnitCode(string $sMeasurementUnitCode): self
    {
        $this->sMeasurementUnitCode = $sMeasurementUnitCode;

        return $this;
    }

    /**
     * Set Range Minimum Quantity.
     *
     * @param string $sRangeMinimumQuantity (6162)
     *
     * @return self $this
     */
    public function setRangeMinimumQuantity(string $sRangeMinimumQuantity): self
    {
        $this->sRangeMinimumQuantity = $sRangeMinimumQuantity;

        return $this;
    }

    /**
     * Set Range Maximum Quantity.
     *
     * @param string $sRangeMaximumQuantity (6152)
     *
     * @return self $this
     */
    public function setRangeMaximumQuantity(string $sRangeMaximumQuantity): self
    {
        $this->sRangeMaximumQuantity = $sRangeMaximumQuantity;

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

        // Range Type Code Qualifier
        $aComposed[] = $this->sRangeTypeCodeQualifier;

        // Measurement Unit Code
        $aRange[] = $this->sMeasurementUnitCode;

        // Range Minimum Quantity

        if ($this->sRangeMinimumQuantity !== null) {
            $aRange[] = $this->sRangeMinimumQuantity;
        }

        // Range Maximum Quantity

        if ($this->sRangeMaximumQuantity !== null) {
            $aRange[] = $this->sRangeMaximumQuantity;
        }

        if (count($aRange) > 0) {
            $aComposed[] = $aRange;
        }

        $this->setComposed($aComposed);

        return $this;
    }
}
