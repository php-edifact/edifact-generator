<?php

namespace EDI\Generator\Segment;

use EDI\Generator\Segment;

/**
 * Date Time Period.
 *
 * @see https://service.unece.org/trade/untdid/d17b/trsd/trsddtm.htm
 */
class DateTimePeriod extends Segment
{
    protected $sDateOrTimeOrPeriodFunctionCodeQualifier = '';
    protected $sDateOrTimeOrPeriodText = '';
    protected $sDateOrTimeOrPeriodFormatCode = '';

    /**
     * Set Date Or Time Or Period Function Code Qualifier.
     *
     * @param string $sDateOrTimeOrPeriodFunctionCodeQualifier (2005)
     *
     * @return self $this
     */
    public function setDateOrTimeOrPeriodFunctionCodeQualifier(string $sDateOrTimeOrPeriodFunctionCodeQualifier): self
    {
        $this->sDateOrTimeOrPeriodFunctionCodeQualifier = $sDateOrTimeOrPeriodFunctionCodeQualifier;
        return $this;
    }

    /**
     * Set Date Or Time Or Period Text.
     *
     * @param string $sDateOrTimeOrPeriodText (2380)
     *
     * @return self $this
     */
    public function setDateOrTimeOrPeriodText(string $sDateOrTimeOrPeriodText): self
    {
        $this->sDateOrTimeOrPeriodText = $sDateOrTimeOrPeriodText;
        return $this;
    }

    /**
     * Set Date Or Time Or Period Format Code.
     *
     * @param string $sDateOrTimeOrPeriodFormatCode (2379)
     *
     * @return self $this
     */
    public function setDateOrTimeOrPeriodFormatCode(string $sDateOrTimeOrPeriodFormatCode): self
    {
        $this->sDateOrTimeOrPeriodFormatCode = $sDateOrTimeOrPeriodFormatCode;
        return $this;
    }

    /**
     * Compose.
     *
     * @return self $this
     */
    public function compose(): self
    {
        $aComposed = ['EQD'];

        // Date Or Time Or Period Function Code Qualifier
        $aComposed[] = $this->sDateOrTimeOrPeriodFunctionCodeQualifier;

        // Date Or Time Or Period Text
        $aComposed[] = $this->sDateOrTimeOrPeriodText;

        // Date Or Time Or Period Format Code
        $aComposed[] = $this->sDateOrTimeOrPeriodFormatCode;

        $this->setComposed($aComposed);

        return $this;
    }
}
