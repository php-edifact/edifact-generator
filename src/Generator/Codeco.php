<?php
namespace EDI\Generator;

class Codeco extends Message
{
    private $sender;
    private $receiver;
    private $messageCF;

    private $containers;

    public function __construct($messageID = null, $identifier = 'CODECO', $version = 'D', $release = 'D95B', $controllingAgency = 'UN', $association = null)
    {
        parent::__construct($identifier, $version, $release, $controllingAgency, $messageID, $association);

        $this->containers = [];
    }

    /*
     *
     */
    public function setSenderAndReceiver($sender, $receiver)
    {
        $this->sender = ['NAD', 'MS', $sender];
        $this->receiver = ['NAD', 'MR', $receiver];
        return $this;
    }

    /*
     * $line: Master Liner Codes List
     */
    public function setCarrier($line)
    {
        $this->messageCF = ['NAD', 'CF', [$line, 160, 166]];
        return $this;
    }

    public function addContainer(Codeco\Container $container)
    {
        $this->containers[] = $container;
        return $this;
    }

    /*
     * $documentCode = 34 (gate in), 36 (gate out)
     */
    public function compose($msgStatus = 5, $documentCode = 34)
    {
        $this->messageContent = [
            ['BGM', $documentCode, $this->messageID, $msgStatus]
        ];

        $this->messageContent[] = $this->sender;
        $this->messageContent[] = $this->receiver;
        $this->messageContent[] = $this->messageCF;

        foreach ($this->containers as $cntr) {
            $content = $cntr->compose();
            foreach ($content as $segment) {
                $this->messageContent[] = $segment;
            }
        }

        $this->messageContent[] = ['CNT', [16, count($this->containers)]];
        parent::compose();
        return $this;
    }
}
