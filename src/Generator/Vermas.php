<?php
namespace EDI\Generator;

class Vermas extends Message
{
    private $dtmSend;

    private $messageLine = '';
    private $messageSender = '';
    private $messageSenderInformation = '';
    private $messageSenderCompany = ['NAD', 'TB'];

    private $containers;

    public function __construct($messageID = null, $identifier = 'VERMAS', $version = 'D', $release = '16A', $controllingAgency = 'UN', $association = 'SMDG10')
    {
        parent::__construct($identifier, $version, $release, $controllingAgency, $messageID, $association);

        $this->dtmSend = self::dtmSegment(137, date('YmdHi'));

        $this->containers = [];
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

    public function compose($msgStatus = 5, $documentCode = 749)
    {
        $this->messageContent = [
            ['BGM', $documentCode, $this->messageID, $msgStatus]
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

        parent::compose();
        return $this;
    }
}
