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
        ->setPosition(2, 'articleId')
        ->setGrossPrice(385)
        ->setNetPrice(354.78)
        ->setOrderNumberWholesaler('4501532449')
        ->setDeliveryNoteNumber(931551, "2021-04-19")
        ->addDiscount(-5, Item::DISCOUNT_TYPE_PERCENT, 385, 'LKW Lieferung')
        ->addDiscount(-3, Item::DISCOUNT_TYPE_PERCENT, 385, 'Sonderrabatt')
        ->addDiscountFactor(354.78, 385);

      $invoice->addItem($item);
      $invoice->compose();
      $encoder = new Encoder($interchange->addMessage($invoice)->getComposed(), true);
      $encoder->setUNA(":+,? '");
      $message = str_replace("'", "'\n", $encoder->get());
    } catch (EdifactException $e) {
      fwrite(STDOUT, "\n\nINVOICE-ITEM\n" . $e->getMessage());
    }
    $this->assertContains("PRI+GRP:385,00:::1:PCE'\nPRI+NTP:354,78:::1:PCE'\nRFF+VN:4501532449'\nRFF+AAJ:931551'\nDTM+2:20210419:102'", $message);
    $this->assertContains("ALC+A++++ZZZ:::LKW Lieferung'\nPCD+3:5,00'\nMOA+8:19,25", $message);
    $this->assertContains("ALC+A++++ZZZ:::Sonderrabatt'\nPCD+3:3,00'\nMOA+8:11,55", $message);
    $this->assertContains("ALC+A++++SF'\nPCD+1:0,9215'\nMOA+8:30,22", $message);
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
