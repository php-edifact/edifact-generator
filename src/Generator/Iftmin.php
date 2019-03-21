<?php

namespace EDI\Generator;

/**
 * Class Iftmin
 * @package EDI\Generator
 */
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

    /**
     * Iftmin constructor.
     * @param null $messageID
     * @param string $identifier
     * @param string $version
     * @param string $release
     * @param string $controllingAgency
     * @param string $association
     */
    public function __construct($messageID = null, $identifier = 'IFTMIN', $version = 'D', $release = '04A', $controllingAgency = 'UN', $association = 'BIG14')
    {
        parent::__construct($identifier, $version, $release, $controllingAgency, $messageID, $association);

        $this->dtmSend = self::dtmSegment(137, date('YmdHi'));

        $this->containers = [];
    }

    /**
     * @param string $name
     * @param $email
     * @return $this|\EDI\Generator\Message
     */
    public function setSender($name, $email)
    {
        $this->messageSender = ['CTA', 'BK', ['', $name]];
        $this->messageSenderInformation = ['COM', [$email, 'EM']];
        return $this;
    }

    /**
     * @param $earliest
     * @param $latest
     * @return $this
     */
    public function setPickupDateRange($earliest, $latest)
    {
        $this->pickupDate = [self::dtmSegment(234, $earliest), self::dtmSegment(235, $latest)];
        return $this;
    }

    /**
     * @param $earliest
     * @param $latest
     * @return $this
     */
    public function setDeliveryDateRange($earliest, $latest)
    {
        $this->deliveryDate = [self::dtmSegment(64, $earliest), self::dtmSegment(63, $latest)];
        return $this;
    }

    /**
     * $currency ISO 4217-3
     * @param $price
     * @param $currency
     * @return \EDI\Generator\Iftmin
     */
    public function setAgreedAmount($price, $currency)
    {
        $this->agreedAmount = ['MOA', [121, $price, $currency]];
        return $this;
    }

    /**
     * Free text instructions
     * $ftx Max 512*5 chars
     * @param $ftx
     * @return \EDI\Generator\Iftmin
     */
    public function setFreeTextInstructions($ftx)
    {
        $this->freeTextInstructions = ['FTX', 'AAI', '', '', str_split($ftx, 512)];
        return $this;
    }

    /**
     * Estimated weight in tonnes
     * @param $weight
     * @return \EDI\Generator\Iftmin
     */
    public function setEstimatedWeight($weight)
    {
        $this->weight = ['CNT', ['7', $weight, 'TNE']];
        $this->weightKg = ['MEA', 'WT', 'AET', ['KGM', $weight]];
        return $this;
    }

    /**
     * Cargo nature
     * $cargo DE 7085
     * @param $cargo
     * @return \EDI\Generator\Iftmin
     */
    public function setCargoNature($cargo)
    {
        $this->cargoNature = ['GDS', $cargo];
        return $this;
    }

    /**
     * @param $adn
     * @return $this
     */
    public function setTransportOrderNumber($adn)
    {
        $this->transportOrderNumber = self::rffSegment('ADN', $adn);
        return $this;
    }

    /**
     * @param $booking
     * @param string $bookingType
     * @param null $sequence
     * @return $this
     */
    public function setBooking($booking, $bookingType = 'BN', $sequence = null)
    {
        $this->booking = self::rffSegment($bookingType, $booking);
        if ($sequence !== null) {
            $this->bookingSequence = self::rffSegment('SQ', $sequence);
        }
        return $this;
    }

    /**
     * Vessel call information
     * @param $extVoyage
     * @param $line
     * @param $vslName
     * @param $callsign
     * @return \EDI\Generator\Iftmin
     */
    public function setVessel($extVoyage, $line, $vslName, $callsign)
    {
        $this->vessel = self::tdtSegment(20, $extVoyage, '', '', [$line, 172, 20], '', '', [$callsign, 146, 11, $vslName]);
        return $this;
    }

    /**
     * Consignee / Consignor
     *
     * $what = CN (Consignee) / CZ (Consignor)
     * $code = usually VAT
     * $name
     * $address
     * @param $what
     * @param $code
     * @param $name
     * @param $address
     * @param $postalCode
     * @return \EDI\Generator\Iftmin
     */
    public function setConsignment($what, $code, $name, $address, $postalCode)
    {
        $name = str_split($name, 35);
        $address = str_split($address, 35);

        $this->forwarder = ['NAD', $what, $code, '', $name, $address, '', '', $postalCode];
        return $this;
    }

    /**
     * @param string|null $sMessageFunctionCode
     * @param string|null $sDocumentNameCode
     * @param string|null $sDocumentIdentifier
     * @return \EDI\Generator\Message
     * @throws \EDI\Generator\EdifactException
     */
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
