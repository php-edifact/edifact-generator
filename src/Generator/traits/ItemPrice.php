<?php

namespace EDI\Generator\Traits;
use EDI\Generator\EdiFactNumber;


/**
 * Trait ItemPrice
 * @package EDI\Generator\Traits
 */
trait ItemPrice {

    /** @var array */
    protected $grossPrice;
    /** @var array */
    protected $netPrice;

    /**
     * @param $qualifier
     * @param $value
     * @param int $priceBase
     * @param string $priceBaseUnit
     * @return array
     */
    public static function addPRISegment($qualifier, $value, $priceBase = 1, $priceBaseUnit = 'PCE')
    {
        return [
            'PRI',
            [
                $qualifier,
                EdiFactNumber::convert($value),
                '',
                '',
                $priceBase,
                $priceBaseUnit
            ]
        ];
    }

    /**
     * @return array
     */
    public function getGrossPrice()
    {
        return $this->grossPrice;
    }

    /**
     * @param string $grossPrice
     * @return \EDI\Generator\Traits\ItemPrice
     */
    public function setGrossPrice($grossPrice)
    {
        $this->grossPrice = self::addPRISegment('GRP', $grossPrice);
        $this->addKeyToCompose('grossPrice');

        return $this;
    }

    /**
     * @return array
     */
    public function getNetPrice()
    {
        return $this->netPrice;
    }

    /**
     * @param string $netPrice
     * @return \EDI\Generator\Traits\ItemPrice
     */
    public function setNetPrice($netPrice)
    {
        $this->netPrice = self::addPRISegment('NTP', $netPrice);
        $this->addKeyToCompose('netPrice');

        return $this;
    }
}