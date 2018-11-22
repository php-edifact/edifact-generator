<?php
namespace EDI\Generator\Cohaor;

/**
 * Date Time Period.
 *
 * @see https://service.unece.org/trade/untdid/d17b/trsd/trsddtm.htm
 */
class DateTimePeriod
{
    private $sDateOrTimeOrPeriodFunctionCodeQualifier = '';
    private $sDateOrTimeOrPeriodText = '';
    private $sDateOrTimeOrPeriodFormatCode = '';

    /**
     * Set Date Or Time Or Period Function Code Qualifier.
     *
     * @param string $sDateOrTimeOrPeriodFunctionCodeQualifier (2005)
     *
     * @return self $this
     */
    public function setDateOrTimeOrPeriodFunctionCodeQualifier(string $sDateOrTimeOrPeriodFunctionCodeQualifier) : self
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
    public function setDateOrTimeOrPeriodText(string $sDateOrTimeOrPeriodText) : self
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
    public function setDateOrTimeOrPeriodFormatCode(string $sDateOrTimeOrPeriodFormatCode) : self
    {
        $this->sDateOrTimeOrPeriodFormatCode = $sDateOrTimeOrPeriodFormatCode;
        return $this;
    }

    /**
     * Compose.
     *
     * @return array $aComposed
     */
    public function compose() : array
    {
        $aComposed = ['EQD'];

        // Date Or Time Or Period Function Code Qualifier
        $aComposed[] = $this->sDateOrTimeOrPeriodFunctionCodeQualifier;

        // Date Or Time Or Period Text
        $aComposed[] = $this->sDateOrTimeOrPeriodText;

        // Date Or Time Or Period Format Code
        $aComposed[] = $this->sDateOrTimeOrPeriodFormatCode;

        return $aComposed;
    }
}
