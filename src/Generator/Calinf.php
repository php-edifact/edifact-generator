<?php

namespace EDI\Generator;

class Calinf extends Message
{
    private $dtmSend;
    private $messageSender;
    private $messageReceiver;
    private $vessel;
    private $eta;
    private $etd;
    private $callsign;

    private $containers = [];

    /**
     * Construct.
     *
     * @param mixed  $sMessageReferenceNumber        (0062)
     * @param string $sMessageType                   (0065)
     * @param string $sMessageVersionNumber          (0052)
     * @param string $sMessageReleaseNumber          (0054)
     * @param string $sMessageControllingAgencyCoded (0051)
     * @param string $sAssociationAssignedCode       (0057)
     */
    public function __construct(
        $sMessageReferenceNumber = null,
        $sMessageType = 'CALINF',
        $sMessageVersionNumber = 'D',
        $sMessageReleaseNumber = '00B',
        $sMessageControllingAgencyCoded = 'UN',
        $sAssociationAssignedCode = 'SMDG20'
    ) {
        parent::__construct($sMessageType, $sMessageVersionNumber, $sMessageReleaseNumber,
            $sMessageControllingAgencyCoded, $sMessageReferenceNumber, $sAssociationAssignedCode);

        $this->dtmSend = self::dtmSegment(137, date('YmdHi'));
    }

    /*
     * Date of the message submission
     *
     */
    public function setDTMMessageSendingTime($dtm)
    {
        $this->dtmSend = self::dtmSegment(137, $dtm);

        return $this;
    }

    /*
     * Message sender (usually the vessel agent)
     *
     */
    public function setSender($code, $name)
    {
        $this->messageSender = ['NAD', 'MS', $code, $name];

        return $this;
    }

    /*
     * Message receiver (usually the terminal)
     *
     */
    public function setReceiver($code, $name)
    {
        $this->messageReceiver = ['NAD', 'MR', $code, $name];

        return $this;
    }

    /*
     * Vessel call information
     *
     */
    public function setVessel($extVoyage, $line, $imoNumber, $vslName, $callsign)
    {
        $this->vessel = ['TDT', 20, $extVoyage, '', '', [$line, 172, 20], '', '', [$imoNumber, 146, 54, $vslName]];
        $this->callsign = self::rffSegment('VM', $callsign);

        return $this;
    }

    /*
     * Estimated Time of Arrival
     *
     */
    public function setEta($dtm)
    {
        $this->eta = self::dtmSegment(132, $dtm);

        return $this;
    }

    /*
     * Estimated Time of Departure
     *
     */
    public function setEtd($dtm)
    {
        $this->etd = self::dtmSegment(133, $dtm);

        return $this;
    }

    /**
     * Compose.
     *
     * @param mixed $sMessageFunctionCode (1225)
     * @param mixed $sDocumentNameCode    (1001)
     * @param mixed $sDocumentIdentifier  (1004)
     *
     * @return parent::compose()
     */
    public function compose(?string $sMessageFunctionCode = null, ?string $sDocumentNameCode = null, ?string $sDocumentIdentifier = null): parent
    {
        $this->messageContent = [
            ['BGM', $sDocumentNameCode, $this->messageID, $sMessageFunctionCode],
        ];

        $this->messageContent[] = $this->dtmSend;
        $this->messageContent[] = $this->messageSender;
        $this->messageContent[] = $this->messageReceiver;
        $this->messageContent[] = $this->vessel;
        $this->messageContent[] = $this->eta;
        $this->messageContent[] = $this->etd;
        $this->messageContent[] = $this->callsign;

        return parent::compose($sMessageFunctionCode, $sDocumentNameCode, $sDocumentIdentifier);
    }
}
