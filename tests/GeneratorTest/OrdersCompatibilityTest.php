<?php

namespace GeneratorTest;

use EDI\Encoder;
use EDI\Generator\Interchange;
use EDI\Generator\Orders;
use PHPUnit\Framework\TestCase;

class OrdersCompatibilityTest extends TestCase
{
    public function test_custom_identifier_and_eancom_custom_order_number_are_composed(): void
    {
        $orders = (new Orders())
            ->setCustomOrderNumber('ORDER-1001', '31C')
            ->setCustomIdentifier('ORDER-1001');

        $item = (new Orders\Item())
            ->setPosition('1', '1234567890123', 'EN')
            ->setQuantity('1');

        $orders->addItem($item);
        $orders->compose();

        $message = $this->encodeOrders($orders);

        self::assertStringContainsString("BGM+31C::28+ORDER-1001+9'", $message);
        self::assertStringContainsString("RFF+ON:ORDER-1001'", $message);
    }

    public function test_numeric_custom_order_number_stays_scalar_by_default(): void
    {
        $orders = (new Orders())
            ->setCustomOrderNumber('ORDER-220', '220');

        $item = (new Orders\Item())
            ->setPosition('1', '1234567890123', 'EN')
            ->setQuantity('1');

        $orders->addItem($item);
        $orders->compose();

        $message = $this->encodeOrders($orders);

        self::assertStringContainsString("BGM+220+ORDER-220+9'", $message);
        self::assertStringNotContainsString("BGM+220::28+ORDER-220+9'", $message);
    }

    public function test_item_compatibility_segments_are_emitted_in_expected_shape(): void
    {
        $orders = (new Orders())
            ->setCustomOrderNumber('ORDER-2001', '31C')
            ->setCustomIdentifier('ORDER-2001');

        $item = (new Orders\Item())
            ->setPosition('1', '1234567890123', 'EN')
            ->setQuantity('2', 'PCE', 1)
            ->setQli('QLI-REFERENCE')
            ->setOurPrice('10.00')
            ->addInformation('050', 'Sample Item')
            ->addGir(1, 'LOC', 'FUND', '10.00', 'COL')
            ->addGir(2, 'LOC2', 'FUND2', '12.34', null);

        $orders->addItem($item);
        $orders->compose();

        $message = $this->encodeOrders($orders);

        self::assertStringContainsString("QTY+1:2'", $message);
        self::assertStringNotContainsString("QTY+1:2:PCE'", $message);
        self::assertStringContainsString("RFF+QLI:QLI-REFERENCE'", $message);
        self::assertStringContainsString("PRI+AAE:10.00'", $message);
        self::assertStringContainsString("IMD+L+050+:::Sample Item'", $message);
        self::assertStringContainsString("GIR+001+LOC:LLO+COL:LSQ+FUND:LFN+10.00:LCV'", $message);
        self::assertStringContainsString("GIR+002+LOC2:LLO+:LSQ+FUND2:LFN+12.34:LCV'", $message);
    }


    public function test_interchange_uses_first_message_identifier_as_default_application_reference(): void
    {
        $orders = new Orders('MSG-1', 'QUOTES', 'D', '96A', 'UN', 'EAN002');
        $orders->setCustomOrderNumber('ORDER-3001', '31C');

        $item = (new Orders\Item())
            ->setPosition('1', '1234567890123', 'EN')
            ->setQuantity('1');

        $orders->addItem($item);
        $orders->compose();

        $message = $this->encodeOrders($orders);

        self::assertStringContainsString('+QUOTES++++\'UNH+', $message);
    }

    public function test_nad_segments_trim_trailing_empty_components_when_only_identifier_is_provided(): void
    {
        $orders = (new Orders())
            ->setCustomOrderNumber('ORDER-4001', '31C')
            ->setBuyerAddress(null, '', '', '', '', '', null, '9', 'BUYER')
            ->setSupplierAddress(null, '', '', '', '', '', null, '9', 'SUPPLIER');

        $item = (new Orders\Item())
            ->setPosition('1', '1234567890123', 'EN')
            ->setQuantity('1');

        $orders->addItem($item);
        $orders->compose();

        $message = $this->encodeOrders($orders);

        self::assertStringContainsString("NAD+BY+BUYER::9'", $message);
        self::assertStringContainsString("NAD+SU+SUPPLIER::9'", $message);
        self::assertStringNotContainsString("NAD+BY+BUYER::9+++++++", $message);
        self::assertStringNotContainsString("NAD+SU+SUPPLIER::9+++++++", $message);
    }
    private function encodeOrders(Orders $orders): string
    {
        $interchange = (new Interchange('SENDER', 'RECEIVER'))->setCharset('UNOC', '3');

        $encoder = new Encoder($interchange->addMessage($orders)->getComposed(), true);
        $encoder->setUNA(":+,? '");

        return $encoder->get();
    }
}
