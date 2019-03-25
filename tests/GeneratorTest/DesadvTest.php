<?php

namespace GeneratorTest;

use EDI\Encoder;
use EDI\Generator\Desadv;
use EDI\Generator\EdifactException;
use EDI\Generator\Interchange;
use PHPUnit\Framework\TestCase;

/**
 * Class DesadvTest
 * @package GeneratorTest
 */
final class DesadvTest extends TestCase
{
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

    /**
     * @return \DateTime|false
     * @throws \Exception
     */
    private function getDateTime()
    {
        return (new \DateTime())
            ->setDate(2018, 1, 23)
            ->setTime(10, 0, 0);
    }

    public function testDeliverNoteNumberException()
    {
        $this->expectExceptionMessage('value: XXX is not in allowed values:  [22E, 270, 351] in EDI\Generator\Desadv->setDeliveryNoteNumber');
        (new Desadv())
            ->setDeliveryNoteNumber('XXX', 'LS123456789');
    }


    public function testNameAndAddress()
    {
        $this->assertEquals(
            'NAD+SU+partnerId::9++name1:name2:name3+street+city++zipCode+DE\'',
            (new Encoder([
                (new Desadv())->addNameAndAddress(
                    'name1',
                    'name2',
                    'name3',
                    'street',
                    'zipCode',
                    'city',
                    'DE',
                    '9',
                    'SU',
                    'partnerId'
                )]))->get()
        );



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
        $interchange->setCharset('UNOC', '3');

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
                ->setOrderNumberWholesaler('MyOrderNumber')
            ;
            $desadv->addItem($item);
            $desadv->compose();
            $encoder = new Encoder($interchange->addMessage($desadv)->getComposed(), true);
            $encoder->setUNA(":+,? '");

            $message = str_replace("'", "'\n", $encoder->get());
            //fwrite(STDOUT, "\n\nDESADV\n" . $message);

            $this->assertStringContainsString('UNT+15', $message);
            $this->assertStringContainsString('DTM+137', $message);
            $this->assertStringContainsString('DTM+11', $message);
            $this->assertStringContainsString('DTM+17', $message);
            $this->assertStringContainsString('CTA++', $message);
            $this->assertStringContainsString('COM+', $message);
        } catch (EdifactException $e) {
            fwrite(STDOUT, "\n\nDESADV\n" . $e->getMessage());
        }
    }
}
