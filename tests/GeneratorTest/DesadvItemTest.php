<?php
/**
 * Created by PhpStorm.
 * User: Sascha
 * Date: 23.01.2018
 * Time: 13:38
 */

namespace GeneratorTest;

use EDI\Generator\Desadv\Item;
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
            '1',
            'PCE',
        ], $item->getQuantity());
    }


    public function testGetDescription()
    {
        $item = new Item();
        $item->setDescription(
            'article description'
        );

        $this->assertEquals([
            'IMD',
            '',
            '',
            'ZU',
            '',
            '89',
            'article description',
        ], $item->getDescription());
    }


}