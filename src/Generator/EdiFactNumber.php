<?php

namespace EDI\Generator;

/**
 * Class EdiFactNumber
 * @package EDI\Generator
 */
class EdiFactNumber
{
    public const DECIMAL_COMMA = ',';
    public const DECIMAL_POINT = '.';

    /**
     * @param string|float|double $value
     * @param int $decimals
     * @param string $format
     * @return string
     */
    public static function convert($value, $decimals = 2, $format = self::DECIMAL_POINT)
    {
        if (!is_numeric($value)) {
            $value = floatval(str_replace(',', '.', $value));
        }
        return number_format($value, $decimals, $format, '');
    }
}
