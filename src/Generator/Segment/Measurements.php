<?php

namespace EDI\Generator\Segment;

use EDI\Generator\Segment;

/**
 * Measurements.
 *
 * @see https://service.unece.org/trade/untdid/d17b/trsd/trsdmea.htm
 */
class Measurements extends Segment
{
    const SEGMENT_NAME = 'MEA';

    protected $sMeasurementPurposeCodeQualifier;
    protected $aMeasurementDetails = [];
    protected $aValueRange = [];
    protected $sSurfaceOrLayerCode;

    /**
     * Set Measurement Purpose Code Qualifier.
     *
     * @param string $sMeasurementPurposeCodeQualifier (6311)
     *
     * @return self $this
     */
    public function setMeasurementPurposeCodeQualifier(string $sMeasurementPurposeCodeQualifier): self
    {
        $this->sMeasurementPurposeCodeQualifier = $sMeasurementPurposeCodeQualifier;

        return $this;
    }

    /**
     * Set Measurement Details (C502).
     *
     * @param mixed $sMeasuredAttributeCode          (6313)
     * @param mixed $sMeasurementSignificanceCode    (6321)
     * @param mixed $sNonDiscreteMeasurementNameCode (6155)
     * @param mixed $sNonDiscreteMeasurementName     (6154)
     *
     * @return self $this
     */
    public function setMeasurementDetails(
        ?string $sMeasuredAttributeCode = null,
        ?string $sMeasurementSignificanceCode = null,
        ?string $sNonDiscreteMeasurementNameCode = null,
        ?string $sNonDiscreteMeasurementName = null
    ) {
        $aMeasurementDetails = [];

        // Measured Attribute Code

        if ($sMeasuredAttributeCode !== null) {
            $aMeasurementDetails[] = $sMeasuredAttributeCode;
        }

        // Measurement Significance code

        if ($sMeasurementSignificanceCode !== null) {
            $aMeasurementDetails[] = $sMeasurementSignificanceCode;
        }

        // Non-discrete Measurement Name Code

        if ($sNonDiscreteMeasurementNameCode !== null) {
            $aMeasurementDetails[] = $sNonDiscreteMeasurementNameCode;
        }

        // Non-discrete Measurement Name

        if ($sNonDiscreteMeasurementName !== null) {
            $aMeasurementDetails[] = $sNonDiscreteMeasurementName;
        }

        $this->aMeasurementDetails = $aMeasurementDetails;

        return $this;
    }

    /**
     * Set Value Range (C174).
     *
     * @param mixed $sMeasurementUnitCode
     * @param mixed $sMeasure
     * @param mixed $sRangeMinimumQuantity
     * @param mixed $sRangeMaximumQuantity
     * @param mixed $sSignificantDigitsQuantity
     *
     * @return self $this
     */
    public function setValueRange(
        ?string $sMeasurementUnitCode = null,
        ?string $sMeasure = null,
        ?string $sRangeMinimumQuantity = null,
        ?string $sRangeMaximumQuantity = null,
        ?string $sSignificantDigitsQuantity = null
    ) {
        $aValueRange = [];

        // Measurement Unit Code

        if ($sMeasurementUnitCode !== null) {
            $aValueRange[] = $sMeasurementUnitCode;
        }

        // Measure

        if ($sMeasure !== null) {
            $aValueRange[] = $sMeasure;
        }

        // Range Minimum Quantity

        if ($sRangeMinimumQuantity !== null) {
            $aValueRange[] = $sRangeMinimumQuantity;
        }

        // Range Maximum Quantity

        if ($sRangeMaximumQuantity !== null) {
            $aValueRange[] = $sRangeMaximumQuantity;
        }

        // Significant Digits Quantity

        if ($sSignificantDigitsQuantity !== null) {
            $aValueRange[] = $sSignificantDigitsQuantity;
        }

        $this->aValueRange = $aValueRange;

        return $this;
    }

    /**
     * Set Surface Or Layer Code.
     *
     * @param string $sSurfaceOrLayerCode (7383)
     *
     * @return self $this
     */
    public function setSurfaceOrLayerCode(string $sSurfaceOrLayerCode): self
    {
        $this->sSurfaceOrLayerCode = $sSurfaceOrLayerCode;

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

        // Measurement Purpose Code Qualifier
        $aComposed[] = $this->sMeasurementPurposeCodeQualifier;

        // Measurement Details

        if (count($this->aMeasurementDetails) > 0) {
            $aComposed[] = $this->aMeasurementDetails;
        }

        // Value / Range

        if (count($this->aValueRange) > 0) {
            $aComposed[] = $this->aValueRange;
        }

        // Surface Or Layer Code

        if ($this->sSurfaceOrLayerCode !== null) {
            $aComposed[] = $this->sSurfaceOrLayerCode;
        }

        $this->setComposed($aComposed);

        return $this;
    }
}
