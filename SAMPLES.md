VERMAS
------
Verified gross mass transmission. Supports one or more containers per message.

```php
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

echo (new \EDI\Encoder($aComposed, false))->get();
```

```
UNB+UNOA:2+ME+YOU+190130:2039+I5C51FD6BE2EEA'
UNH+M5C51FD6BE3109+VERMAS:D:16A:UN:SMDG10'
BGM+749+M5C51FD6BE3109+5'
DTM+137:201901302039:203'
NAD+TB'
CTA+IC+:JOHN DOE'
COM+TEST@EXAMPLE.COM:EM'
EQD+CN+CBHU1234567+22G1:6346:306'
RFF+BN:4001234567'
RFF+SQ:1'
SEL+45545:CA'
MEA+AAE+VGM+KGM:1212'
DTM+798:201901302039:203'
DOC+SM1:VGM:306+DEFAULT'
NAD+SPC+++MY COMPANY'
CTA+RP+:JOHN DOE'
COM+JOHN@EXAMPLE.COM:EM'
UNT+17+M5C51FD6BE3109'
UNZ+1+I5C51FD6BE2EEA'
```
COPINO
------
Transportation order. Only one container per message.

```php
$oInterchange = (new \EDI\Generator\Interchange('ME', 'YOU'));

$oCopino = (new \EDI\Generator\Copino())
    ->setSenderAndReceiver('ME', 'YOU')
    ->setDTM('201204260000')
    ->setTransporter('12000051161000025', 8, '', 'TRUCKER CORP.', 'XA212345', 'JOHN DOE')
    ->setVessel('CARRIER', 'XNOE', 'NOE VESSEL')
    ->setContainer('CBHU1234567', '22G1', '4001234567', '1')
    ->setMeasures('G', 11000)
    ->setPort('ITGOA', 'VTE')
    ->setDestination('HKHKG');

$oCopino = $oCopino->compose(9, 661);

$aComposed = $oInterchange->addMessage($oCopino)->getComposed();

echo (new \EDI\Encoder($aComposed, false))->get();
```

```
UNB+UNOA:2+ME+YOU+190130:2046+I5C51FF1C8CD34'
UNH+M5C51FF1C8CF9A+COPINO:D:95B:UN:ITG13'
BGM+661+M5C51FF1C8CF9A+9'
RFF+XXX:1'
TDT+1+12000051161000025+8++TRUCKER CORP.+++XA212345:146::JOHN DOE'
LOC+88+ITGOA:139:6+VTE:72:306'
DTM+132:201204260000:203'
NAD+MS+ME'
NAD+MR+YOU'
GID+1'
SGP+CBHU1234567'
EQD+CN+CBHU1234567+22G1:102:5++2+5'
RFF+BN:4001234567'
RFF+SQ:1'
MEA+AAE+G+KGM:11000'
TDT+20++1+13+CARRIER+++XNOE:103::NOE VESSEL'
LOC+7+HKHKG:139:6'
CNT+16:1'
UNT+18+M5C51FF1C8CF9A'
UNZ+1+I5C51FF1C8CD34'
```

COPARN
------
Container announcement. One container per message. This example shows a full acceptance order sent to the terminal (documentType = 126).

```php
$oInterchange = (new \EDI\Generator\Interchange('ME', 'YOU'));

$oCoparn = (new \EDI\Generator\Coparn())
    ->setBooking('400123456', '0001')
    ->setRFFOrder('TEMPORDER')
    ->setVessel('0002W', 'COS', 'NOE VESSEL', 'XNOE')
    ->setETA('201701210000')
    ->setETD('201701210000')
    ->setPOL('ITGOA')
    ->setPOD('HKHKG')
    ->setFND('HKHKG')
    ->setCarrier('COS')
    ->setContainer('CBHU1234567', '22G1')
    ->setVGM('11495.14', '201701210000')
    ->setTemperature('14.3')
    ->setDangerous(3, 1366)
    ->setOverDimensions(0, 0, 0, 0, 7.5)
    ->setCargoCategory('GENERAL CARGO')
;

$oCoparn = $oCoparn->compose(5, 126);

$aComposed = $oInterchange->addMessage($oCoparn)->getComposed();

echo (new \EDI\Encoder($aComposed, false))->get();
```

