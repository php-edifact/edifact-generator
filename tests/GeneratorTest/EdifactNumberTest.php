<?php

namespace GeneratorTest;



use EDI\Generator\EdiFactNumber;
use PHPUnit\Framework\TestCase;

/**
 * Class EdifactNumberTest
 * @package GeneratorTest
 */
class EdifactNumberTest extends TestCase {


    public function testNumberWithComma()
    {
        $this->assertEquals(
            '100,12',
            EdiFactNumber::convert('100,123')
        );
    }

    public function testNumberWithPoint()
    {
        $this->assertEquals(
            '100,22',
            EdiFactNumber::convert(100.223)
        );
    }

    public function testNumberFromString()
    {
        $this->assertEquals(
            '100,22',
            EdiFactNumber::convert('100.223')
        );
    }

    public function testNumberFromStringComma()
    {
        $this->assertEquals(
            '100,22',
            EdiFactNumber::convert('100,223')
        );
    }


    public function testNumberNegative()
    {
        $this->assertEquals(
            '-100,22',
            EdiFactNumber::convert('-100,223')
        );
    }
}