<?php
/**
 * Created by PhpStorm.
 * User: Sascha
 * Date: 18.01.2018
 * Time: 14:16
 */

namespace EDI\Generator;

/**
 * Class Desadv
 * @package EDI\Generator
 */
class Desadv extends Message
{
    /** @var string */
    private $sender;
    /** @var string */
    private $receiver;
    /** @var array */
    private $containers;

    /**
     * Desadv constructor.
     * @param null $messageId
     * @param string $identifier
     * @param string $version
     * @param string $release
     * @param string $controllingAgency
     * @param null $association
     */
    public function __construct($messageId = null, $identifier = 'DESADV', $version = 'D', $release = '96B', $controllingAgency = 'UN', $association = null)
    {
        parent::__construct($identifier, $version, $release, $controllingAgency, $messageId, $association);

        $this->containers = [];
    }

    /**
     * @param $sender
     * @return $this
     */
    public function setSender($sender)
    {
        $this->sender = ['NAD', 'MS', $sender];
        return $this;
    }


    /**
     * @param $receiver
     * @return $this
     */
    public function setReceiver($receiver)
    {
        $this->receiver = ['NAD', 'MR', $receiver];
        return $this;
    }


    /**
     * @param null $msgStatus
     * @return $this
     */
    public function compose($msgStatus = null)
    {
        if ($this->sender !== null) {
            $this->messageContent[] = $this->sender;
        }
        if ($this->receiver !== null) {
            $this->messageContent[] = $this->receiver;
        }

//        $this->messageContent[] = ['CNT', [16, count($this->containers)]];
        parent::compose();
        return $this;
    }
}