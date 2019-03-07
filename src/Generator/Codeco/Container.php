<?php

namespace EDI\Generator\Codeco;

class Container
{
    private $cntr;
    private $bkg;

    private $weight;
    private $seal;
    private $effectiveDate;

    private $modeOfTransport;

    public function __construct()
    {
    }

    /*
     * $size = 22G1, 42G1, etc; 306 = smdg, 6436 = ISO spec
     * $statusCode = 1 (Continental), 2 (Export), 3 (Import)
     * $fullEmptyIndicator = 4 (Empty), 5 (Full)
     */
    public function setContainer($number, $size, $statusCode, $fullEmptyIndicator)
    {
        $this->cntr = \EDI\Generator\Message::eqdSegment('CN', $number, [$size, '6346', '306'], '', $statusCode, $fullEmptyIndicator);

        return $this;
    }

    /*
     *
     */
    public function setBooking($booking, $sequence = null)
    {
        $this->bkg = \EDI\Generator\Message::rffSegment('BN', $booking);

        return $this;
    }

    /*
     *
     */
    public function setBillOfLading($bl)
    {
        $this->bkg = \EDI\Generator\Message::rffSegment('BM', $bl);

        return $this;
    }

    /*
     * $seal = free text
     * $sealIssuer = DE 9303
     */
    public function setSeal($seal, $sealIssuer)
    {
        $this->seal = ['SEL', [$seal, $sealIssuer]];

        return $this;
    }

    /*
     * Date of the equipment event
     */
    public function setEffectiveDate($date = null)
    {
        if ($date === null) {
            $date = date('YmdHi');
        }
        $this->effectiveDate = \EDI\Generator\Message::dtmSegment(7, $date);

        return $this;
    }

    /*
     * $transportMode = DE 8067 (2 = rail, 3 = road)
     * $transportMeans = DE 8179 (25 = train, 31 = truck)
     */
    public function setModeOfTransport($transportMode, $transportMeans)
    {
        $this->modeOfTransport = \EDI\Generator\Message::tdtShortSegment(1, '', $transportMode, $transportMeans);

        return $this;
    }

    /*
     * $type = 165 (place of delivery)
     */
    public function setLocation($locode)
    {
        $this->destination = \EDI\Generator\Message::locSegment(165, [$locode, 139, 6]);

        return $this;
    }

    /*
     * Weight information
     * $type = G (gross mass), VGM (verified gross mass)
     *
     */
    public function setWeight($type, $weight)
    {
        $this->weight = ['MEA', 'AAE', $type, ['KGM', $weight]];

        return $this;
    }

    public function compose()
    {
        $composed = [$this->cntr];
        if ($this->bkg !== null) {
            $composed[] = $this->bkg;
        }
        $composed[] = $this->effectiveDate;
        if ($this->weight !== null) {
            $composed[] = $this->weight;
        }
        if ($this->seal !== null) {
            $composed[] = $this->seal;
        }
        if ($this->modeOfTransport !== null) {
            $composed[] = $this->modeOfTransport;
        }

        return $composed;
    }
}
