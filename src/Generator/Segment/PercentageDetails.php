<?php

namespace EDI\Generator\Segment;

use EDI\Generator\Segment;

/**
 * Percentage Details.

 * @see https://service.unece.org/trade/untdid/d17b/trsd/trsdpcd.htm
 */
class PercentageDetails extends Segment
{
    const SEGMENT_NAME = 'PCD';

    protected $aPercentageDetails = [];
    protected $sStatusDescriptionCode;

    /**
     * Set Percentage Details (C501).
     *
     * @param mixed $sPercentageTypeCodeQualifier       (5245)
     * @param mixed $sPercentage                        (5482)
     * @param mixed $sPercentageBasisIdentificationCode (5249)
     * @param mixed $sCodeListIdentificationCode        (1131)
     * @param mixed $sCodeListResponsibleAgencyCode     (3055)
     *
     * @return self $this
     */
    public function setPercentageDetails(
        ?string $sPercentageTypeCodeQualifier = null,
        ?string $sPercentage = null,
        ?string $sPercentageBasisIdentificationCode = null,
        ?string $sCodeListIdentificationCode = null,
        ?string $sCodeListResponsibleAgencyCode = null
    ) {
        $aPercentageDetails = [];

        // Percentage Type Code Qualifier

        if ($sPercentageTypeCodeQualifier !== null) {
            $aPercentageDetails[] = $sPercentageTypeCodeQualifier;
        }

        // Percentage

        if ($sPercentage !== null) {
            $aPercentageDetails[] = $sPercentage;
        }

        // Percentage Basis Identification Code

        if ($sPercentageBasisIdentificationCode !== null) {
            $aPercentageDetails[] = $sPercentageBasisIdentificationCode;
        }

        // Code List Identification Code

        if ($sCodeListIdentificationCode !== null) {
            $aPercentageDetails[] = $sCodeListIdentificationCode;
        }

        // Code List Responsible Agency Code

        if ($sCodeListResponsibleAgencyCode !== null) {
            $aPercentageDetails[] = $sCodeListResponsibleAgencyCode;
        }

        $this->aPercentageDetails = $aPercentageDetails;

        return $this;
    }

    /**
     * Set Status Description Code
     *
     * @param mixed $sStatusDescriptionCode (4405)
     *
     * @return self $this
     */
    public function setStatusDescriptionCode($sStatusDescriptionCode)
    {
        $this->sStatusDescriptionCode = $sStatusDescriptionCode;

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

        // Percentage Details
        $aComposed[] = $this->aPercentageDetails;

        // Status Description Code

        if ($this->sStatusDescriptionCode !== null) {
            $aComposed[] = $this->sStatusDescriptionCode;
        }

        $this->setComposed($aComposed);

        return $this;
    }
}
