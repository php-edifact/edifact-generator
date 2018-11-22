<?php

namespace EDI\Generator\Cohaor;

/**
 * Control Total.
 *
 * @see https://service.unece.org/trade/untdid/d17b/trsd/trsdcnt.htm
 */
class ControlTotal
{
    private $sControlTotalTypeCodeQualifier = '';
    private $sControlTotalQuantity = '';
    private $sMeasurementUnitCode = '';

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
     * @return array $aComposed
     */
    public function compose(): array
    {
        $aComposed = ['CNT'];

        // Control Total Type Code Qualifier
        $aComposed[] = $this->sControlTotalTypeCodeQualifier;

        // Control Total Quantity
        $aComposed[] = $this->sControlTotalQuantity;

        // Measurement Unit Code
        $aComposed[] = $this->sMeasurementUnitCode;

        return $aComposed;
    }
}
