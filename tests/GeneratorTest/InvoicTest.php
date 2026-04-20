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
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\RecursionContext\InvalidArgumentException;

/**
 * Class InvoicTest
 *
 * @package GeneratorTest
 */
final class InvoicTest extends TestCase {

  /**
   * Test discount
   * @return void 
   * @throws InvalidArgumentException 
   * @throws ExpectationFailedException 
   */
  public function testDiscount() {
    $interchange = (new Interchange(
      'UNB-Identifier-Sender',
      'UNB-Identifier-Receiver'
    ))
      ->setCharset('UNOC')
      ->setSenderQualifier(14)
      ->setReceiverQualifier(14)
      ->setCharsetVersion('3');
    $invoice = new Invoic();

    try {
      $item = new Invoic\Item();
      $item
        ->setPosition(1, 'articleId')
        ->setQuantity(5)
        ->setNetPrice(823.20)
        ->setAdditionalText('')
        ->setGrossPrice(840.0);
      $item
        ->addDiscount(-2.0, Invoic\Item::DISCOUNT_TYPE_PERCENT, 840, 'Grundrabatt', 'TD')
        ->addDiscountFactor(823.20, 840);

      $invoice->addItem($item);
      $invoice->compose();
    } catch (EdifactException $e) {
      fwrite(STDOUT, "\n\nINVOICE\n" . $e->getMessage());
    }

    $encoder = new Encoder($interchange->addMessage($invoice)->getComposed(), true);

    $message = str_replace("'", "'\n", $encoder->get());
    $this->assertStringContainsString("PRI+GRP:840,00:::1:PCE'", $message);
    $this->assertStringContainsString(
      "ALC+A++++TD:::Grundrabatt'\nPCD+3:2,00'\nMOA+8:16,80'\nALC+A++++SF'\nPCD+1:0,9800'\nMOA+8:16,80'",
      $message
    );
    $this->assertStringContainsString("UNT+13+", $message);
  }

