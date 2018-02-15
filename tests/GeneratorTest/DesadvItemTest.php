<?php
/**
 * Created by PhpStorm.
 * User: Sascha
 * Date: 23.01.2018
 * Time: 13:38
 */

namespace GeneratorTest;

use EDI\Encoder;
use EDI\Generator\Desadv\Item;
use EDI\Generator\EdifactException;
use PHPUnit\Framework\TestCase;

/**
 * Class DesadvItemTest
 * @package GeneratorTest
 */
class DesadvItemTest extends TestCase
{
    public function testGetPosition()
    {
        $item = new Item();
        $item->setPosition(
            '1',
            '8290123'
        );

        $this->assertEquals([
            'LIN',
            '1',
            '',
            '8290123',
            'MF',
        ], $item->getPosition());
    }

    /**
     * @throws \EDI\Generator\EdifactException
     */
    public function testGetQuantity()
    {
        $item = new Item();
        $item->setQuantity(
            '1'
        );

        $this->assertEquals([
            'QTY',
            '12',
            '1,000',
            'PCE',
        ], $item->getQuantity());
    }

    /**
     *
     */
    public function XtestGetAdditionalText()
    {
        $item = (new Item())
            ->setAdditionalText('Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, ali');

        $this->assertEquals([
            'Lorem ipsum dolor sit amet, consectetuer',
            ' adipiscing elit. Aenean commodo ligula ',
            'eget dolor. Aenean massa. Cum sociis nat',
            'oque penatibus et magnis dis parturient ',
            'montes, nascetur ridiculus mus. Donec qu',
            'am felis, ultricies nec, pellentesque eu',
            ', pretium quis, sem. Nulla consequat mas',
            'sa quis enim. Donec pede justo, fringill',
        ], $item->getAdditionalText());

        try {
            $composed = $item->compose();
            $this->assertEquals(8, count($composed));
            $this->assertEquals([
                'IMD',
                '',
                '',
                'ZU',
                '',
                '89',
                'am felis, ultricies nec, pellentesque eu'
            ], $composed[5]);
        } catch (EdifactException $e) {

        }
    }

    /**
     * @ignore
     */
    public function XtestGetSpecificationText()
    {
        $item = (new Item())
            ->setSpecificationText('Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, ali');

        $this->assertEquals([
            'Lorem ipsum dolor sit amet, consectetuer',
            ' adipiscing elit. Aenean commodo ligula ',
        ], $item->getSpecificationText());

        try {
            $composed = $item->compose();
            $this->assertEquals(2, count($composed));
            $this->assertEquals([
                'IMD',
                '',
                '',
                'ZU',
                '89',
                ' adipiscing elit. Aenean commodo ligula '
            ], $composed[1]);
        } catch (EdifactException $e) {

        }
    }


    public function XtestIMDSegment()
    {
        $line = 'IMD+++SP:::12345678901234567890123456789012345:12345\'';
        $encoder = new Encoder(\EDI\Generator\Traits\Item::addIMDSegment('12345678901234567890123456789012345'), true);
        $this->assertEquals(
            $line,
            $encoder->get()
        );



    }


}