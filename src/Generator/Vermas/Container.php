<?php
namespace EDI\Generator\Vermas;

class Container
{

    private $cntr;
    private $bkg;
    private $seal;
    private $measures;
    private $weighDate;
    private $weighMethod;
    private $shipper;
    private $spcContact;

    public function __construct()
    {

    }

    /*
     * $size = 22G1, 42G1, etc
     */
    public function setContainer($number, $size)
    {
        $this->cntr = ['EQD', 'CN', $number, [$size, '6346', '306']]; // 306 = smdg, 6436 = ISO spec
        return $this;
    }

    /*
     *
    */
    public function setBooking($booking, $sequence = null)
    {
        $bkg = [];
        $bkg[]= ['RFF', ['BN', $booking]]; // RFF codes DE 1153
        if ($sequence !== null) {
            $bkg[]= ['RFF', ['SQ', $sequence]];
        }
        $this->bkg = $bkg;
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
     * $weightMode = DE 6313
     * $weight = free text
     * $unit = KGM or LBS
     */
    public function setMeasures($weightMode, $weight, $unit = 'KGM')
    {
        $this->measures = ['MEA', 'AAE', $weightMode, [$unit, $weight]];
        return $this;
    }

    /*
     * $type = SM1 | SM2
     * $cert = documentation identification
     */
    public function setWeighMethod($type, $cert)
    {
        $this->weighMethod = ['DOC', [$type, 'VGM', 306], $cert];
        return $this;
    }

    /*
     * $type = SM1 | SM2
     * $cert = documentation identification
     */
    public function setWeighDate($date = null)
    {
        if ($date === null) {
            $date = date('YmdHi');
        }
        $this->weighDate = \EDI\Generator\Message::dtmSegment(798, $date);
        return $this;
    }
 
    /*
     * $spcShipper = SOLAS verified gross mass responsible party
     */
    public function setShipper($spcShipper)
    {
        $this->shipper = ['NAD', 'SPC', '', $spcShipper];
        return $this;
    }

    /*
     * $cntType: RP = responsible person (DE 3139)
     * $cntTitle: free text
     * $comData: free text
     * $comType: DE 3155
     */
    public function setSpcContact($cntType, $cntTitle, $comType, $comData)
    {
        $this->spcContact = [];
        $this->spcContact[] = ['CTA', $cntType, ['', $cntTitle]];
        $this->spcContact[] = ['COM', [$comData, $comType]];
        return $this;
    }

    public function compose()
    {
        $composed = [];
        if ($this->cntr !== null) {
            $composed[] = $this->cntr;
        }
        if ($this->bkg !== null) {
            $composed[] = $this->bkg[0];
            $composed[] = $this->bkg[1];
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
        if ($this->shipper !== null) {
            $composed[] = $this->shipper;
        }
        if ($this->spcContact !== null) {
            $composed[] = $this->spcContact[0];
            $composed[] = $this->spcContact[1];
        }
        return $composed;
    }
}
