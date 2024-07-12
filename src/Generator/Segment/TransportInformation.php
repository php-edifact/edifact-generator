<?php

namespace EDI\Generator\Segment;

use EDI\Generator\Segment;

/**
 * Transport Information.
 *
 * @see https://service.unece.org/trade/untdid/d17b/trsd/trsdtdt.htm
 */
class TransportInformation extends Segment
{
    public const SEGMENT_NAME = 'TDT';

    protected $sTransportStageCodeQualifier;
    protected $sMeansOfTransportJourneyIdentifier;
    protected $aModeOfTransport = [];
    protected $aTransportMeans = [];
    protected $aCarrier = [];
    protected $sTransitDirectionIndicatorCode;
    protected $aExcessTransportationInformation = [];
    protected $aTransportIdentification = [];
    protected $sTransportMeansOwnershipIndicatorCode;
    protected $aPowerType = [];
    protected $aTransportService = [];

    /**
     * Set Transport Stage Code Qualifier.
     *
     * @param string $sTransportStageCodeQualifier
     * @return self
     */
    public function setTransportStageCodeQualifier(string $sTransportStageCodeQualifier): self
    {
        $this->sTransportStageCodeQualifier = $sTransportStageCodeQualifier;
        return $this;
    }

    /**
     * Set Means of Transport Journey Identifier.
     *
     * @param string $sMeansOfTransportJourneyIdentifier
     * @return self
     */
    public function setMeansOfTransportJourneyIdentifier(string $sMeansOfTransportJourneyIdentifier): self
    {
        $this->sMeansOfTransportJourneyIdentifier = $sMeansOfTransportJourneyIdentifier;
        return $this;
    }

    /**
     * Set Mode of Transport (C220).
     *
     * @param string|null $sTransportModeNameCode
     * @param string|null $sTransportModeName
     * @return self
     */
    public function setModeOfTransport(?string $sTransportModeNameCode = null, ?string $sTransportModeName = null): self
    {
        $aModeOfTransport = [];
        if ($sTransportModeNameCode !== null) {
            $aModeOfTransport[] = $sTransportModeNameCode;
        }
        if ($sTransportModeName !== null) {
            $aModeOfTransport[] = $sTransportModeName;
        }
        $this->aModeOfTransport = $aModeOfTransport;
        return $this;
    }

    /**
     * Set Transport Means (C001).
     *
     * @param string|null $sTransportMeansDescriptionCode
     * @param string|null $sCodeListIdentificationCode
     * @param string|null $sCodeListResponsibleAgencyCode
     * @param string|null $sTransportMeansDescription
     * @return self
     */
    public function setTransportMeans(
        ?string $sTransportMeansDescriptionCode = null,
        ?string $sCodeListIdentificationCode = null,
        ?string $sCodeListResponsibleAgencyCode = null,
        ?string $sTransportMeansDescription = null
    ): self {
        $aTransportMeans = [];
        if ($sTransportMeansDescriptionCode !== null) {
            $aTransportMeans[] = $sTransportMeansDescriptionCode;
        }
        if ($sCodeListIdentificationCode !== null) {
            $aTransportMeans[] = $sCodeListIdentificationCode;
        }
        if ($sCodeListResponsibleAgencyCode !== null) {
            $aTransportMeans[] = $sCodeListResponsibleAgencyCode;
        }
        if ($sTransportMeansDescription !== null) {
            $aTransportMeans[] = $sTransportMeansDescription;
        }
        $this->aTransportMeans = $aTransportMeans;
        return $this;
    }

