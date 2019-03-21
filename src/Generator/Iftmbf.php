<?php

namespace EDI\Generator;

/**
 * Class Iftmbf
 * @package EDI\Generator
 */
class Iftmbf extends Message
{
    private $messageSender;
    private $messageSenderInformation;
    private $dtmSend;

    private $transportRequirements;
    private $freeTextInstructions;
    private $cargoNature;

    private $placeOfReceipt;
    private $placeOfDelivery;
    private $bookingOffice;

    private $contractNumber;
    private $shipmentReference;

    private $vessel;
    private $pol;
    private $pod;

    private $bookingParty;
    private $carrier;
    private $forwarder;
    private $consignor;

    private $containers = [];

    /**
     * Construct.
     *
     * @param mixed  $sMessageReferenceNumber        (0062)
     * @param string $sMessageType                   (0065)
     * @param string $sMessageVersionNumber          (0052)
     * @param string $sMessageReleaseNumber          (0054)
     * @param string $sMessageControllingAgencyCoded (0051)
     * @param string $sAssociationAssignedCode       (0057)
     */
    public function __construct(
        $sMessageReferenceNumber = null,
        $sMessageType = 'IFTMBF',
        $sMessageVersionNumber = 'D',
        $sMessageReleaseNumber = '00B',
        $sMessageControllingAgencyCoded = 'UN',
        $sAssociationAssignedCode = '2.0'
    ) {
        parent::__construct($sMessageType, $sMessageVersionNumber, $sMessageReleaseNumber,
            $sMessageControllingAgencyCoded, $sMessageReferenceNumber, $sAssociationAssignedCode);

        $this->dtmSend = self::dtmSegment(137, date('YmdHi'));
    }

    /**
     * @param string $name
     * @param $email
     * @return $this|\EDI\Generator\Message
     */
    public function setSender($name, $email)
    {
        $this->messageSender = ['CTA', 'IC', ['', $name]];
        $this->messageSenderInformation = ['COM', [$email, 'EM']];

        return $this;
    }

    /**
     * Transport type requested
     * $tsr DE 4065.
     * @param $tsr
     * @return \EDI\Generator\Iftmbf
     */
    public function setTransportRequirements($tsr)
    {
        $this->transportRequirements = ['TSR', 27];

        return $this;
    }

    /**
     * Free text instructions
     * $ftx Max 512*5 chars.
     * @param $ftx
     * @return \EDI\Generator\Iftmbf
     */
    public function setFreeTextInstructions($ftx)
    {
        $this->freeTextInstructions = ['FTX', 'AAI', '', '', str_split($ftx, 512)];

        return $this;
    }

    /**
     * Cargo nature
     * $cargo DE 7085.
     * @param $cargo
     * @return \EDI\Generator\Iftmbf
     */
    public function setCargoNature($cargo)
    {
        $this->cargoNature = ['GDS', $cargo];

        return $this;
    }

    /**
     * @param $porLocode
     * @return $this
     */
    public function setPlaceOfReceipt($porLocode)
    {
        $this->placeOfReceipt = self::locSegment(88, [$porLocode, 181, 6]);

        return $this;
    }

    /**
     * @param $podLocode
     * @return $this
     */
    public function setPlaceOfDelivery($podLocode)
    {
        $this->placeOfDelivery = self::locSegment(7, [$podLocode, 181, 6]);

        return $this;
    }

    /**
     * @param $bkgLocode
     * @return $this
     */
    public function setBookingOffice($bkgLocode)
    {
        $this->bookingOffice = self::locSegment(197, [$bkgLocode, 181, 6]);

        return $this;
    }

    /**
     * @param $ctNumber
     * @return $this
     */
    public function setContractNumber($ctNumber)
    {
        $this->contractNumber = self::rffSegment('CT', $ctNumber);

        return $this;
    }

    /**
     * @param $siNumber
     * @return $this
     */
    public function setShipmentReference($siNumber)
    {
        $this->shipmentReference = self::rffSegment('SI', $siNumber);

        return $this;
    }

    /**
     * Vessel call information
     *
     * $extVoyage Common voyage reference
     * $scac SCAC code for the liner
     * $imonumber Vessel IMO number (7 digits)
     * $vslName Vessel name
     * @param $extVoyage
     * @param $scac
     * @param $vslName
     * @param $imonumber
     * @return \EDI\Generator\Iftmbf
     */
    public function setVessel($extVoyage, $scac, $vslName, $imonumber)
    {
        $this->vessel = self::tdtSegment(20, $extVoyage, 1, 8, [$scac, 172, 182], '', '', [$imonumber, 146, 11, $vslName]);

        return $this;
    }

