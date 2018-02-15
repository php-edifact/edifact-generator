<?php

use EDI\Encoder;
use EDI\Generator\Interchange;
use EDI\Generator\Invoic\Item;

$interchange = new Interchange(
    'UNB-Identifier-Sender',
    'UNB-Identifier-Receiver'
);
$interchange->setCharset('UNOC')
    ->setCharsetVersion('3');

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
$item = new Item();
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
$item->addDiscount(-20.34, Item::DISCOUNT_TYPE_ABSOLUTE);
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

$message = $encoder->get();