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
    protected $sRangeTypeCodeQualifier = '';
    protected $sMeasurementUnitCode ='';
    protected $sRangeMinimumQuantity = '';
    protected $sRangeMaximumQuantity = '';

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
        $aComposed = ['RNG'];

        // Range Type Code Qualifier
        $aComposed[] = $this->sRangeTypeCodeQualifier;

        // Measurement Unit Code
        $aComposed[] = $this->sMeasurementUnitCode;

        // Range Minimum Quantity
        $aComposed[] = $this->sRangeMinimumQuantity;

        // Range Maximum Quantity
        $aComposed[] = $this->sRangeMaximumQuantity;

        $this->setComposed($aComposed);

        return $this;
    }
}
