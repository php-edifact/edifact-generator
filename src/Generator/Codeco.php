<?php

namespace EDI\Generator;

/**
 * Class Codeco
 * @package EDI\Generator
 */
class Codeco extends Message
{
    private $sender;
    private $receiver;
    private $messageCF;

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
        $sMessageType = 'CODECO',
        $sMessageVersionNumber = 'D',
        $sMessageReleaseNumber = '95B',
        $sMessageControllingAgencyCoded = 'UN',
        $sAssociationAssignedCode = null
    ) {
        parent::__construct(
            $sMessageType,
            $sMessageVersionNumber,
            $sMessageReleaseNumber,
            $sMessageControllingAgencyCoded,
            $sMessageReferenceNumber,
            $sAssociationAssignedCode
        );
    }

    /**
     * @param $sender
     * @param $receiver
     * @return \EDI\Generator\Codeco
     */
    public function setSenderAndReceiver($sender, $receiver)
    {
        $this->sender = ['NAD', 'MS', $sender];
        $this->receiver = ['NAD', 'MR', $receiver];

        return $this;
    }

    /**
     * $line: Master Liner Codes List
     * @param $line
     * @return \EDI\Generator\Codeco
     */
    public function setCarrier($line)
    {
        $this->messageCF = ['NAD', 'CF', [$line, 160, 166]];

        return $this;
    }

    /**
     * @param \EDI\Generator\Codeco\Container $container
     * @return $this
     */
    public function addContainer(Codeco\Container $container)
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
     * @return \EDI\Generator\Message ::compose()
     * @throws \EDI\Generator\EdifactException
     */
    public function compose(?string $sMessageFunctionCode = "5", ?string $sDocumentNameCode = "34", ?string $sDocumentIdentifier = null): parent
    {
        $this->messageContent = [
            ['BGM', $sDocumentNameCode, $this->messageID, $sMessageFunctionCode],
        ];

        if ($this->sender !== null) {
            $this->messageContent[] = $this->sender;
        }
        if ($this->receiver !== null) {
            $this->messageContent[] = $this->receiver;
        }
        if ($this->messageCF !== null) {
            $this->messageContent[] = $this->messageCF;
        }

        foreach ($this->containers as $cntr) {
            $content = $cntr->compose();
            foreach ($content as $segment) {
                $this->messageContent[] = $segment;
            }
        }

        $this->messageContent[] = ['CNT', [16, count($this->containers)]];

        return parent::compose($sMessageFunctionCode, $sDocumentNameCode, $sDocumentIdentifier);
    }
}