    /**
     * Set Carrier (C040).
     *
     * @param string|null $sCarrierIdentifier
     * @param string|null $sCodeListIdentificationCode
     * @param string|null $sCodeListResponsibleAgencyCode
     * @param string|null $sCarrierName
     * @return self
     */
    public function setCarrier(
        ?string $sCarrierIdentifier = null,
        ?string $sCodeListIdentificationCode = null,
        ?string $sCodeListResponsibleAgencyCode = null,
        ?string $sCarrierName = null
    ): self {
        $aCarrier = [];
        if ($sCarrierIdentifier !== null) {
            $aCarrier[] = $sCarrierIdentifier;
        }
        if ($sCodeListIdentificationCode !== null) {
            $aCarrier[] = $sCodeListIdentificationCode;
        }
        if ($sCodeListResponsibleAgencyCode !== null) {
            $aCarrier[] = $sCodeListResponsibleAgencyCode;
        }
        if ($sCarrierName !== null) {
            $aCarrier[] = $sCarrierName;
        }
        $this->aCarrier = $aCarrier;
        return $this;
    }

    /**
     * Set Transit Direction Indicator Code.
     *
     * @param string $sTransitDirectionIndicatorCode
     * @return self
     */
    public function setTransitDirectionIndicatorCode(string $sTransitDirectionIndicatorCode): self
    {
        $this->sTransitDirectionIndicatorCode = $sTransitDirectionIndicatorCode;
        return $this;
    }

    /**
     * Set Excess Transportation Information (C401).
     *
     * @param string $sExcessTransportationReasonCode
     * @param string $sExcessTransportationResponsibilityCode
     * @param string|null $sCustomerShipmentAuthorisationIdentifier
     * @return self
     */
    public function setExcessTransportationInformation(
        string $sExcessTransportationReasonCode,
        ?string $sExcessTransportationResponsibilityCode = null,
        ?string $sCustomerShipmentAuthorisationIdentifier = null
    ): self {
        $aExcessTransportationInformation = [
            $sExcessTransportationReasonCode
        ];
        if ($sExcessTransportationResponsibilityCode !== null) {
            $aExcessTransportationInformation[] = $sExcessTransportationResponsibilityCode;
        }
        if ($sCustomerShipmentAuthorisationIdentifier !== null) {
            $aExcessTransportationInformation[] = $sCustomerShipmentAuthorisationIdentifier;
        }
        $this->aExcessTransportationInformation = $aExcessTransportationInformation;
        return $this;
    }

    /**
     * Set Transport Identification (C222).
     *
     * @param string|null $sTransportMeansIdentificationNameIdentifier
     * @param string|null $sCodeListIdentificationCode
     * @param string|null $sCodeListResponsibleAgencyCode
     * @param string|null $sTransportMeansIdentificationName
     * @param string|null $sTransportMeansNationalityCode
     * @return self
     */
    public function setTransportIdentification(
        ?string $sTransportMeansIdentificationNameIdentifier = null,
        ?string $sCodeListIdentificationCode = null,
        ?string $sCodeListResponsibleAgencyCode = null,
        ?string $sTransportMeansIdentificationName = null,
        ?string $sTransportMeansNationalityCode = null
    ): self {
        $aTransportIdentification = [];
        if ($sTransportMeansIdentificationNameIdentifier !== null) {
            $aTransportIdentification[] = $sTransportMeansIdentificationNameIdentifier;
        }
        if ($sCodeListIdentificationCode !== null) {
            $aTransportIdentification[] = $sCodeListIdentificationCode;
        }
        if ($sCodeListResponsibleAgencyCode !== null) {
            $aTransportIdentification[] = $sCodeListResponsibleAgencyCode;
        }
        if ($sTransportMeansIdentificationName !== null) {
            $aTransportIdentification[] = $sTransportMeansIdentificationName;
        }
        if ($sTransportMeansNationalityCode !== null) {
            $aTransportIdentification[] = $sTransportMeansNationalityCode;
        }
        $this->aTransportIdentification = $aTransportIdentification;
        return $this;
    }

    /**
     * Set Transport Means Ownership Indicator Code.
     *
     * @param string $sTransportMeansOwnershipIndicatorCode
     * @return self
     */
    public function setTransportMeansOwnershipIndicatorCode(string $sTransportMeansOwnershipIndicatorCode): self
    {
        $this->sTransportMeansOwnershipIndicatorCode = $sTransportMeansOwnershipIndicatorCode;
        return $this;
    }

