<?php

namespace EDI\Generator\Segment;

use EDI\Generator\Segment;

/**
 * Control Total.
 *
 * @see https://service.unece.org/trade/untdid/d17b/trsd/trsdcnt.htm
 */
class ControlTotal extends Segment
{
    protected $sControlTotalTypeCodeQualifier = '';
    protected $sControlTotalQuantity = '';
    protected $sMeasurementUnitCode = '';

    /**
     * Set Control Total Type Code Qualifier.
     *
     * @param string $sControlTotalTypeCodeQualifier (6069)
     *
     * @return self $this
     */
    public function setControlTotalTypeCodeQualifier(string $sControlTotalTypeCodeQualifier): self
    {
        $this->sControlTotalTypeCodeQualifier = $sControlTotalTypeCodeQualifier;
        return $this;
    }

    /**
     * Set Control Total Quantity.
     *
     * @param string $sControlTotalQuantity (6066)
     *
     * @return self $this
     */
    public function setControlTotalQuantity(string $sControlTotalQuantity): self
    {
        $this->sControlTotalQuantity = $sControlTotalQuantity;
        return $this;
    }

    /**
     * Set Measurement Unit Code.
     *
     * @param string $sControlTotalQuantity (6411)
     *
     * @return self $this
     */
    public function setMeasurementUnitCode(string $sMeasurementUnitCode): self
    {
        $this->sMeasurementUnitCode = sMeasurementUnitCode;
        return $this;
    }

    /**
     * Compose.
     *
     * @return self $this
     */
    public function compose(): self
    {
        $aComposed = ['CNT'];

        // Control Total Type Code Qualifier
        $aComposed[] = $this->sControlTotalTypeCodeQualifier;

        // Control Total Quantity
        $aComposed[] = $this->sControlTotalQuantity;

        // Measurement Unit Code
        $aComposed[] = $this->sMeasurementUnitCode;

        $this->setComposed($aComposed);

        return $this;
    }
}
