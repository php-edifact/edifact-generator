<?php
/**
 * Created by PhpStorm.
 * User: Sascha
 * Date: 18.01.2018
 * Time: 16:01
 */

namespace GeneratorTest;

use EDI\Encoder;
use EDI\Generator\Base;
use EDI\Generator\EdifactException;
use EDI\Generator\Interchange;
use EDI\Generator\Invoic;
use PHPUnit\Framework\TestCase;

/**
 * Class InvoicTest
 *
 * @package GeneratorTest
 */
final class InvoicTest extends TestCase
{

  /**
   *
   */
  public function testBeginOfMessageInvoice()
  {
    $encoder = new Encoder(
      [Invoic::addBGMSegment('INV1234', Invoic::TYPE_INVOICE)],
      true
    );
    $this->assertEquals(
      'BGM+380::89+INV1234+9\'',
      $encoder->get()
    );
  }

  /**
   *
   */
  public function testBeginOfMessageReversal()
  {
    $encoder = new Encoder(
      [Invoic::addBGMSegment('INV1234', Invoic::TYPE_INVOICE, Invoic::TYPE_REVERSAL)],
      true
    );
    $this->assertEquals(
      'BGM+380::89+INV1234+1\'',
      $encoder->get()
    );
  }


  /**
   *
   */
  public function testFreeText()
  {
    $this->assertEquals(
      'FTX+OSI++HAE:89+reduction of fees text\'',
      (new Encoder(
        [
          Invoic::addFTXSegment(
            'reduction of fees text',
            'OSI',
            'HAE'
          ),
        ]
      ))->get()
    );
  }

  /**
   *
   */
  public function testCurrency()
  {
    $this->assertEquals(
      'CUX+2:EUR\'',
      (new Encoder(
        [
          (new Invoic)->setCurrency()->getCurrency(),
        ]
      ))->get()
    );
  }

  /**
   *
   */
  public function testPCD()
  {
    $this->assertEquals(
      'PCD+12:3,00\'',
      (new Encoder(
        [
          Invoic::addPCDSegment(3),
        ]
      ))->get()
    );
  }


  /**
   *
   */
  public function testPAT()
  {
    $this->assertEquals(
      'PAT+22++5\'',
      (new Encoder(
        [
          Invoic::addPATSegment(Base::PAT_SKONTO),
        ]
      ))->get()
    );
  }

  /**
   * Skonto
   */
  public function testSkonto()
  {
    $interchange = (new Interchange(
      'UNB-Identifier-Sender',
      'UNB-Identifier-Receiver'
    ))
      ->setCharset('UNOC')
      ->setCharsetVersion('3');
    $invoice = new Invoic();
    $invoice->addCashDiscount('2020-04-20', 3);
    $invoice->addCashDiscount('2020-04-27', 1);
    $invoice->addNetAmount(26, '2020-05-01');
    try {
      $invoice->compose();
    } catch (EdifactException $e) {
    }
    $encoder = new Encoder($interchange->addMessage($invoice)->getComposed(), true);

    $message = str_replace("'", "'\n", $encoder->get());
//    fwrite(STDOUT, "\n\nINVOICE\n" . $message);

    $this->assertContains('PAT+22++5', $message);
    $this->assertContains('DTM+343:20200420:102', $message);
    $this->assertContains('PCD+12:3,00', $message);

    $this->assertContains('DTM+343:20200427:102', $message);
    $this->assertContains('PCD+12:1,00', $message);

    $this->assertContains('PAT+ZZZ++5:::26', $message);
    $this->assertContains('DTM+13:20200501:102', $message);
  }


  /**
   *
   */
  public function testInvoice()
  {
    $interchange = (new Interchange(
      'UNB-Identifier-Sender',
      'UNB-Identifier-Receiver'
    ))
      ->setCharset('UNOC')
      ->setCharsetVersion('3');
    $invoice = new Invoic();

    try {
      $invoice
        ->setInvoiceNumber('INV12345')
        ->setInvoiceDate($this->getDateTime())
        ->setDeliveryDate($this->getDateTime())
        ->setReductionOfFeesText('reduction')
        ->setExcludingVatText('excluding Vat text with more as 70 characters used for testing')
        ->setInvoiceDescription('invoiceDescription')
        ->setManufacturerAddress(
          'Name 1',
          'Name 2',
          'Name 3',
          'Street',
          '99999',
          'city',
          'DE'
        )->setWholesalerAddress(
          'Name 1',
          'Name 2',
          'Name 3',
          'Street',
          '99999',
          'city',
          'DE'
        )->setDeliveryAddress(
          'Name 1',
          'Name 2',
          'Name 3',
          'Street',
          '99999',
          'city',
          'DE'
        )->setContactPerson('John Doe')
        ->setMailAddress('john.doe@company.com')
        ->setPhoneNumber('+49123456789')
        ->setFaxNumber('+49123456789-11')
        ->setVatNumber('DE 123456789')
        ->setCurrency('EUR');
      $item = new Invoic\Item();
      $item
        ->setPosition(1, 'articleId')
        ->setQuantity(5)
        ->setAdditionalText('additionalText')
        ->setInvoiceDescription('this is a longer description for testing inside item position')
        ->setNetPrice(22.50)
        ->setGrossPrice(26.775)
        ->setOrderNumberWholeSaler('545.SWEB-05622249-002')
        ->setOrderDate($this->getDateTime())
        ->setDeliveryNotePosition(20)
        ->setDeliveryNoteNumber('deliverNoteNumber')
        ->setDeliveryNoteDate($this->getDateTime());
      $item->addDiscount(-20.34, 30, Invoic\Item::DISCOUNT_TYPE_ABSOLUTE);

      $invoice->addItem($item);


      $invoice->addCharges('149');

      $invoice
        ->setTotalPositionsAmount(100.22)
        ->setBasisAmount(80)
        ->setTaxableAmount(80)
        ->setPayableAmount(100.22)
        ->setTax(19, 19.11);

      $invoice->compose();
      $encoder = new Encoder($interchange->addMessage($invoice)->getComposed(), true);
      $encoder->setUNA(":+,? '");
      $message = str_replace("'", "'\n", $encoder->get());
//            fwrite(STDOUT, "\n\nINVOICE\n" . $message);

      $this->assertContains('UNT+40', $message);
      $this->assertContains('TAX+7+VAT+++:::19,00', $message);
      $this->assertContains('ALC+C++++DL', $message);
      $this->assertContains('MOA+8:149,00', $message);

    } catch (EdifactException $e) {
      fwrite(STDOUT, "\n\nINVOICE\n" . $e->getMessage());
    }
  }

  /**
   * @return \DateTime
   */
  private function getDateTime()
  {
    return (new \DateTime())
      ->setDate(2018, 1, 23)
      ->setTime(10, 0, 0);
  }
}
