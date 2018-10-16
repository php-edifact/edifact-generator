<?php
/**
 * Created by PhpStorm.
 * User: Sascha
 * Date: 08.02.2018
 * Time: 15:37
 */

namespace EDI\Generator;

/**
 * Class EdiFactNumber
 * @package EDI\Generator
 */
class EdiFactNumber
{
    const DECIMAL_COMMA = ',';
    const DECIMAL_POINT = '.';

    /**
     * @param string|float|double $value
     * @param int $decimals
     * @param string $format
     * @return string
     */
    public static function convert($value, $decimals = 2, $format = self::DECIMAL_COMMA)
    {
        if (!is_numeric($value)) {
            $value = floatval(str_replace(',', '.', $value));
        }

        return number_format($value, $decimals, $format, '');
    }

}