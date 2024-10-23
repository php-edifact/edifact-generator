<?php

namespace EDI\Generator;

use EDI\Generator\Traits\Segments;

/**
 * Class Base
 *
 * @package EDI\Generator
 */
class Base
{
    use Segments;

    /** @var array */
    protected $messageContent = [];

    /** @var array */
    protected $composed;

    /** @var string */
    //    protected $managingOrganisation = '89';

    /**
     * @param $keyName
     */
    public function addKeyToCompose($keyName)
    {
        $this->composeKeys[] = $keyName;
    }

    /**
     * compose message by keys given in an ordered array
     *
     * @param array $keys
     *
     * @return array
     * @throws EdifactException
     */
    public function composeByKeys($keys = null)
    {
        if ($keys === null) {
            $keys = $this->composeKeys;
        }
        foreach ($keys as $key) {
            if (property_exists($this, $key)) {
                if ($this->{$key} !== null) {
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
     * Crop String to max char length
     *
     * @param string $string
     * @param int $length
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
     * @param $errorMessage
     *
     * @throws EdifactException
     */
    protected function isAllowed($value, $array, $errorMessage = null)
    {
        if ($errorMessage === null) {
            $errorMessage = 'value: ' . $value . ' is not in allowed values: ' .
                ' [' . implode(', ', $array) . '] in ' . get_class($this) . '->' .
                debug_backtrace()[1]['function'];
        }
        if (!in_array($value, $array, true)) {
            throw new EdifactException($errorMessage);
        }
    }

}
