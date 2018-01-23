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
 * @package EDI\Generator
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

    /**
     * compose message by keys givven in an ordered array
     * @param array $keys
     * @return array
     * @throws EdifactException
     */
    public function composeByKeys($keys)
    {
        foreach ($keys as $key) {
            if (property_exists($this, $key)) {
                if (!is_null($this->{$key})) {
                    $this->messageContent[] = $this->{$key};
                }
            } else {
                throw new EdifactException('key ' . $key . ' not found for composeByKeys');
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
     * @return array
     */
    protected function addRFFSegment($functionCode, $identifier)
    {
        return [
            'RFF',
            [
                $functionCode,
                self::maxChars($identifier, 35)
            ]
        ];
    }

    /**
     * @param $dateString
     * @param $type
     * @return array
     * @throws EdifactException
     */
    protected function addDTMSegment($dateString, $type)
    {
        return ['DTM', $type, EdifactDate::get($dateString)];
    }


    /**
     * Crop String to max char length
     * @param string $string
     * @param int $length
     * @return string
     */
    protected static function maxChars($string, $length = 35)
    {
        return mb_substr($string, 0, $length);
    }

}