<?php
namespace EDI\Generator;

class Iftmin extends Message
{
    
    private $messageSender;
    private $messageSenderInformation;
    private $dtmSend;
    private $pickupDate;
    private $deliveryDate;
    private $agreedAmount;
    private $freeTextInstructions;
    private $cargoNature;
    private $weight;
    private $transportOrderNumber;
    private $booking;
    private $bookingSequence;

    public function __construct($messageID = null, $identifier = 'IFTMIN', $version = 'D', $release = '04A', $controllingAgency = 'UN', $association = 'BIG14')
    {
        parent::__construct($identifier, $version, $release, $controllingAgency, $messageID, $association);

        $this->dtmSend = self::dtmSegment(137, date('YmdHi'));

        $this->containers = [];
    }

    public function setSender($name, $email)
    {
        $this->messageSender = ['CTA', 'BK', ['', $name]];
        $this->messageSenderInformation = ['COM', [$email, 'EM']];
        return $this;
    }

    public function setPickupDateRange($earliest, $latest)
    {
        $this->pickupDate = [self::dtmSegment(234, $earliest), self::dtmSegment(235, $latest)];
        return $this;
    }

    public function setDeliveryDateRange($earliest, $latest)
    {
        $this->deliveryDate = [self::dtmSegment(64, $earliest), self::dtmSegment(63, $latest)];
        return $this;
    }

    /**
     * $currency ISO 4217-3
     */
    public function setAgreedAmount($price, $currency)
    {
        $this->agreedAmount = ['MOA', [121, $price, $currency]];
        return $this;
    }

    /**
     * Free text instructions
     * $ftx Max 512*5 chars
     */
    public function setFreeTextInstructions($ftx)
    {
        $this->freeTextInstructions = ['FTX', 'AAI', '', '', str_split($ftx, 512)];
        return $this;
    }

    /*
     * Estimated weight in tonnes
     */
    public function setEstimatedWeight($weight) {
        $this->weight = ['CNT', ['7', $weight, 'TNE']];
        $this->weightKg = ['MEA', 'WT', 'AET', ['KGM', $weight]];
        return $this;
    }

    /**
     * Cargo nature
     * $cargo DE 7085
     */
    public function setCargoNature($cargo)
    {
        $this->cargoNature = ['GDS', $cargo];
        return $this;
    }

    public function setTransportOrderNumber($adn)
    {
        $this->transportOrderNumber = self::rffSegment('ADN', $adn);
        return $this;
    }

    public function setBooking($booking, $bookingType = 'BN', $sequence = null)
    {
        $this->booking = self::rffSegment($bookingType, $booking);
        if ($sequence !== null) {
            $this->bookingSequence = self::rffSegment('SQ', $sequence);
        }
        return $this;
    }

    /*
     * Vessel call information
     *
     */
    public function setVessel($extVoyage, $line, $vslName, $callsign)
    {
        $this->vessel = self::tdtSegment(20, $extVoyage, '', '', [$line, 172, 20], '', '', [$callsign, 146, 11, $vslName]);
        return $this;
    }

    /*
     * Consignee / Consignor
     *
     * $what = CN (Consignee) / CZ (Consignor)
     * $code = usually VAT
     * $name
     * $address
     */
    public function setConsignment($what, $code, $name, $address, $postalCode)
    {
        $name = str_split($name, 35);
        $address = str_split($address, 35);

        $this->forwarder = ['NAD', $what, $code, '', $name, $address, '', '', $postalCode];
        return $this;
    }

    public function compose(?string $sMessageFunctionCode = "5", ?string $sDocumentNameCode = "171", ?string $sDocumentIdentifier = null): parent
    {
        $this->messageContent = [
            ['BGM', $sDocumentNameCode, $this->messageID, $sMessageFunctionCode]
        ];
        $this->messageContent[] = $this->messageSender;
        $this->messageContent[] = $this->messageSenderInformation;
        $this->messageContent[] = $this->dtmSend;
        $this->messageContent[] = $this->pickupDate[0];
        $this->messageContent[] = $this->pickupDate[1];
        $this->messageContent[] = $this->deliveryDate[0];
        $this->messageContent[] = $this->deliveryDate[1];
        $this->messageContent[] = $this->agreedAmount;
        $this->messageContent[] = $this->freeTextInstructions;
        $this->messageContent[] = $this->weight;
        $this->messageContent[] = $this->cargoNature;
        $this->messageContent[] = $this->transportOrderNumber;
        $this->messageContent[] = $this->booking;
        if ($this->bookingSequence !== null) {
            $this->messageContent[] = $this->bookingSequence;
        }
        $this->messageContent[] = $this->vessel;

        $this->messageContent[] = $this->weightKg;

        return parent::compose($sMessageFunctionCode, $sDocumentNameCode, $sDocumentIdentifier);
    }
}
