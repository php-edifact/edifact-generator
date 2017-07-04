VERMAS
------
Verified gross mass transmission. Supports one or more containers per message.
```php
$p = (new EDI\Generator\Interchange('ME', 'YOU'));

$v = (new EDI\Generator\Vermas())
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
COPINO
------
Transportation order. Only one container per message.
```php
$p = (new EDI\Generator\Interchange('ME', 'YOU'));
$copino = (new \EDI\Generator\Copino())
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
COPARN
------
Container announcement. One container per message. This example shows a full acceptance order sent to the terminal (documentType = 126).
```php
$inc = (new EDI\Generator\Interchange('ME', 'YOU'));
$v = (new EDI\Generator\Coparn());

$v->setBooking('400123456', '0001')
  ->setRFFOrder('TEMPORDER');
$v->setVessel('0002W', 'COS', 'NOE VESSEL', 'XNOE');
$v->setETA('201701210000')
    ->setETD('201701210000')
    ->setPOL('ITGOA')->setPOD('HKHKG')->setFND('HKHKG')->setCarrier('COS');

$v->setContainer('CBHU1234567', '22G1');

$v->setVGM('11495.14', '201701210000');
$v->setTemperature('14.3');
$v->setDangerous(3, 1366);
$v->setOverDimensions(0, 0, 0, 0, 7.5);

$v->setCargoCategory('GENERAL CARGO');
$v = $v->compose(9);
$inc = $inc->addMessage($v)->getComposed();

$incText = (new EDI\Encoder($inc, false))->get();
```
CODECO
------
Container move report. Multiple containers per message. Each message can be for gate in or for gate out.
```php
$inc = (new EDI\Generator\Interchange('ME', 'YOU'));
$v = (new EDI\Generator\Codeco());

$v->setSenderAndReceiver('ITPIALOMA', 'COSCOS');
$v->setCarrier('COS');

$c = (new EDI\Generator\Codeco\Container());
$c->setContainer('CBHU1234567', '22G1', 2, 5);
$c->setBooking('4006531400');
$c->setEffectiveDate('201701020800');
$c->setSeal('1234567', 'CA');
$c->setModeOfTransport(3, 31);
$c->setWeight('G', 15400);

$v = $v->addContainer($c);

$v = $v->compose(9);
$inc = $inc->addMessage($v)->getComposed();

$incText = (new EDI\Encoder($inc, false))->get();
```
COPRAR
------
Container load or discharge order.  Multiple containers per message. The example is a loading order.
```php
$p = (new EDI\Generator\Interchange('ME', 'YOU'));

$v = (new EDI\Generator\Coprar());
$v->setVessel('0002W', 'COS', 'NOE VESSEL', 'XNOE');
$v->setPort(9, 'ITGOA');
$v->setETA('201701210000')
    ->setETD('201701210000');
$v->setCarrier('COS');

$c= (new EDI\Generator\Coprar\Container());
$c->setContainer('CBHU1234567', '22G1', 2, 5);
$c->setBooking('4006531400');
$c->setPOD('HKHKG')->setFND('HKHKG');

$c->setVGM('11495.14', '201701210000');
$c->setTemperature('14.3');
$c->setDangerous(3, 1366);
$c->setOverDimensions(0, 0, 0, 0, 7.5);

$c->setCargoCategory('GENERAL CARGO');
$c->setContainerOperator('COS');

$v = $v->addContainer($c);

$v = $v->compose(5, 45);

$inc = $p->addMessage($v)->getComposed();
$incText = (new EDI\Encoder($inc, false))->get();
```
WESTIM
------
Container MNR message (ISO EDI, not UN/EDIFACT). One container per message.
```php
$p = (new EDI\Generator\Interchange('IT888XXXX', 'CARRIER'));

$v = (new EDI\Generator\Westim('ESTNUMBER'));

$v->setTransactionDate('170702')->setCurrency('EUR')->setLabourRate('100.00');
$v->setPartners('IT888XXXX', 'CARRIER');
$v->setContainer('CBHU', '1234567', '4510');
$v->setFullEmpty('E');

$d = (new EDI\Generator\Westim\Damage());
$d->setDamage('01', 'IXXX', 'TFA', 'DB', 'SK');
$d->setWork('SC', '', '0', '1', '', '1');
$d->setCost('0', '82.63', 'O', '18.00');
$v->addDamage($d);

$v->setCostTotals('O', '4.5', '82.63', '0', '0', '87.13');

$v->setTotalMessageAmounts('87.13');

$v = $v->compose();

$inc = $p->addMessage($v)->getComposed();
$incText = (new EDI\Encoder($inc, false))->get();
```