```
UNB+UNOA:2+ME+YOU+190130:2054+I5C5201079DADD'
UNH+M5C5201079DE48+COPARN:D:00B:UN:SMDG20'
BGM+126+M5C5201079DE48+5+AB'
DTM+137:201901302054:203'
RFF+ATX:TEMPORDER'
RFF+BN:400123456'
TDT+20+0002W+++COS:172:20+++XNOE:146:11:NOE VESSEL'
RFF+VM:XNOE'
LOC+9+ITGOA:139:6'
DTM+132:201701210000:203'
DTM+133:201701210000:203'
NAD+MS+COS:160:ZZZ'
NAD+CF+COS:160:166'
EQD+CN+CBHU1234567+22G1:102:5++2+5'
RFF+SQ:0001'
TMD+3'
DTM+798:201701210000:203'
LOC+7+HKHKG:139:6'
LOC+9+ITGOA:139:6'
LOC+11+HKHKG:139:6'
MEA+AAE+VGM+KGM:11495.14'
DIM+5+CMT:0'
DIM+6+CMT:0'
DIM+7+CMT::0'
DIM+8+CMT::0'
DIM+13+CMT:::7.5'
TMP+2+14.3:CEL'
FTX+AAA+++GENERAL CARGO'
DGS+IMD+3+1366'
TDT+1++3'
CNT+16:1'
UNT+31+M5C5201079DE48'
UNZ+1+I5C5201079DADD'
```


CODECO
------
Container move report. Multiple containers per message. Each message can be for gate in or for gate out.

```php
$oInterchange = (new \EDI\Generator\Interchange('ME', 'YOU'));

$oCodeco = (new \EDI\Generator\Codeco())
    ->setSenderAndReceiver('ITPIALOMA', 'COSCOS')
    ->setCarrier('COS')
;

$oContainer = (new \EDI\Generator\Codeco\Container())
    ->setContainer('CBHU1234567', '22G1', 2, 5)
    ->setBooking('4006531400')
    ->setEffectiveDate('201701020800')
    ->setSeal('1234567', 'CA')
    ->setModeOfTransport(3, 31)
    ->setWeight('G', 15400)
;

$oCodeco = $oCodeco->addContainer($oContainer);

$oCodeco = $oCodeco->compose(5, 34);

$aComposed = $oInterchange->addMessage($oCodeco)->getComposed();

echo (new \EDI\Encoder($aComposed, false))->get();
```

```
UNB+UNOA:2+ME+YOU+190130:2101+I5C520285973E2'
UNH+M5C52028597E23+CODECO:D:95B:UN'
BGM+34+M5C52028597E23+5'
NAD+MS+ITPIALOMA'
NAD+MR+COSCOS'
NAD+CF+COS:160:166'
EQD+CN+CBHU1234567+22G1:6346:306++2+5'
RFF+BN:4006531400'
DTM+7:201701020800:203'
MEA+AAE+G+KGM:15400'
SEL+1234567:CA'
TDT+1++3+31'
CNT+16:1'
UNT+13+M5C52028597E23'
UNZ+1+I5C520285973E2'
```

COPRAR
------
Container load or discharge order.  Multiple containers per message. The example is a loading order.

```php
$oInterchange = (new \EDI\Generator\Interchange('ME', 'YOU'));

$oCoprar = (new \EDI\Generator\Coprar())
    ->setVessel('0002W', 'COS', 'NOE VESSEL', 'XNOE')
    ->setPort(9, 'ITGOA')
    ->setETA('201701210000')
    ->setETD('201701210000')
    ->setCarrier('COS')
;

$oContainer = (new \EDI\Generator\Coprar\Container())
    ->setContainer('CBHU1234567', '22G1', 2, 5)
    ->setBooking('4006531400')
    ->setPOD('HKHKG')->setFND('HKHKG')
    ->setVGM('11495.14', '201701210000')
    ->setTemperature('14.3')
    ->setDangerous(3, 1366)
    ->setOverDimensions(0, 0, 0, 0, 7.5)
    ->setCargoCategory('GENERAL CARGO')
    ->setContainerOperator('COS')
;

$oCoprar = $oCoprar->addContainer($oContainer);

$oCoprar = $oCoprar->compose(5, 45);

$aComposed = $oInterchange->addMessage($oCoprar)->getComposed();

echo (new \EDI\Encoder($aComposed, false))->get();
```

```
UNB+UNOA:2+ME+YOU+190130:2107+I5C5204015D145'
UNH+M5C5204015D3A1+COPRAR:D:95B:UN:SMDG16'
BGM+45+M5C5204015D3A1+5'
RFF+XXX:1'
TDT+20+0002W+1++COS:172:20+++XNOE:103::NOE VESSEL'
LOC+9+ITGOA:139:6'
DTM+132:201701210000:203'
DTM+133:201701210000:203'
NAD+CA+COS:160:20'
EQD+CN+CBHU1234567+22G1:102:5++2+5'
RFF+BN:4006531400'
DTM+798:201701210000:203'
LOC+11+HKHKG:139:6'
LOC+7+HKHKG:139:6'
MEA+AAE+VGM+KGM:11495.14'
DIM+5+CMT:0'
DIM+6+CMT:0'
DIM+7+CMT::0'
DIM+8+CMT::0'
DIM+13+CMT:::7.5'
TMP+2+14.3:CEL'
FTX+AAA+++GENERAL CARGO'
DGS+IMD+3+1366'
NAD+CF+COS:160:20'
CNT+16:1'
UNT+25+M5C5204015D3A1'
UNZ+1+I5C5204015D145'
```

