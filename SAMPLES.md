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

COHAOR
------
Container special handling order message

```
$oInterchange = (new \EDI\Generator\Interchange('ME', 'YOU'));

$sMessageReferenceNumber = 'ROW' . str_pad(1, 11, 0, STR_PAD_LEFT);

$oCohaor = (new \EDI\Generator\Cohaor($sMessageReferenceNumber));

// Segment Group 2

$aSegments = [];

// Segment Group 2 : Name And Address

$oNameAndAddress = (new \EDI\Generator\Segment\NameAndAddress())
    ->setPartyFunctionCodeQualifier('')
    ->setPartyIdentificationDetails('My Party')
    ->setNameAndAddress([
        'My Company', // line 1 .. 5
        'My Address', // line 2 .. 5
        '1234 AB' // line 3 .. 5
    ])
    ->setCityName('My City')
    ->setPostalIdentificationCode('123456')
    ->setCountryIdentifier('NL')
    ->compose();

$aSegments[] = $oNameAndAddress->getComposed();

// Segment Group 2

$oCohaor->addSegmentGroup(2, $aSegments);

// Segment Group 4

$aSegments = [];

// Segment Group 4 : Equipment Details

$oEquipmentDetails = (new \EDI\Generator\Segment\EquipmentDetails())
    ->setEquipmentTypeCodeQualifier('AM') // Refrigerated Container
    ->setEquipmentIdentification('123456')
    ->setEquipmentSizeAndType('1234', '', 5, '')
    ->compose()
;

$aSegments[] = $oEquipmentDetails->getComposed();

// Segment Group 4 : Date Time Period

$oDateTimePeriod = (new \EDI\Generator\Segment\DateTimePeriod())
    ->setDateOrTimeOrPeriodFunctionCodeQualifier(7) // Effective from date/time
    ->setDateOrTimeOrPeriodText('201812031015')
    ->setDateOrTimeOrPeriodFormatCode(203)// CCYYMMDDHHMM
    ->compose();

$aSegments[] = $oDateTimePeriod->getComposed();

// Segment Group 4 : Place Location Identification

$oPlaceLocationIdentification = (new \EDI\Generator\Segment\PlaceLocationIdentification())
    ->setLocationFunctionCodeQualifier('9') // Place of loading
    ->setLocationIdentification('NLRTM') // Rotterdam
    ->compose();

$aSegments[] = $oPlaceLocationIdentification->getComposed();

// Segment Group 4 : Free Text

$oFreeText1 = (new \EDI\Generator\Segment\FreeText())
    ->setTextSubjectCodeQualifier('AAA') // Good Description
    ->setFreeTextFunctionCode('')
    ->setTextReference('')
    ->setTextLiteral(['Bananas']) // Commodity
    ->compose();

$aSegments[] = $oFreeText1->getComposed();

// Segment Group 4 : Measurements

$oMeasurements = (new \EDI\Generator\Segment\Measurements())
    ->setMeasurementPurposeCodeQualifier('AAE') // Measurement
    ->setMeasurementDetails('AAO') // Humidity
    ->setValueRange('PER', '95.00')
    ->compose()
;

$aSegments[] = $oMeasurements->getComposed();

// Segment Group 4

$oCohaor->addSegmentGroup(4, $aSegments);

// Segment Group 11

$aSegments = [];

// Segment Group 11 : Temperature

$oTemperature = (new \EDI\Generator\Segment\Temperature())
    ->setTemperatureTypeCodeQualifier('SET')
    ->setTemperatureSetting('13.00', 'CEL')
    ->compose();

$aSegments[] = $oTemperature->getComposed();

// Segment Group 11 : Range Details

$oRangeDetails = (new \EDI\Generator\Segment\RangeDetails())
    ->setRangeTypeCodeQualifier('5') // Temperature range
    ->setMeasurementUnitCode('CEL')
    ->setRangeMinimumQuantity('10.00')
    ->setRangeMaximumQuantity('15.00')
    ->compose();

$aSegments[] = $oRangeDetails->getComposed();

// Segment Group 11 : Control Total

$oControlTotal = (new \EDI\Generator\Segment\ControlTotal())
    ->setControlTotalTypeCodeQualifier('16')
    ->setControlTotalQuantity('1')
    ->compose()
;

$aSegments[] = $oControlTotal->getComposed();

// Segment Group 11

$oCohaor->addSegmentGroup(11, $aSegments);

$sDocumentIdentifier = uniqid(); // Your unique identifier

$oCohaor->compose(9, 293, $sDocumentIdentifier);

$aComposed = $oInterchange->addMessage($oCohaor)->getComposed();

$sComposed = (new \EDI\Encoder($aComposed, false))->get();
```

```
UNB+UNOA:2+ME+YOU+181203:1021+I5C04F58A8CA7C'
UNH+ROW00000000001+COHAOR:D:17B:UN:ITG12'
BGM+293+5c04f58a8e778+9'
NAD++My Party+My Company:My Address:1234 AB+++My City++123456+NL'
EQD+AM+123456+1234::5:'
DTM+7:201812031015:203'
LOC+9+NLRTM'
FTX+AAA+++Bananas'
MEA+AAE+AAO+PER:95.00'
TMP+SET+13.00:CEL'
RNG+5+CEL:10.00:15.00'
CNT+16:1'
UNT+12+ROW00000000001'
UNZ+1+I5C04F58A8CA7C'
```
