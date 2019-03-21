<?php

namespace GeneratorTest;


use EDI\Generator\EdifactDate;
use PHPUnit\Framework\TestCase;

class EdifactDateTest extends TestCase
{

    /**
     * date format test
     * @throws \EDI\Generator\EdifactException
     */
    public function testDateFormat()
    {
        $this->assertEquals(
            '20180123',
            EdifactDate::get('2018-01-23')
        );
    }

    /**
     * date format test
     * @throws \EDI\Generator\EdifactException
     */
    public function testDateTimeFormat()
    {
        $this->assertEquals(
            '201801231000',
            EdifactDate::get('2018-01-23 10:00:00', EdifactDate::DATETIME)
        );
    }

    /**
     *
     */
    public function testParseFormat()
    {
        $dateTime = (new \DateTime())
            ->setDate(2018, 1, 23)
            ->setTime(10, 0, 0);
        $this->assertEquals(
            $dateTime,
            EdifactDate::parseFormat('2018-01-23 10:00:00', EdifactDate::DATETIME)
        );
    }


    public function testParseTimeFormat()
    {
        $dateTime = (new \DateTime())
            ->setDate(2018, 1, 23)
            ->setTime(10, 0, 0);
        $this->assertEquals(
            $dateTime,
            EdifactDate::parseFormat('2018-01-23 10:00', EdifactDate::DATETIME)
        );
    }

}