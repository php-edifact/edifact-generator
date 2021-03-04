<?php
/**
 * Created by PhpStorm.
 * User: Sascha
 * Date: 23.01.2018
 * Time: 13:25
 */

namespace EDI\Generator;

/**
 * Class Base
 *
 * @package EDI\Generator
 * @property array $composeKeys
 */
class Base
{

  /** @var array */
  protected $messageContent = [];

  /** @var array */
  protected $composed;

  /** @var string */
  protected $sender;

  /** @var string */
  protected $receiver;

  /** @var string */
//    protected $managingOrganisation = '89';

  /**
   * @param $keyName
   */
  public function addKeyToCompose($keyName)
  {
    array_push($this->composeKeys, $keyName);
  }

  /**
   * compose message by keys givven in an ordered array
   *
   * @param array $keys
   *
   * @return array
   * @throws EdifactException
   */
  public function composeByKeys($keys = null)
  {
    if (is_null($keys)) {
      $keys = $this->composeKeys;
    }
    foreach ($keys as $key) {
      if (property_exists($this, $key)) {
        if (!is_null($this->{$key})) {
          $value = $this->{$key};
          if ($value) {
            $this->messageContent[] = $value;
          } else {
            throw new EdifactException("key " . $key . " returns no array structure");
          }
        }
      } else {
        throw new EdifactException(
          'key: ' . $key . ' not found for composeByKeys in ' . get_class($this) . '->' .
          debug_backtrace()[1]['function']
        );
      }
    }

    return $this->messageContent;
  }

  /**
   * @return array
   */
  public function getComposed()
  {
    return $this->composed;
  }

  /**
   * @return string
   */
  public function getSender()
  {
    return $this->sender;
  }

  /**
   * @param string $sender
   *
   * @return $this
   */
  public function setSender($sender)
  {
    $this->sender = $sender;

    return $this;
  }

  /**
   * @return string
   */
  public function getReceiver()
  {
    return $this->receiver;
  }

  /**
   * @param string $receiver
   *
   * @return $this
   */
  public function setReceiver($receiver)
  {
    $this->receiver = $receiver;

    return $this;
  }


  /**
   * @param string, $functionCode
   * @param $identifier
   *
   * @return array|bool
   */
  protected function addRFFSegment($functionCode, $identifier)
  {
    if (empty($identifier)) {
      return false;
    }

    return [
      'RFF',
      [
        $functionCode,
        self::maxChars($identifier, 35),
      ],
    ];
  }

  /**
   * @param     $dateString
   * @param     $type
   * @param int $formatQualifier
   *
   * @return array
   * @throws EdifactException
   * @see https://www.stylusstudio.com/edifact/D94A/2379.htm
   */
  protected function addDTMSegment($dateString, $type, $formatQualifier = EdifactDate::DATE)
  {
    $data = [];
    array_push($data, $type);
    if (!empty($dateString)) {
      array_push($data, EdifactDate::get($dateString, $formatQualifier));
      array_push($data, $formatQualifier);
    }

    return [
      'DTM',
      $data,
    ];
  }

  /**
   * @param $documentNumber
   * @param $type
   *
   * @return array
   */
  public static function addBGMSegment($documentNumber, $type, $x = Invoic::TYPE_ORIGINAL)
  {
    return [
      'BGM',
      [
        $type,
        '',
        '89',
      ],
      $documentNumber,
      $x,
    ];
  }

  /**
   * Crop String to max char length
   *
   * @param string $string
   * @param int    $length
   *
   * @return string
   */
  protected static function maxChars($string, $length = 35)
  {
    if (empty($string)) {
      return '';
    }

    return mb_substr($string, 0, $length);
  }

  /**
   *
   * @param      $value
   * @param      $array
   * @param null $errorMessage
   *
   * @throws EdifactException
   */
  protected function isAllowed($value, $array, $errorMessage = null)
  {
    if (is_null($errorMessage)) {
      $errorMessage = 'value: ' . $value . ' is not in allowed values: ' .
        ' [' . implode(', ', $array) . '] in ' . get_class($this) . '->' .
        debug_backtrace()[1]['function'];
    }
    if (!in_array($value, $array)) {
      throw new EdifactException($errorMessage);
    }
  }


  /**
   * @param $qualifier
   * @param $value
   *
   * @return array
   */
  public static function addMOASegment($qualifier, $value)
  {
    return [
      'MOA',
      [
        $qualifier,
        EdiFactNumber::convert($value),
      ],
    ];
  }


  const PAT_VALUTA = '3';
  const PAT_SKONTO = '22';
  const PAT_NET_PAYMENT_TARGET = 'ZZZ';
  const PAT_TIME_INVOICE_DATE = 5;

  /**
   * @param     $type
   * @param int $value
   *
   * @return array
   */
  public static function addPATSegment($type, $value = self::PAT_TIME_INVOICE_DATE, $days = null)
  {
    $valueArray = [
      $value,
    ];

    if ($days) {
      $valueArray[] = '';
      $valueArray[] = '';
      $valueArray[] = $days;
    }

    return [
      'PAT',
      [
        $type,
      ],
      '',
      $valueArray,
    ];
  }


  const PCD_CASH_DISCOUNT = '12';

  /**
   * Prozentangaben
   *
   * @return array
   */
  public static function addPCDSegment($value)
  {
    return [
      'PCD',
      [
        self::PCD_CASH_DISCOUNT,
        EdiFactNumber::convert($value),
      ],
    ];
  }

  const PIA_ADDITIONAL_INFORMATION = 1;

  /**
   * EAN Nummer
   *
   * @return array
   */
  public static function addPIASegment($ean)
  {
    return [
      'PIA',
      [
        self::PIA_ADDITIONAL_INFORMATION,
      ],
      [
        $ean,
        'EN',
      ],
    ];
  }


}