    /**
     * Set Power Type (C003).
     *
     * @param string|null $sPowerTypeCode
     * @param string|null $sCodeListIdentificationCode
     * @param string|null $sCodeListResponsibleAgencyCode
     * @param string|null $sPowerTypeDescription
     * @return self
     */
    public function setPowerType(
        ?string $sPowerTypeCode = null,
        ?string $sCodeListIdentificationCode = null,
        ?string $sCodeListResponsibleAgencyCode = null,
        ?string $sPowerTypeDescription = null
    ): self {
        $aPowerType = [];
        if ($sPowerTypeCode !== null) {
            $aPowerType[] = $sPowerTypeCode;
        }
        if ($sCodeListIdentificationCode !== null) {
            $aPowerType[] = $sCodeListIdentificationCode;
        }
        if ($sCodeListResponsibleAgencyCode !== null) {
            $aPowerType[] = $sCodeListResponsibleAgencyCode;
        }
        if ($sPowerTypeDescription !== null) {
            $aPowerType[] = $sPowerTypeDescription;
        }
        $this->aPowerType = $aPowerType;
        return $this;
    }

    /**
     * Set Transport Service (C290).
     *
     * @param string|null $sTransportServiceIdentificationCode
     * @param string|null $sCodeListIdentificationCode
     * @param string|null $sCodeListResponsibleAgencyCode
     * @param string|null $sTransportServiceName
     * @param string|null $sTransportServiceDescription
     * @return self
     */
    public function setTransportService(
        ?string $sTransportServiceIdentificationCode = null,
        ?string $sCodeListIdentificationCode = null,
        ?string $sCodeListResponsibleAgencyCode = null,
        ?string $sTransportServiceName = null,
        ?string $sTransportServiceDescription = null
    ): self {
        $aTransportService = [];
        if ($sTransportServiceIdentificationCode !== null) {
            $aTransportService[] = $sTransportServiceIdentificationCode;
        }
        if ($sCodeListIdentificationCode !== null) {
            $aTransportService[] = $sCodeListIdentificationCode;
        }
        if ($sCodeListResponsibleAgencyCode !== null) {
            $aTransportService[] = $sCodeListResponsibleAgencyCode;
        }
        if ($sTransportServiceName !== null) {
            $aTransportService[] = $sTransportServiceName;
        }
        if ($sTransportServiceDescription !== null) {
            $aTransportService[] = $sTransportServiceDescription;
        }
        $this->aTransportService = $aTransportService;
        return $this;
    }

    /**
     * Compose the segment.
     *
     * @return self
     */
    public function compose(): self
    {
        $aComposed = [self::SEGMENT_NAME];

        if ($this->sTransportStageCodeQualifier !== null) {
            $aComposed[] = $this->sTransportStageCodeQualifier;
        }

        if ($this->sMeansOfTransportJourneyIdentifier !== null) {
            $aComposed[] = $this->sMeansOfTransportJourneyIdentifier;
        }

        if (!empty($this->aModeOfTransport)) {
            $aComposed[] = $this->aModeOfTransport;
        }

        if (!empty($this->aTransportMeans)) {
            $aComposed[] = $this->aTransportMeans;
        }

        if (!empty($this->aCarrier)) {
            $aComposed[] = $this->aCarrier;
        }

        if ($this->sTransitDirectionIndicatorCode !== null) {
            $aComposed[] = $this->sTransitDirectionIndicatorCode;
        }

        if (!empty($this->aExcessTransportationInformation)) {
            $aComposed[] = $this->aExcessTransportationInformation;
        }

        if (!empty($this->aTransportIdentification)) {
            $aComposed[] = $this->aTransportIdentification;
        }

        if ($this->sTransportMeansOwnershipIndicatorCode !== null) {
            $aComposed[] = $this->sTransportMeansOwnershipIndicatorCode;
        }

        if (!empty($this->aPowerType)) {
            $aComposed[] = $this->aPowerType;
        }

        if (!empty($this->aTransportService)) {
            $aComposed[] = $this->aTransportService;
        }

        $this->setComposed($aComposed);

        return $this;
    }
}