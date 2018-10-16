<?php

namespace EDI\Generator;

/**
 * Class Interchange
 * @package EDI\Generator
 */
class Interchange
{
    /**
     * Interchange header parameters
     */
    private $interchangeCode;

    /** @var string */
    private $sender;

    /** @var string */
    private $charset = 'UNOA';

    /** @var string */
    private $charsetVersion = '2';

    /** @var string */
    private $receiver;

    /** @var string */
    private $date;

    /** @var string */
    private $time;

    /** @var array */
    private $messages;

    /** @var array */
    private $composed;

    /**
     * Interchange constructor.
     * @param $sender
     * @param $receiver
     * @param null $date
     * @param null $time
     * @param null $interchangeCode
     */
    public function __construct($sender, $receiver, $date = null, $time = null, $interchangeCode = null)
    {
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
    }

    /**
     * @param $charset
     * @return $this
     */
    public function setCharset($charset)
    {
        $this->charset = $charset;
        return $this;
    }

    /**
     * @param string $charsetVersion
     * @return Interchange
     */
    public function setCharsetVersion($charsetVersion)
    {
        $this->charsetVersion = $charsetVersion;
        return $this;
    }


    /**
     * Add a Message to the Interchange
     * @param $msg
     * @return Interchange
     */
    public function addMessage($msg)
    {
        $this->messages[] = $msg;

        return $this;
    }

    /**
     * Format the Interchange segments
     * @return Interchange
     */
    public function compose()
    {
        $temp = [];
        $temp[] = [
            'UNB',
            [
                $this->charset,
                $this->charsetVersion
            ],
            $this->sender,
            $this->receiver,
            [
                $this->date,
                $this->time
            ],
            $this->interchangeCode
        ];

        foreach ($this->messages as $msg) {
            foreach ($msg->getComposed() as $i) {
                $temp[] = $i;
            }
        }

        $temp[] = [
            'UNZ',
            count($this->messages),
            $this->interchangeCode
        ];
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
