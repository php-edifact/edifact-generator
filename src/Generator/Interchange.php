<?php

namespace EDI\Generator;

/**
 * Class Interchange
 * @package EDI\Generator
 */
class Interchange
{
    private $interchangeCode;
    private $sender;
    private $receiver;
    private $date;
    private $time;
    private $charset;
    private $appref;

    private $messages;
    private $composed;

    public function __construct(
        string $sender,
        string $receiver,
        ?string $date = null,
        ?string $time = null,
        ?string $interchangeCode = null
    ) {
        $this->messages = [];

        if ($interchangeCode === null) {
            $this->interchangeCode = 'I' . strtoupper(uniqid());
        } else {
            $this->interchangeCode = $interchangeCode;
        }

        $this->sender = $sender;
        $this->receiver = $receiver;
        if ($date === null) {
            $this->date = date('ymd');
        } else {
            $this->date = $date;
        }
        if ($time === null) {
            $this->time = date('Hi');
        } else {
            $this->time = $time;
        }

        $this->charset = ['UNOA', 2];
    }

    /**
     * Change the default character set
     * $identifier Syntax identifier
     * $version Syntax version
     * @param $identifier
     * @param $version
     * @return $this
     */
    public function setCharset($identifier, $version)
    {
        $this->charset = [$identifier, $version];

        return $this;
    }

    /**
     * Set the application reference
     * $appref Application reference
     * @param $appref
     * @return $this
     */
    public function setApplicationReference($appref)
    {
        $this->appref = $appref;

        return $this;
    }

    /**
     * Add a Message to the Interchange
     * @param $msg
     * @return $this
     */
    public function addMessage($msg)
    {
        $this->messages[] = $msg;

        return $this;
    }

    /**
     * Return the messages array
     * @return array
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * Format the Interchange segments
     * @return $this
     */
    public function compose()
    {
        $temp = [];
        $composedMessages = [];

        foreach ($this->messages as $msg) {
            $msgContent = $msg->getComposed();
            if ($msgContent === null) {
                $msgContent = $msg->compose()->getComposed();
            }

            $composedMessages[] = $msgContent;
        }

        $applicationReference = $this->appref;
        if ($applicationReference === null && isset($composedMessages[0][0][2][0])) {
            $applicationReference = (string) $composedMessages[0][0][2][0];
        }

        $unb = [
            'UNB',
            $this->charset,
            $this->sender,
            $this->receiver,
            [$this->date, $this->time],
            $this->interchangeCode,
        ];

        if ($applicationReference !== null && $applicationReference !== '') {
            $unb[] = $applicationReference;
            $unb[] = [];
            $unb[] = [];
            $unb[] = [];
            $unb[] = [];
        }

        $temp[] = $unb;

        foreach ($composedMessages as $msgContent) {
            foreach ($msgContent as $entry) {
                $temp[] = $entry;
            }
        }

        $temp[] = ['UNZ', (string) count($this->messages), $this->interchangeCode];
        $this->composed = $temp;

        return $this;
    }

    /**
     * Return composed message as array
     * @return array
     */
    public function getComposed()
    {
        if ($this->composed === null) {
            $this->compose();
        }

        return $this->composed;
    }
}
