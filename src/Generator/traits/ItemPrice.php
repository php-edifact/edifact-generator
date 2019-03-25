<?php

namespace EDI\Generator\Traits;

use EDI\Generator\EdiFactNumber;

/**
 * Trait ItemPrice
 * @url http://www.unece.org/trade/untdid/d96b/uncl/uncl5125.htm
 * @package EDI\Generator\Traits
 */
trait ItemPrice
{
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
                (string)$priceBase,
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
     * @return $this
     */
    public function setGrossPrice($grossPrice)
    {
        $this->grossPrice = self::addPRISegment('AAB', $grossPrice);
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
     * @return $this
     */
    public function setNetPrice($netPrice)
    {
        $this->netPrice = self::addPRISegment('AAA', $netPrice);
        $this->addKeyToCompose('netPrice');

        return $this;
    }
}
