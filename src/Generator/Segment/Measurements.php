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
    protected $sMeasurementPurposeCodeQualifier = '';
    protected $aMeasurementDetails = [];
    protected $aValueRange = [];
    protected $sSurfaceOrLayerCode = '';

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
     * Set Measurement Details.
     *
     * @param string $sMeasuredAttributeCode (6313)
     * @param string $sMeasurementSignificanceCode (6321)
     * @param string $sNonDiscreteMeasurementNameCode (6155)
     * @param string $sNonDiscreteMeasurementName (6154)
     *
     * @return self $this
     */
    public function setMeasurementDetails(
        string $sMeasuredAttributeCode = '',
        string $sMeasurementSignificanceCode = '',
        string $sNonDiscreteMeasurementNameCode = '',
        string $sNonDiscreteMeasurementName = ''
    ) {
        $aMeasurementDetails = [];

        // Measured Attribute Code
        $aMeasurementDetails[] = $sMeasuredAttributeCode;

        // Measurement Significance code
        $aMeasurementDetails[] = $sMeasurementSignificanceCode;

        // Non-discrete Measurement Name Code
        $aMeasurementDetails[] = $sNonDiscreteMeasurementNameCode;

        // Non-discrete Measurement Name
        $aMeasurementDetails[] = $sNonDiscreteMeasurementName;

        $this->aMeasurementDetails = $aMeasurementDetails;

        return $this;
    }

    /**
     * Set Value Range.
     *
     * @param string $sMeasurementUnitCode
     * @param string $sMeasure
     * @param string $sRangeMinimumQuantity
     * @param string $sRangeMaximumQuantity
     * @param string $sSignificantDigitsQuantity
     *
     * @return self $this
     */
    public function setValueRange(
        string $sMeasurementUnitCode = '',
        string $sMeasure = '',
        string $sRangeMinimumQuantity = '',
        string $sRangeMaximumQuantity = '',
        string $sSignificantDigitsQuantity = ''
    ) {
        $aValueRange = [];

        // Measurement Unit Code
        $aValueRange[] = $sMeasurementUnitCode;

        // Measure
        $aValueRange[] = $sMeasure;

        // Range Minimum Quantity
        $aValueRange[] = $sRangeMinimumQuantity;

        // Range Maximum Quantity
        $aValueRange[] = $sRangeMaximumQuantity;

        // Significant Digits Quantity
        $aValueRange[] = $sSignificantDigitsQuantity;

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
        $aComposed = ['MEA'];

        // Measurement Purpose Code Qualifier
        $aComposed[] = $this->sMeasurementPurposeCodeQualifier;

        // Measurement Details
        $aComposed[] = $this->aMeasurementDetails;

        // Value / Range
        $aComposed[] = $this->aValueRange;

        // Surface Or Layer Code
        $aComposed[] = $this->sSurfaceOrLayerCode;

        $this->setComposed($aComposed);

        return $this;
    }
}
