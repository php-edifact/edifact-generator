<?php
namespace EDI\Generator\Iftmbf;

class Container
{
    private $goodsID;
    private $cntr;
    private $weight;
    private $weightEq;

    public function __construct($goodsID)
    {
        $this->goodsID = $goodsID;
    }

    /*
     * $size = 22G1, 42G1, etc; 306 = smdg, 6436 = ISO spec
     * $statusCode = 1 (Continental), 2 (Export), 3 (Import)
     * $fullEmptyIndicator = 4 (Empty), 5 (Full)
     */
    public function setContainer($size)
    {
        $this->cntr = \EDI\Generator\Message::eqdSegment('CN', $this->goodsID, [$size, '102', '5'], '', 2, 5);
        return $this;
    }

    /*
     * Add the array counter as id for this group
     */
    public function setGoodsID($id) {
        $this->goodsID = $id;
        return $this;
    }

    /*
     * Goods description
     */
    public function setGoodsDescription($description) {
        $description = str_split($description, 35);
        $this->goodsDescription = ['FTX', 'AAA', '', '', $description];
        return $this;
    }

    /*
     * Goods weight
     */
    public function setWeight($weight, $unit = 'KGM') {
        $this->weight = ['MEA', 'AAE', 'G', [$unit, $weight]];
        $this->weightEq = ['MEA', 'AAE', 'AAL', [$unit, $weight]];
        return $this;
    }

    /*
     * Goods pick up location
     * $code Code identifying the pick up location
     * $name Company name (max 70 chars)
     * $address Address (max 105 chars)
     * $postalCode ZIP Code
     */
    public function setShipFrom($code, $name, $address, $postalCode)
    {
        $name = str_split($name, 35);
        $address = str_split($address, 35);

        $this->shipFrom = ['NAD', 'SF', [$code, 160, 'ZZZ'], array_merge($name, $address), '', '', '', '', $postalCode];
        return $this;
    }

    public function setShipDate($date)
    {
        $this->shipDate = self::dtmSegment(200, $date);
        return $this;
    }

    /*
     * $cntType: RP = responsible person (DE 3139)
     * $cntTitle: free text
     * $comData: free text
     * $comType: DE 3155
     */
    public function setShipContact($cntType, $cntTitle, $comType = null, $comData = null)
    {
        $this->shipContact = [];
        $this->shipContact[] = ['CTA', $cntType, ['', $cntTitle]];
        if ($comType !== null) {
            $this->shipContact[] = ['COM', [$comData, $comType]];
        }
        return $this;
    }

    public function composeGoods()
    {
        $composed = [
            ['GID', $this->goodsID],
            $this->goodsDescription,
            $this->weight
        ];

        return $composed;
    }

    public function composeEquipment()
    {
        $composed = [
            $this->cntr,
            ['EQN', 1],
            $this->weightEq
        ];
        if ($this->shipContact !== null) {
            $composed[] = $this->shipContact[0];
            if (isset($this->shipContact[1])) {
                $composed[] = $this->shipContact[1];
            }
        }
        return $composed;
    }
}
