<?php

namespace GeneratorTest;

use PHPUnit\Framework\TestCase;

/**
 * Class SamplesTest
 * @package GeneratorTest
 */
class SamplesTest extends TestCase
{
    public function testVermas() {
        $oInterchange = (new \EDI\Generator\Interchange('ME', 'YOU'));

        $oVermas = (new \EDI\Generator\Vermas())
            ->setMessageSender('IC', '', 'JOHN DOE')
            ->setMessageSenderInformation('EM', 'TEST@EXAMPLE.COM');

        $oContainer = (new \EDI\Generator\Vermas\Container())
            ->setContainer('CBHU1234567', '22G1')
            ->setBooking('4001234567', '1')
            ->setSeal('45545', 'CA')
            ->setMeasures('VGM', '1212')
            ->setWeighDate()
            ->setWeighMethod('SM1', 'DEFAULT')
            ->setShipper('MY COMPANY')
            ->setSpcContact('RP', 'JOHN DOE', 'EM', 'JOHN@EXAMPLE.COM');

        $oVermas = $oVermas->addContainer($oContainer);

        $oVermas = $oVermas->compose(5, 749);

        $aComposed = $oInterchange->addMessage($oVermas)->getComposed();

        $result = (new \EDI\Encoder($aComposed, false))->get();

        self::assertStringContainsString('UNT+17', $result);
        self::assertStringContainsString('EQD+CN+CBHU1234567+22G1:6346:306', $result);
    }

    public function testCustomMessage() {
        $oInterchange = (new \EDI\Generator\Interchange('ME', 'YOU'))
                ->setCharset('UNOC', 2);

        $oBase = (new \EDI\Generator\Message('IFTMIN', 'D', '04A', 'UN'));

        $bgm = (new \EDI\Generator\Segment\BeginningOfMessage())
            ->setDocument('99')
            ->setDocumentIdentification($oBase->getMessageID())
            ->setMessageFunctionCode('9');

        $oBase->addSegment($bgm);

        $dtm = (new \EDI\Generator\Segment\DateTimePeriod())
            ->setDateOrTimeOrPeriodFunctionCodeQualifier('137')
            ->setDateOrTimeOrPeriodText(date('YmdHi'))
            ->setDateOrTimeOrPeriodFormatCode('203');

        $oBase->addSegment($dtm);

        $oBase = $oBase->compose();
        $aComposed = $oInterchange->addMessage($oBase)->getComposed();
        //
        $this->assertCount(6, $aComposed);

        $msgs = $oInterchange->getMessages();
        $this->assertCount(1, $msgs);
        $this->assertInstanceOf(\EDI\Generator\Message::class, $msgs[0]);

        $result = (new \EDI\Encoder($aComposed, false))->get();

        self::assertStringContainsString('UNT+4', $result);
        self::assertStringContainsString('IFTMIN:D:04A:UN', $result);
    }
}
