==VERMAS==
Supports one or more containers per message.

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

==COPINO==
Only one container per message.

```php
$p = (new EDI\Generator\Interchange('ME', 'YOU'));
$copino = (new \EDI\Generator\Copino('COPINO', 'D', '95B', 'UN', null, 'ITG13'))
->setSenderAndReceiver('ME', 'YOU')
->setDTM('201204260000')
->setTransporter('12000051161000025', 8, '', 'TRUCKER CORP.', 'XA212345', 'JOHN DOE')
->setVessel('CARRIER', 'XNOE', 'NOE VESSEL')
->setContainer('CBHU1234567', '22G1', '4001234567', '1')
->setMeasures('G', 11000)
->setPort('ITGOA', 'VTE')
->setDestination('HKHKG');

$copino = $copino->compose(5);

$p = $p->addMessage($copino)->getComposed();

echo (new EDI\Encoder($p, false))->get();
```
