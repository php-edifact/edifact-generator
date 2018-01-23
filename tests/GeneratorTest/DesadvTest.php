<?php
/**
 * Created by PhpStorm.
 * User: Sascha
 * Date: 18.01.2018
 * Time: 16:01
 */

namespace GeneratorTest;

use EDI\Encoder;
use EDI\Generator\Desadv;
use EDI\Generator\EdifactException;
use EDI\Generator\Interchange;
use PHPUnit\Framework\TestCase;

final class DesadvTest extends TestCase
{
    /**
     * Test deliver note number
     */
    public function testDeliverNoteNumber()
    {
        $desadv = new Desadv();
        try {
            $desadv->setDeliveryNoteNumber(
                Desadv::DELIVER_NOTE,
                'LS123456789'
            );
        } catch (EdifactException $e) {

        }
        $array = $desadv->getDeliverNoteNumber();
        $this->assertEquals([
            'BGM',
            '270',
            'LS123456789'
        ], $array);
    }


    private function getDateTime()
    {
        return (new \DateTime())
            ->setDate(2018, 1, 23)
            ->setTime(10, 0, 0);
    }

    /**
     * @throws EdifactException
     */
    public function testDeliverNoteNumberException()
    {
        $this->expectExceptionMessage('document type not allowed here');
        (new Desadv())
            ->setDeliveryNoteNumber('XXX', 'LS123456789');

    }

    /**
     * @throws EdifactException
     */
    public function testDeliveryDate()
    {
        $desadv = new Desadv();
        $desadv->setDeliveryDate($this->getDateTime());

        $this->assertEquals([
            'DTM',
            '11',
            '20180123'
        ], $desadv->getDeliveryDate());
    }


    /**
     * @throws EdifactException
     */
    public function testDeliveryNoteDate()
    {
        $desadv = new Desadv();
        $desadv->setDeliveryNoteDate($this->getDateTime());

        $this->assertEquals([
            'DTM',
            '137',
            '20180123'
        ], $desadv->getDeliveryNoteDate());
    }


    /**
     * @throws EdifactException
     */
    public function testShippingDate()
    {
        $desadv = new Desadv();
        $desadv->setShippingDate($this->getDateTime());

        $this->assertEquals([
            'DTM',
            '17',
            '20180123'
        ], $desadv->getShippingDate());
    }


    public function testContactPerson()
    {
        $desadv = new Desadv();
        $desadv->setContactPerson('John Doe');

        $this->assertEquals([
            'CTA',
            '',
            'John Doe'
        ], $desadv->getContactPerson());
    }


    public function testMailAddress()
    {
        $desadv = new Desadv();
        $desadv->setMailAddress('john.doe@company.com');

        $this->assertEquals([
            'COM',
            'john.doe@company.com',
            'EM'
        ], $desadv->getMailAddress());
    }


    public function testNameAndAddress()
    {
        $desadv = (new Desadv())
            ->setManufacturerAddress(
                'Name 1',
                'Name 2',
                'Name 3',
                'street',
                '99999',
                'city',
                'DE'
            );

        $this->assertEquals([
            'NAD',
            'SU',
            [
                '',
                '',
                'ZZZ'
            ],
            '',
            [
                'Name 1',
                'Name 2',
                'Name 3'
            ],
            [
                'street'
            ],
            [
                'city'
            ],
            [
                '',
            ],
            [
                '99999',
            ],
            [
                'DE',
            ],
        ], $desadv->getManufacturerAddress());


    }

    public function testDesadv()
    {
        $interchange = new Interchange(
            'UNB-Identifier-Sender',
            'UNB-Identifier-Receiver'
        );
        $interchange->setCharset('UNOC')
            ->setCharsetVersion('3');

        try {
            $desadv = (new Desadv())
                ->setSender('UNB-Identifier-Sender')
                ->setReceiver('GC-Gruppe')
                ->setDeliveryNoteNumber(Desadv::DELIVER_NOTE, 'LS123456789')
                ->setDeliveryNoteDate($this->getDateTime())
                ->setDeliveryDate($this->getDateTime())
                ->setShippingDate($this->getDateTime())
                ->setWholesalerAddress(
                    'Name 1',
                    'Name 2',
                    'Name 3',
                    'Street',
                    '99999',
                    'city',
                    'DE'
                )
                ->setContactPerson('John Doe')
                ->setMailAddress('john.doe@company.com')
                ->setPhoneNumber('+49123456789')
                ->setFaxNumber('+49123456789-11')
                ->setDeliveryAddress(
                    'Name 1',
                    'Name 2',
                    'Name 3',
                    'Street',
                    '99999',
                    'city',
                    'DE'
                );

            $item = new Desadv\Item();
            $item
                ->setPosition(
                    '1',
                    '8290123'
                )
                ->setQuantity('3')
            ->setOrderNumber('MyOrderNumber');
            $desadv->addItem($item);

            $desadv->compose();

            $encoder = new Encoder($interchange->addMessage($desadv)->getComposed(), true);
            $encoder->setUNA(":+,? '");

            $message = str_replace("'", "'\n", $encoder->get());
//            fwrite(STDOUT, "\n\nDESADV\n" . $message);

            $this->assertContains('UNT+15', $message);
            $this->assertContains('DTM+137', $message);
            $this->assertContains('DTM+11', $message);
            $this->assertContains('DTM+17', $message);
            $this->assertContains('CTA++', $message);
            $this->assertContains('COM+', $message);

        } catch (EdifactException $e) {

        }
    }

}