WESTIM
------
Container MNR message (ISO EDI, not UN/EDIFACT). One container per message.

```php
$oInterchange = (new \EDI\Generator\Interchange('IT888XXXX', 'CARRIER'));

$oWestim = (new \EDI\Generator\Westim('ESTNUMBER'))
    ->setTransactionDate('170702')->setCurrency('EUR')->setLabourRate('100.00')
    ->setPartners('IT888XXXX', 'CARRIER')
    ->setContainer('CBHU', '1234567', '4510')
    ->setFullEmpty('E')
    ->setCostTotals('O', '4.5', '82.63', '0', '0', '87.13')
    ->setTotalMessageAmounts('87.13')
;

$oDamage = (new \EDI\Generator\Westim\Damage())
    ->setDamage('01', 'IXXX', 'TFA', 'DB', 'SK')
    ->setWork('SC', '', '0', '1', '', '1')
    ->setCost('0', '82.63', 'O', '18.00')
;

$oWestim->addDamage($oDamage);

$oWestim = $oWestim->compose();

$aComposed = $oInterchange->addMessage($oWestim)->getComposed();

echo (new \EDI\Encoder($aComposed, false))->get();
```

```
UNB+UNOA:2+IT888XXXX+CARRIER+190130:2121+I5C520753A0FDB'
UNH+ESTNUMBER+WESTIM:0'
DTM+ATR+170702'
RFF+EST+ESTNUMBER+170702'
ACA+EUR+STD:0'
LBR+100.00'
NAD+LED+CARRIER'
NAD+DED+IT888XXXX'
EQF+CON+CBHU:1234567+4510+MGW:0:KGM'
CUI+++E'
ECI+D'
DAM+01+IXXX+TFA+DB+SK'
WOR+SC+:0:1:+1'
COS+0+0+82.63+O+18.00+N'
CTO+O+4.5+82.63+0+0+87.13'
TMA+87.13'
UNT+16+ESTNUMBER'
UNZ+1+I5C520753A0FDB'
```

COHAOR
------
Container special handling order message

```php
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

echo (new \EDI\Encoder($aComposed, false))->get();
```

```
UNB+UNOA:2+ME+YOU+190130:2015+I5C51F7D31CFF8'
UNH+ROW00000000001+COHAOR:D:17B:UN:ITG12'
BGM+293+5c51f7d31e599+9'
NAD++My Party+My Company:My Address:1234 AB+My City+123456+NL'
EQD+AM+123456+1234::5:'
DTM+7:201812031015:203'
LOC+9+NLRTM'
FTX+AAA+++Bananas'
MEA+AAE+AAO+PER:95.00'
TMP+SET+13.00:CEL'
RNG+5+CEL:10.00:15.00'
CNT+16:1'
UNT+12+ROW00000000001'
UNZ+1+I5C51F7D31CFF8'
```


ORDERS
------
Purchase order. 

```php
$interchange = new \EDI\Generator\Interchange('UNB-Identifier-Sender','UNB-Identifier-Receiver');
$interchange->setCharset('UNOC', '3');

$orders = new \EDI\Generator\Orders();
$orders
    ->setOrderNumber('AB76104')
    ->setContactPerson('John Doe')
    ->setMailAddress('john.doe@company.com')
    ->setPhoneNumber('+49123456789')
    ->setDeliveryDate(new \DateTime())
    ->setDeliveryAddress(
        'Name 1',
        'Name 2',
        'Name 3',
        'Street',
        '99999',
        'city',
        'DE'
    )
    ->setDeliveryTerms('CAF');

// adding order items
$item = new \EDI\Generator\Orders\Item();
$item->setPosition('1', '8290123', 'EN')->setQuantity(3);
$orders->addItem($item);

$item = new \EDI\Generator\Orders\Item();
$item->setPosition('2', 'AB992233', 'EN')->setQuantity(1);
$orders->addItem($item);

$orders->compose();

$encoder = new \EDI\Encoder($interchange->addMessage($orders)->getComposed(), true);
$encoder->setUNA(":+,? '");
echo $encoder->get();
```

```
UNB+UNOC:3+UNB-Identifier-Sender+UNB-Identifier-Receiver+190325:1242+I5C98CCC536C91'
UNH+M5C98CCC536CA2+ORDERS:D:96B:UN:ITEK35'
BGM+120+AB76104+9'
DTM+2:201903251242:203'
CTA++:John Doe'
COM+john.doe@company.com:EM'
COM+?+49123456789:TE'
NAD+ST+::ZZZ++Name 1:Name 2:Name 3+Street+city++99999+DE'
TOD+6++CAF'
LIN+1++8290123:EN'
QTY+12:3:PCE'
LIN+2++AB992233:EN'
QTY+12:1:PCE'
UNS+S'
CNT+2+2'
UNT+15+M5C98CCC536CA2'
UNZ+1+I5C98CCC536C91'
```