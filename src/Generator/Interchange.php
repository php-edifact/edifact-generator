<?php

namespace EDI\Generator;

/**
 * Class Interchange
 *
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

  /** @var string */
  private $senderQualifier;

  /** @var string */
  private $receiverQualifier;


  /**
   * Interchange constructor.
   *
   * @param string $sender
   * @param string $receiver
   * @param null   $date
   * @param null   $time
   * @param null   $interchangeCode
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
   *
   * @return $this
   */
  public function setCharset($charset)
  {
    $this->charset = $charset;
    return $this;
  }

  /**
   * @param string $charsetVersion
   *
   * @return Interchange
   */
  public function setCharsetVersion($charsetVersion)
  {
    $this->charsetVersion = $charsetVersion;
    return $this;
  }


  /**
   * Add a Message to the Interchange
   *
   * @param $msg
   *
   * @return Interchange
   */
  public function addMessage($msg)
  {
    $this->messages[] = $msg;

    return $this;
  }

  /**
   * Format the Interchange segments
   *
   * @return Interchange
   */
  public function compose()
  {
    $temp = [];
    $sender = $this->sender;
    $receiver = $this->receiver;
    if ($this->senderQualifier) {
      $sender = [
        $this->sender,
        $this->senderQualifier,
      ];
    }
    if ($this->receiverQualifier) {
      $receiver = [
        $this->receiver,
        $this->receiverQualifier,
      ];
    }
    $temp[] = [
      'UNB',
      [
        $this->charset,
        $this->charsetVersion,
      ],
      $sender,
      $receiver,
      [
        $this->date,
        $this->time,
      ],
      $this->interchangeCode,
    ];

    foreach ($this->messages as $msg) {
      foreach ($msg->getComposed() as $i) {
        $temp[] = $i;
      }
    }

    $temp[] = [
      'UNZ',
      count($this->messages),
      $this->interchangeCode,
    ];
    $this->composed = $temp;

    return $this;
  }

  /**
   * Return composed message as array
   *
   * @return array
   */
  public function getComposed()
  {
    if ($this->composed === null) {
      $this->compose();
    }
    return $this->composed;
  }

  /**
   * @return string
   */
  public function getSenderQualifier()
  {
    return $this->senderQualifier;
  }

  /**
   * @param string $senderQualifier
   *
   * @return Interchange
   */
  public function setSenderQualifier($senderQualifier)
  {
    $this->senderQualifier = $senderQualifier;
    return $this;
  }

  /**
   * @return string
   */
  public function getReceiverQualifier()
  {
    return $this->receiverQualifier;
  }

  /**
   * @param string $receiverQualifier
   *
   * @return Interchange
   */
  public function setReceiverQualifier($receiverQualifier)
  {
    $this->receiverQualifier = $receiverQualifier;
    return $this;
  }


}
