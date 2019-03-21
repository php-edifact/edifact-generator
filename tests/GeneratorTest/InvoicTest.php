<?php

namespace GeneratorTest;

use EDI\Encoder;
use EDI\Generator\EdifactException;
use EDI\Generator\Interchange;
use EDI\Generator\Invoic;
use PHPUnit\Framework\TestCase;

/**
 * Class InvoicTest
 * @package GeneratorTest
 */
final class InvoicTest extends TestCase
{

    public function testBeginOfMessage()
    {
        $encoder = new Encoder(
            [Invoic::addBGMSegment('INV1234', '380')],
            true
        );
        $this->assertEquals(
            'BGM+380::89+INV1234\'',
            $encoder->get()
        );
    }


    public function testFreeText()
    {
        $this->assertEquals(
            'FTX+OSI++HAE:89+reduction of fees text\'',
            (new Encoder([
                Invoic::addFTXSegment(
                    'reduction of fees text',
                    'OSI',
                    'HAE'
                )]))->get()
        );
    }

    public function testCurrency()
    {
        $this->assertEquals(
            'CUX+2:EUR\'',
            (new Encoder([
                (new Invoic)->setCurrency()->getCurrency()]))->get()
        );
    }


    public function testInvoice()
    {
        $interchange = (new Interchange(
            'UNB-Identifier-Sender',
            'UNB-Identifier-Receiver'
        ))
            ->setCharset('UNOC', '3');
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
                ->setCurrency('EUR')
            ;
            $item = new Invoic\Item();
            $item
                ->setPosition('1', 'articleId')
                ->setQuantity('5')
                ->setAdditionalText('additionalText')
                ->setInvoiceDescription('this is a longer description for testing inside item position')
                ->setNetPrice('22.50')
                ->setGrossPrice('26.775')
                ->setOrderNumberWholeSaler('545.SWEB-05622249-002')
                ->setOrderDate($this->getDateTime())
                ->setDeliveryNotePosition('20')
                ->setDeliveryNoteNumber('deliverNoteNumber')
                ->setDeliveryNoteDate($this->getDateTime())
            ;
            $item->addDiscount(-20.34, Invoic\Item::DISCOUNT_TYPE_ABSOLUTE);
            $item->addDiscount(3);

            $invoice->addItem($item);


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

        } catch (EdifactException $e) {
            fwrite(STDOUT, "\n\nINVOICE\n" . $e->getMessage());
        }
    }

    /**
     * @return \DateTime
     * @throws \Exception
     */
    private function getDateTime()
    {
        return (new \DateTime())
            ->setDate(2018, 1, 23)
            ->setTime(10, 0, 0);
    }
}