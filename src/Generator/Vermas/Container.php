<?php

namespace EDI\Generator\Vermas;

/**
 * Class Container
 * @package EDI\Generator\Vermas
 */
class Container
{
    private $cntr;
    private $bkg;
    private $seal;
    private $measures;
    private $weighDate;
    private $weighMethod;
    private $spcWpa;
    private $shipper;
    private $spcContact;

    public function __construct()
    {
    }

    /**
     * $size = 22G1, 42G1, etc; 306 = smdg, 6436 = ISO spec
     * @param $number
     * @param $size
     * @param bool $fixedFields
     * @return \EDI\Generator\Vermas\Container
     */
    public function setContainer($number, $size, $fixedFields = false)
    {
        $this->cntr = \EDI\Generator\Message::eqdSegment('CN', $number, [$size, '6346', '306']);
        if ($fixedFields) {
            $this->cntr = \EDI\Generator\Message::eqdSegment('CN', $number, [$size, '6346', '306'], '', '', 5);
        }

        return $this;
    }

    /**
     * @param $booking
     *@param $sequence
     * @return \EDI\Generator\Vermas\Container
     */
    public function setBooking($booking, $sequence = null)
    {
        $bkg = [];
        $bkg[] = \EDI\Generator\Message::rffSegment('BN', $booking);
        if ($sequence !== null) {
            $bkg[] = \EDI\Generator\Message::rffSegment('SQ', $sequence);
        }
        $this->bkg = $bkg;

        return $this;
    }

    /**
     * $seal = free text
     * $sealIssuer = DE 9303
     * @param $seal
     * @param $sealIssuer
     * @return \EDI\Generator\Vermas\Container
     */
    public function setSeal($seal, $sealIssuer)
    {
        $this->seal = ['SEL', [$seal, $sealIssuer]];

        return $this;
    }

    /**
     * $weightMode = DE 6313
     * $weight = free text
     * $unit = KGM or LBS
     * @param $weightMode
     * @param $weight
     * @param string $unit
     * @return \EDI\Generator\Vermas\Container
     */
    public function setMeasures($weightMode, $weight, $unit = 'KGM')
    {
        $this->measures = ['MEA', 'AAE', $weightMode, [$unit, $weight]];

        return $this;
    }

    /**
     * $type = SM1 | SM2
     * $cert = documentation identification
     * @param $type
     * @param $cert
     * @return \EDI\Generator\Vermas\Container
     */
    public function setWeighMethod($type, $cert)
    {
        $this->weighMethod = ['DOC', [$type, 'VGM', 306], $cert];

        return $this;
    }

    /**
     * $type = SM1 | SM2
     * $cert = documentation identification
     *@param $date
     * @return \EDI\Generator\Vermas\Container
     */
    public function setWeighDate($date = null)
    {
        if ($date === null) {
            $date = date('YmdHi');
        }
        $this->weighDate = \EDI\Generator\Message::dtmSegment(798, $date);

        return $this;
    }

    /**
     * $spcShipper = SOLAS verified gross mass responsible party
     * @param $spcWpa
     * @return \EDI\Generator\Vermas\Container
     */
    public function setWeighingStationId($spcWpa)
    {
        $this->spcWpa = ['NAD', 'WPA', $spcWpa];

        return $this;
    }

    /**
     * $spcShipper = SOLAS verified gross mass responsible party
     * @param $spcShipper
     *@param $spcCity
     * @return \EDI\Generator\Vermas\Container
     */
    public function setShipper($spcShipper, $spcCity = null)
    {
        $this->shipper = ['NAD', 'SPC', '', '', $spcShipper];
        if ($spcCity !== null) {
            $this->shipper[] = '';
            $this->shipper[] = $spcCity;
        }

        return $this;
    }

    /**
     * $cntType: RP = responsible person (DE 3139)
     * $cntTitle: free text
     * $comData: free text
     * $comType: DE 3155
     * @param $cntType
     * @param $cntTitle
     *@param $comType
     *@param $comData
     * @return \EDI\Generator\Vermas\Container
     */
    public function setSpcContact($cntType, $cntTitle, $comType = null, $comData = null)
    {
        $this->spcContact = [];
        $this->spcContact[] = ['CTA', $cntType, ['', $cntTitle]];
        if ($comType !== null) {
            $this->spcContact[] = ['COM', [$comData, $comType]];
        }

        return $this;
    }

    /**
     * @return array
     */
    public function compose()
    {
        $composed = [];
        if ($this->cntr !== null) {
            $composed[] = $this->cntr;
        }
        if ($this->bkg !== null) {
            $composed[] = $this->bkg[0];
            if (isset($this->bkg[1])) {
                $composed[] = $this->bkg[1];
            }
        }
        if ($this->seal !== null) {
            $composed[] = $this->seal;
        }
        if ($this->measures !== null) {
            $composed[] = $this->measures;
        }
        if ($this->weighDate !== null) {
            $composed[] = $this->weighDate;
        }
        if ($this->weighMethod !== null) {
            $composed[] = $this->weighMethod;
        }
        if ($this->spcWpa !== null) {
            $composed[] = $this->spcWpa;
        }
        if ($this->shipper !== null) {
            $composed[] = $this->shipper;
        }
        if ($this->spcContact !== null) {
            $composed[] = $this->spcContact[0];
            if (isset($this->spcContact[1])) {
                $composed[] = $this->spcContact[1];
            }
        }

        return $composed;
    }
}
