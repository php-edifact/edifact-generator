<?php

/**
 * Created by PhpStorm.
 * User: Sascha
 * Date: 18.01.2018
 * Time: 16:01
 */

namespace GeneratorTest;

use EDI\Encoder;
use EDI\Generator\EdifactException;
use EDI\Generator\Interchange;
use EDI\Generator\Ordrsp;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\RecursionContext\InvalidArgumentException;

/**
 * Class OrdrspTest
 * @package GeneratorTest
 */
class OrdrspTest extends TestCase {

    /**
     *
     */
    public function testOrdrsp() {
        $interchange = new Interchange(
            'UNB-Identifier-Sender',
            'UNB-Identifier-Receiver'
        );
        $interchange->setCharset('UNOC')
            ->setCharsetVersion('3');
        $ordrsp = new Ordrsp();

        try {
            $ordrsp->setOrderConfirmationNumber('AB1234567')
                ->setOrderConfirmationDate(new \DateTime())
                ->setDeliveryDate(new \DateTime())
                ->setOrderNumber('HERS1234567')
                ->setManufacturerAddress(
                    'Name 1',
                    'Name 2',
                    'Name 3',
                    'Street',
                    '99999',
                    'city',
                    'DE'
                )
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
                )
                ->setPositionSeparator()
                ->compose();
        } catch (EdifactException $e) {
        }

        $encoder = new Encoder($interchange->addMessage($ordrsp)->getComposed(), true);
        $encoder->setUNA(":+,? '");

        $message = str_replace("'", "'\n", $encoder->get());
        //        fwrite(STDOUT, "\n\nORDRSP\n" . $message);

        $this->assertStringContainsString('UNT+11', $message);
    }

    /**
     * 
     * @return void 
     * @throws InvalidArgumentException 
     * @throws ExpectationFailedException 
     */
    public function testNameAndAddress() {
        $ordrsp = new Ordrsp();
        $ordrsp->setDeliveryAddress(
            'name one that is longer than 35 characters',
            'name two that is longer than 35 characters',
            'name three that is longer than 35 characters',
            'street that is longer than 35 characters',
            'DE-1234567890',
            'city that is longer than 35 characters',
            'DE with more characters'
        );


        $deliveryAddress = $ordrsp->getDeliveryAddress();
        $expected = [
            'NAD',
            'ST',
            '',
            '',
            [
                'name one that is longer than 35 cha',
                'name two that is longer than 35 cha',
                'name three that is longer than 35 c',
            ],
            [
                'street that is longer than 35',
                'characters'
            ],
            [
                'city that is longer than 35',
                'characters'
            ],
            [
                ''
            ],
            [
                'DE-123456'
            ],
            [
                'DE'
            ]
        ];
        $this->assertEquals($expected, $deliveryAddress);
    }
}