    /**
     * Port of Loading
     * @param $loc
     * @return \EDI\Generator\Iftmbf
     */
    public function setPOL($loc)
    {
        $this->pol = self::locSegment(9, [$loc, 139, 6]);

        return $this;
    }

    /**
     * Port of Discharge
     * @param $loc
     * @return \EDI\Generator\Iftmbf
     */
    public function setPOD($loc)
    {
        $this->pod = self::locSegment(11, [$loc, 139, 6]);

        return $this;
    }

    /**
     * Booking party
     * $code Code identifying the booking party
     * $name Company name (max 70 chars)
     * $address Address (max 105 chars)
     * $postalCode ZIP Code
     * @param $code
     * @param $name
     * @param $address
     * @param $postalCode
     * @return \EDI\Generator\Iftmbf
     */
    public function setBookingParty($code, $name, $address, $postalCode)
    {
        $name = str_split($name, 35);
        $address = str_split($address, 35);

        $this->bookingParty = ['NAD', 'ZZZ', [$code, 160, 'ZZZ'], array_merge($name, $address), '', '', '', '', $postalCode];

        return $this;
    }

    /**
     * $scac SCAC code for the liner
     * @param $scac
     * @return \EDI\Generator\Iftmbf
     */
    public function setCarrier($scac)
    {
        $this->carrier = ['NAD', 'CA', [$scac, 160, 'ZZZ']];

        return $this;
    }

    /**
     * @param $code
     * @param $name
     * @param $address
     * @param $postalCode
     * @return $this
     */
    public function setForwarder($code, $name, $address, $postalCode)
    {
        $name = str_split($name, 35);
        $address = str_split($address, 35);

        $this->forwarder = ['NAD', 'FW', [$code, 160, 'ZZZ'], array_merge($name, $address), '', '', '', '', $postalCode];

        return $this;
    }

    /**
     * @param $code
     * @param $name
     * @param $address
     * @param $postalCode
     * @return $this
     */
    public function setConsignor($code, $name, $address, $postalCode)
    {
        $name = str_split($name, 35);
        $address = str_split($address, 35);

        $this->consignor = ['NAD', 'CZ', [$code, 160, 'ZZZ'], array_merge($name, $address), '', '', '', '', $postalCode];

        return $this;
    }

    /**
     * @param \EDI\Generator\Iftmbf\Container $container
     * @return $this
     */
    public function addContainer(Iftmbf\Container $container)
    {
        $this->containers[] = $container;

        return $this;
    }

    /**
     * Compose.
     *
     * @param mixed $sMessageFunctionCode (1225)
     * @param mixed $sDocumentNameCode (1001)
     * @param mixed $sDocumentIdentifier (1004)
     *
     * @return \EDI\Generator\Message ::compose()
     * @throws \EDI\Generator\EdifactException
     */
    public function compose(?string $sMessageFunctionCode = "5", ?string $sDocumentNameCode = "335", ?string $sDocumentIdentifier = null): parent
    {
        $this->messageContent = [
            ['BGM', $sDocumentNameCode, $this->messageID, $sMessageFunctionCode],
        ];

        $this->messageContent[] = $this->messageSender;
        $this->messageContent[] = $this->messageSenderInformation;
        $this->messageContent[] = $this->dtmSend;

        $this->messageContent[] = $this->transportRequirements;
        $this->messageContent[] = $this->freeTextInstructions;
        $this->messageContent[] = $this->cargoNature;

        $this->messageContent[] = $this->placeOfDelivery;
        $this->messageContent[] = $this->placeOfReceipt;
        $this->messageContent[] = $this->bookingOffice;

        $this->messageContent[] = $this->contractNumber;
        $this->messageContent[] = $this->shipmentReference;

        $this->messageContent[] = $this->vessel;
        $this->messageContent[] = $this->pol;
        $this->messageContent[] = $this->pod;

        $this->messageContent[] = $this->bookingParty;
        $this->messageContent[] = $this->carrier;
        $this->messageContent[] = $this->forwarder;
        $this->messageContent[] = $this->consignor;

        //$this->messageContent[] = ['GID', 1];

        foreach ($this->containers as $cntr) {
            $content = $cntr->composeGoods();

            foreach ($content as $segment) {
                $this->messageContent[] = $segment;
            }
        }

        foreach ($this->containers as $cntr) {
            $content = $cntr->composeEquipment();

            foreach ($content as $segment) {
                $this->messageContent[] = $segment;
            }
        }

        return parent::compose($sMessageFunctionCode, $sDocumentNameCode, $sDocumentIdentifier);
    }
}
