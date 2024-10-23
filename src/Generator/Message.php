<?php

namespace EDI\Generator;

/**
 * Class Message
 * @package EDI\Generator
 */
class Message extends Base
{
    /** @var string */
    protected $messageID;

    protected $messageContent;

    /** @var array string */
    protected $messageType;

    public function __construct(
        string $identifier,
        string $version,
        ?string $release = null,
        ?string $controllingAgency = null,
        ?string $messageID = null,
        ?string $association = null
    ) {
        $this->messageType = [$identifier, $version];

        if ($release !== null) {
            $this->messageType[] = $release;
        }

        if ($release !== null) {
            $this->messageType[] = $controllingAgency;
        }

        if ($association !== null) {
            $this->messageType[] = $association;
        }

        if ($messageID === null) {
            $this->messageID = 'M' . strtoupper(uniqid());
        } else {
            $this->messageID = $messageID;
        }

        $this->messageContent = [];
    }

    public function setMessageContent($messageContent)
    {
        $this->messageContent = $messageContent;
        return $this;
    }

    public function getMessageContent()
    {
        return $this->messageContent;
    }

    public function addSegment($segment)
    {
        $this->messageContent[] = $segment;
        return $this;
    }

    public function getMessageID()
    {
        return $this->messageID;
    }

    public function setMessageID($messageId)
    {
        $this->messageID = $messageId;
        return $this;
    }

    /**
     * Compose.
     * @throws \EDI\Generator\EdifactException
     */
    public function compose()
    {
        $aComposed = [];

        // Message Header
        $aComposed[] = ['UNH', $this->messageID, $this->messageType];

        if (count($this->messageContent) == 0) {
            throw new EdifactException('no content available for message');
        }

        // Segments
        foreach ($this->messageContent as $i) {
            if ($i instanceof \EDI\Generator\Segment) {
                $i = $i->compose()->getComposed();
            }
            $aComposed[] = $i;
        }

        // Message Trailer
        $aComposed[] = ['UNT', (string)(2 + count($this->messageContent)), $this->messageID];

        $this->composed = $aComposed;

        return $this;
    }

}
