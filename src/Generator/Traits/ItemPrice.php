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
     * @param int $decimals
     * @param string $format
     * @return array
     */
    public static function addPRISegment($qualifier, $value, $priceBase = 1, $priceBaseUnit = 'PCE', $decimals = 2, $format = EdiFactNumber::DECIMAL_COMMA)
    {
        return [
            'PRI',
            [
                $qualifier,
                EdiFactNumber::convert($value, $decimals, $format),
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
     * @param string $format
     * @param int $decimals
     * @return $this
     */
    public function setGrossPrice($grossPrice, $format = EdiFactNumber::DECIMAL_COMMA, $decimals = 2)
    {
        $this->grossPrice = self::addPRISegment('AAB', $grossPrice, 1, 'PCE', $decimals, $format);
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
     * @param string $format
     * @param int $decimals
     * @return $this
     */
    public function setNetPrice($netPrice, $format = EdiFactNumber::DECIMAL_COMMA, $decimals = 2)
    {
        $this->netPrice = self::addPRISegment('AAA', $netPrice, 1, 'PCE', $decimals, $format);
        $this->addKeyToCompose('netPrice');

        return $this;
    }
}
