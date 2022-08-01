<?php

namespace EDI\Generator\Codeco;

/**
 * Class Container
 * @package EDI\Generator\Codeco
 */
class Container
{
    private $cntr;
    private $bkg;

    private $weight;
    private $seal;
    private $effectiveDate;
    private $destination;
    private $modeOfTransport;

    public function __construct()
    {
    }

    /**
     * $size = 22G1, 42G1, etc; 306 = smdg, 6436 = ISO spec
     * $statusCode = 1 (Continental), 2 (Export), 3 (Import)
     * $fullEmptyIndicator = 4 (Empty), 5 (Full)
     * @param $number
     * @param $size
     * @param $statusCode
     * @param $fullEmptyIndicator
     * @return \EDI\Generator\Codeco\Container
     */
    public function setContainer($number, $size, $statusCode, $fullEmptyIndicator)
    {
        $this->cntr = \EDI\Generator\Message::eqdSegment('CN', $number, [$size, '6346', '306'], '', $statusCode, $fullEmptyIndicator);

        return $this;
    }

    /**
     * @param $booking
     * @param null $sequence
     * @return \EDI\Generator\Codeco\Container
     */
    public function setBooking($booking, $sequence = null)
    {
        $this->bkg = \EDI\Generator\Message::rffSegment('BN', $booking);

        return $this;
    }

    /**
     * @param $bl
     * @return \EDI\Generator\Codeco\Container
     */
    public function setBillOfLading($bl)
    {
        $this->bkg = \EDI\Generator\Message::rffSegment('BM', $bl);

        return $this;
    }

    /**
     * $seal = free text
     * $sealIssuer = DE 9303
     * @param $seal
     * @param $sealIssuer
     * @return \EDI\Generator\Codeco\Container
     */
    public function setSeal($seal, $sealIssuer)
    {
        $this->seal = ['SEL', [$seal, $sealIssuer]];

        return $this;
    }

    /**
     * Date of the equipment event
     * @param null $date
     * @return \EDI\Generator\Codeco\Container
     */
    public function setEffectiveDate($date = null)
    {
        if ($date === null) {
            $date = date('YmdHi');
        }
        $this->effectiveDate = \EDI\Generator\Message::dtmSegment(7, $date);

        return $this;
    }

    /**
     * $transportMode = DE 8067 (2 = rail, 3 = road)
     * $transportMeans = DE 8179 (25 = train, 31 = truck)
     * @param $transportMode
     * @param $transportMeans
     * @return \EDI\Generator\Codeco\Container
     */
    public function setModeOfTransport($transportMode, $transportMeans)
    {
        $this->modeOfTransport = \EDI\Generator\Message::tdtShortSegment(1, '', $transportMode, $transportMeans);

        return $this;
    }

    /**
     * $type = 165 (place of delivery)
     * @param $locode
     * @return \EDI\Generator\Codeco\Container
     */
    public function setLocation($locode)
    {
        $this->destination = \EDI\Generator\Message::locSegment(165, [$locode, 139, 6]);

        return $this;
    }

    /**
     * Weight information
     * $type = G (gross mass), VGM (verified gross mass)
     * @param $type
     * @param $weight
     * @return \EDI\Generator\Codeco\Container
     */
    public function setWeight($type, $weight)
    {
        $this->weight = ['MEA', 'AAE', $type, ['KGM', $weight]];

        return $this;
    }

    /**
     * @return array
     */
    public function compose()
    {
        $composed = [$this->cntr];
        if ($this->bkg !== null) {
            $composed[] = $this->bkg;
        }
        $composed[] = $this->effectiveDate;
        if ($this->destination !== null) {
            $composed[] = $this->destination;
        }
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
