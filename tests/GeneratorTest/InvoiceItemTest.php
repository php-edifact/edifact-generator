<?php

namespace Generator;

use EDI\Encoder;
use EDI\Generator\Invoic\Item;
use PHPUnit\Framework\TestCase;

/**
 * Class InvoicItemTest
 *
 * @package Generator
 */
class InvoicItemTest extends TestCase
{


  /**
   * Preis
   */
  public function testPrice()
  {
    $this->assertEquals(
      'PRI+NTP:25,00:::1:PCE\'',
      (new Encoder(
        [
          Item::addPRISegment('NTP', '25,00'),
        ]
      ))->get()
    );
  }

  /**
   *
   */
  public function testAdditionalProductInformation()
  {
    $this->assertEquals(
      'PIA+1+555:EN\'',
      (new Encoder(
        [
          Item::addPIASegment('555'),
        ]
      ))->get()
    );
  }
}
