<?php
namespace EDI\Generator;

class Vermas extends Message
{
    private $dtmSend;

    private $messageSender;
    private $messageSenderInformation;

    private $containers;

    public function __construct($identifier, $version, $release, $controllingAgency, $messageID, $association = null)
    {
        parent::__construct($identifier, $version, $release, $controllingAgency, $messageID, $association);

        $this->dtmSend = $this->dtmSegment(137, date('YmdHi'));

        $this->containers = [];
    }

    /*
     * Date of the message submission
     *
     */
    public function setDTMMessageSendingTime($dtm)
    {
        $this->dtmSend = $this->dtmSegment(137, $dtm);
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

    public function compose($msgStatus = 5)
    {
        $this->messageContent = [
            ['BGM', '749', $this->messageID, $msgStatus]
        ];

        /* message creation date and time */
        $this->messageContent[] = $this->dtmSend;

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
