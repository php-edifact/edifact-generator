<?php

namespace Generator;

use EDI\Encoder;
use EDI\Generator\EdifactException;
use EDI\Generator\Interchange;
use EDI\Generator\Invoic;
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
   *
   */
  public function testDiscount()
  {
    $interchange = (new Interchange(
      'UNB-Identifier-Sender',
      'UNB-Identifier-Receiver'
    ))
      ->setCharset('UNOC')
      ->setCharsetVersion('3');

    $invoice = new Invoic();
    $message = "";
    try {
      $invoice
        ->setInvoiceNumber('INV12345');
      $item = new Item();
      $item
        ->setPosition(1, 'articleId')
        ->addDiscount(-3, 840);
      $invoice->addItem($item);


      $item2 = new Item();
      $item2
        ->setPosition(2, 'articleId')
        ->addDiscount(-20, 1000, Item::DISCOUNT_TYPE_ABSOLUTE);
      $invoice->addItem($item2);



      $invoice->compose();
      $encoder = new Encoder($interchange->addMessage($invoice)->getComposed(), true);
      $encoder->setUNA(":+,? '");
      $message = str_replace("'", "'\n", $encoder->get());
    } catch (EdifactException $e) {

    }

    $this->assertContains('ALC+A++++SF', $message);
    $this->assertContains('PCD+3:25,20', $message);
    $this->assertContains('MOA+8:840,00', $message);


    $this->assertContains('PCD+3:20,00', $message);
    $this->assertContains('MOA+8:1000,00', $message);


  }


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
