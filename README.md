The classes provided by this package give a fluent interface which simplifies the encoding of an UN/EDIFACT message.

The resulting array can be encoded in a valid message with EDI\Encoder class provided by [https://github.com/PHPEdifact/edifact](https://github.com/PHPEdifact/edifact).

Each message type extends a generic Message class which provides common helpers.

The example below creates a VERMAS message with a single container:

```php
$p = (new EDI\Generator\Interchange('ME', 'YOU'));

$v = (new EDI\Generator\Vermas('VERMAS', 'D', '16A', 'UN', '111', 'SMDG10'))
    ->setMessageSender('IC', '', 'JOHN DOE')
    ->setMessageSenderInformation('EM', 'TEST@EXAMPLE.COM');

$c = (new EDI\Generator\Vermas\Container())
    ->setContainer('CBHU1234567', '22G1')
    ->setBooking('4001234567', '1')
    ->setSeal('45545', 'CA')
    ->setMeasures('VGM', '1212')
    ->setWeighDate()
    ->setWeighMethod('SM1', 'DEFAULT')
    ->setShipper('MY COMPANY')
    ->setSpcContact('RP', 'JOHN DOE', 'EM', 'JOHN@EXAMPLE.COM');

$v = $v->addContainer($c);

$v = $v->compose(5);

$p = $p->addMessage($v)->getComposed();

echo (new EDI\Encoder($p, false))->get();
```
