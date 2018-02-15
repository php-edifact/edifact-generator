The classes provided by this package give a fluent interface which simplifies the encoding of an EDI (mainly UN/EDIFACT) message.

The resulting array can be encoded in a valid message with EDI\Encoder class provided by [https://github.com/PHPEdifact/edifact](https://github.com/PHPEdifact/edifact).

Each message type extends a generic Message class which provides common helpers.

See [SAMPLES.md](SAMPLES.md) for code examples using this library.




Generator for ediFACT messages
=

Message types
-

- DESADV
- ORDERS
- ORDRSP
- INVOIC
- CALINF
- CODECO
- COPARN
- COPINO
- COPRAR
- VERMAS
- WESTIM


Messages can be generated in object style





```
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
                ->setTax(19, 19.11)
;


            $invoice->compose();
```