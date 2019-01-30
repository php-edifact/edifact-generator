<?php
namespace EDI\Generator;

class Vermas extends Message
{
    private $dtmSend;

    private $messageLine = '';
    private $messageSender = '';
    private $messageSenderInformation = '';
    private $messageSenderCompany = ['NAD', 'TB'];

    private $containers = [];

    /**
     * Construct.
     *
     * @param mixed $sMessageReferenceNumber (0062)
     * @param string $sMessageType (0065)
     * @param string $sMessageVersionNumber (0052)
     * @param string $sMessageReleaseNumber (0054)
     * @param string $sMessageControllingAgencyCoded (0051)
     * @param string $sAssociationAssignedCode (0057)
     */
    public function __construct(
        $sMessageReferenceNumber = null,
        $sMessageType = 'VERMAS',
        $sMessageVersionNumber = 'D',
        $sMessageReleaseNumber = '16A',
        $sMessageControllingAgencyCoded = 'UN',
        $sAssociationAssignedCode = 'SMDG10'
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
     * $line: Master Liner Codes List
     */
    public function setCarrier($line)
    {
        $this->messageLine = ['NAD', 'CA', [$line, 'LINES', 306]];
        return $this;
    }

    /*
     * $cntFunctionCode: DE 3139
     * $cntIdentifier: free text
     * $cntName: free text
     */
    public function setMessageSenderCompany($companyName)
    {
        $this->messageSenderCompany = ['NAD', 'TB',  $companyName];
        return $this;
    }

    /*
     * $cntFunctionCode: DE 3139
     * $cntIdentifier: free text
     * $cntName: free text
     */
    public function setMessageSender($cntFunctionCode, $cntIdentifier, $cntName)
    {
        $this->messageSender = ['CTA', $cntFunctionCode, [$cntIdentifier, $cntName]];
        return $this;
    }

    /*
     * $comType: DE 3155
     * $comData: free text
     */
    public function setMessageSenderInformation($comType, $comData)
    {
        $this->messageSenderInformation = ['COM', [$comData, $comType]];
        return $this;
    }

    public function addContainer(Vermas\Container $container)
    {
        $this->containers[] = $container;
        return $this;
    }

    /**
     * Compose.
     *
     * @param mixed $sMessageFunctionCode (1225)
     * @param mixed $sDocumentNameCode (1001)
     * @param mixed $sDocumentIdentifier (1004)
     *
     * @return parent::compose()
     */
    public function compose(?string $sMessageFunctionCode = null, ?string $sDocumentNameCode = null, ?string $sDocumentIdentifier = null): parent
    {
        $this->messageContent = [
            ['BGM', $sDocumentNameCode, $this->messageID, $sMessageFunctionCode]
        ];

        /* message creation date and time */
        $this->messageContent[] = $this->dtmSend;

        $this->messageContent[] = $this->messageSenderCompany;

        /* carrier line */
        if ($this->messageLine !== '') {
            $this->messageContent[] = $this->messageLine;
        }

        /* sender information */
        if ($this->messageSender !== '') {
            $this->messageContent[] = $this->messageSender;
        }
        if ($this->messageSenderInformation !== '') {
            $this->messageContent[] = $this->messageSenderInformation;
        }

        /* equipment and vgm information */
        foreach ($this->containers as $cntr) {
            $content = $cntr->compose();
            foreach ($content as $segment) {
                $this->messageContent[] = $segment;
            }
        }

        return parent::compose($sMessageFunctionCode, $sDocumentNameCode, $sDocumentIdentifier);
    }
}