  /**
   *
   */
  public function testBeginOfMessageInvoice() {
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
  public function testBeginOfMessageReversal() {
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
  public function testFreeText() {
    $this->assertEquals(
      'FTX+OSI++HAE::89+reduction of fees text\'',
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
   * Test currency
   */
  public function testCurrency() {
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
  public function testPCD() {
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
  public function testPAT() {
    $this->assertEquals(
      'PAT+22++5\'',
      (new Encoder(
        [
          Invoic::addPATSegment(Base::PAT_SKONTO),
        ]
      ))->get()
    );
  }


  public function testCredit() {
    $this->assertEquals(
      'BGM+381::89+123456+9\'',
      (new Encoder(
        [
          Invoic::addBGMSegment('123456', Invoic::TYPE_CREDIT_NOTE),
        ]
      ))->get()
    );
  }

  public function testCreditStorno() {
    $this->assertEquals(
      'BGM+381::89+123456+1\'',
      (new Encoder(
        [
          Invoic::addBGMSegment('123456', Invoic::TYPE_CREDIT_NOTE, Invoic::TYPE_REVERSAL),
        ]
      ))->get()
    );
  }


  /**
   * Skonto
   */
  public function testSkonto() {
    $interchange = (new Interchange(
      'UNB-Identifier-Sender',
      'UNB-Identifier-Receiver'
    ))
      ->setCharset('UNOC')
      ->setSenderQualifier(14)
      ->setReceiverQualifier(14)
      ->setCharsetVersion('3');
    $invoice = new Invoic();

    try {
      $invoice
        ->setRepresentativeAddress(
          'tester'
        )
        ->setRepresentativeAddressTaxNumber('327/5787/3111')
        ->addCashDiscount('2020-04-20', 3)
        ->addCashDiscount('2020-04-27', 1)
        ->addNetAmount(26, '2020-05-01')
        ->addCharges(320);
      $invoice->compose();
    } catch (EdifactException $e) {
      fwrite(STDOUT, "\n\nINVOICE\n" . $e->getMessage());
    }
    $encoder = new Encoder($interchange->addMessage($invoice)->getComposed(), true);

    $message = str_replace("'", "'\n", $encoder->get());
    //    fwrite(STDOUT, "\n\nINVOICE\n" . $message);

    $this->assertStringContainsString('RFF+FC:327/5787/3111', $message);
    $this->assertStringContainsString('PAT+22++5', $message);
    $this->assertStringContainsString('DTM+343:20200420:102', $message);
    $this->assertStringContainsString('PCD+12:3,00', $message);

    $this->assertStringContainsString('DTM+343:20200427:102', $message);
    $this->assertStringContainsString('PCD+12:1,00', $message);

    $this->assertStringContainsString('PAT+ZZZ++5:::26', $message);
    $this->assertStringContainsString('DTM+13:20200501:102', $message);
    $this->assertStringContainsString('MOA+8:320,00', $message);

    $this->assertStringContainsString("UNB+UNOC:3+UNB-Identifier-Sender:14+UNB-Identifier-Receiver:14", $message);
  }


  /**
   *
   */
  public function testInvoice() {
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
          'DE',
          '9',
          'MFADDRESS',
          'DE123456789MF'
        )->setWholesalerAddress(
          'Name 1',
          'Name 2',
          'Name 3',
          'Street',
          '99999',
          'city',
          'DE',
          '9',
          '4250724100005'
        )->setRepresentativeAddress(
          'Name 1',
          'Name 2',
          'Name 3',
          'Street',
          '99999',
          'city',
          'DE',
          '9',
          '4260257750004'
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
        ->setSpecificationText('specificText')
        ->setAdditionalText(
          'specificText and this is a longer description for testing inside item position, array_push($subArray, $segmentData);, array_push($subArray, $segmentData);, array_push($subArray, $segmentData);'
        )
        ->setInvoiceDescription('this is a longer description for testing inside item position')
        ->setNetPrice(22.50)
        ->setGrossPrice(26.775)
        ->setOrderNumberWholeSaler('545.SWEB-05622249-002')
        ->setOrderDate($this->getDateTime())
        ->setDeliveryNotePosition(20)
        ->setDeliveryNoteNumber('deliverNoteNumber')
        ->setDeliveryNoteDate($this->getDateTime())
        ->setDeliveryDate($this->getDateTime());


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


      $this->assertStringContainsString("UNB+UNOC:3+UNB-Identifier-Sender+UNB-Identifier-Receiver+", $message);
      $this->assertStringContainsString(
        "NAD+SU+MFADDRESS::9++Name 1:Name 2:Name 3+Street+city++99999+DE'\nRFF+VA:DE123456789MF'\n",
        $message
      );

      $this->assertStringContainsString("NAD+WS+", $message);
      $this->assertStringContainsString("NAD+AB+", $message);
      $this->assertStringContainsString("LIN+1++articleId:MF'\nIMD+++SP:::specificText", $message);
      $this->assertStringContainsString("TAX+7+VAT+++:::19,00'\nMOA+150:19,11", $message);
      $this->assertStringContainsString('ALC+C++++DL', $message);
      $this->assertStringContainsString('MOA+8:149,00', $message);
      $this->assertStringContainsString('UNT+46', $message);
    } catch (EdifactException $e) {
      fwrite(STDOUT, "\n\nINVOICE\n" . $e->getMessage());
    }
  }



  /**
   * Test name and address with no space
   * @return void 
   * @throws InvalidArgumentException 
   * @throws ExpectationFailedException 
   */
  public function testNameAndAddressWithNoSpace() {
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
        ->setWholesalerAddress(
          ' Name 1',
          ' Name 2 ',
          ' Name 3 ',
          ' Street ',
          ' 99999 ',
          ' City ',
          ' DE ',
          ' 9 ',
          ' 42507241123123 '
        )
        ->setInvoiceAddress(
          ' Name 1',
          ' Name 2 ',
          ' Name 3 ',
          ' Street ',
          ' 99999 ',
          ' City ',
          ' DE ',
          ' 9 ',
          ' 42507241123123 '
        )
        ->setInvoiceAddressVatId('XXX-12333--DD')
        ->setInvoiceAddressFiscalNumber('DE12343122')
      ;



      $item = new Invoic\Item();
      $item
        ->setPosition(1, 'articleId')
        ->setQuantity(5)
        ->setSpecificationText('ERSS Typ 200 Weiß')
        ->setAdditionalText(
          'Brauchwasserspeicher  - EEK: B'
        )
        ->setInvoiceDescription('this is a longer description for testing inside item position')
        ->setNetPrice(22.50)
        ->setGrossPrice(26.775)
        ->setOrderNumberWholeSaler('545.SWEB-05622249-002')
        ->setOrderPosition(22)
        ->setOrderDate($this->getDateTime())
        ->setDeliveryNotePosition(20)
        ->setDeliveryNoteNumber('deliverNoteNumber')
        ->setDeliveryNoteDate($this->getDateTime())
        ->setDeliveryDate($this->getDateTime());
      $invoice->addItem($item);


      $invoice->compose();
      $encoder = new Encoder($interchange->addMessage($invoice)->getComposed(), true);
      $encoder->setUNA(":+,? '");
      $message = str_replace("'", "'\n", $encoder->get());

      $this->assertStringContainsString(
        "NAD+WS+42507241123123::9++Name 1:Name 2:Name 3+Street+City++99999+DE'",
        $message
      );

      $this->assertStringContainsString(
        "NAD+IV+42507241123123::9++Name 1:Name 2:Name 3+Street+City++99999+DE'\n"  .
          "RFF+VA:XXX-12333--DD'\n" .
          "RFF+FC:DE12343122'",
        $message
      );

      $this->assertStringContainsString(
        "LIN+1++articleId:MF'\n" .
          "IMD+++SP:::ERSS Typ 200 Wei'\n" .
          "IMD+++ZU:::Brauchwasserspeicher  - EEK?: B'\n" .
          "QTY+12:5:PCE'\n" .
          "DTM+35:20180123:102'\n" .
          "FTX+INV++::89+this is a longer description for testing inside item position'\n" .
          "PRI+NTP:22,50:::1:PCE'\n" .
          "PRI+GRP:26,78:::1:PCE'\n" .
          "RFF+VN:545.SWEB-05622249-002'\n" .
          "DTM+4:20180123:102'\n" .
          "RFF+LI:22'\n" .
          "RFF+AAJ:deliverNoteNumber'\n" .
          "DTM+2:20180123:102'\n" .
          "RFF+FI:20'\n",

        // "LIN+1++articleId:MF'\n" .
        //   "IMD+++SP:::ERSS Typ 200 Wei'\n" .
        //   "IMD+++ZU:::Brauchwasserspeicher  - EEK?: B'\n" .
        //   "QTY+12:5:PCE'\n" .
        //   "DTM+35:20180123:102'\n" .
        //   "FTX+INV++::89+this is a longer description for testing inside item position'\n" .
        //   "PRI+GRP:26,78:::1:PCE'\n" .
        //   "PRI+NTP:22,50:::1:PCE'\n" .
        //   "RFF+VN:545.SWEB-05622249-002'\n" .
        //   "DTM+4:20180123:102'\n" .
        //   "RFF+LI:22'\n" .
        //   "RFF+AAJ:deliverNoteNumber'\n" .
        //   "RFF+FI:20'\n",
        $message
      );
      // file_put_contents(getcwd() . 'cache/InvoicTest.edi.txt', $message, FILE_APPEND);
    } catch (EdifactException $e) {
      fwrite(STDOUT, "\n\nINVOICE\n" . $e->getMessage());
    }
  }

  /**
   * @return \DateTime
   */
  private function getDateTime() {
    return (new \DateTime())
      ->setDate(2018, 1, 23)
      ->setTime(10, 0, 0);
  }
}
