<?php
/**
 * Created by PhpStorm.
 * User: Sascha
 * Date: 18.01.2018
 * Time: 14:16
 */

namespace EDI\Generator;

use EDI\Generator\Desadv\Item;
use EDI\Generator\Traits\ContactPerson;
use EDI\Generator\Traits\NameAndAddress;

/**
 * Class Desadv
 * @url http://www.unece.org/trade/untdid/d96b/trmd/desadv_s.htm
 * @package EDI\Generator
 */
class Desadv extends Message
{
    use ContactPerson, NameAndAddress;

    const DELIVERY_ADVICE = '22E';
    const DELIVER_NOTE = '270';
    const DELIVERY_NOTE_ADVICE = '351';

    /** @var array */
    protected $deliveryNoteNumber;
    /** @var array */
    protected $deliveryNoteDate;
    /** @var array */
    protected $shippingDate;
    /** @var array */
    protected $deliveryDate;
    /** @var array */
    protected $transport;
    /** @var Item[] */
    protected $items;

    /**
     * Desadv constructor.
     * @param null $messageId
     * @param string $identifier
     * @param string|null $version
     * @param string|null $release
     * @param string|null $controllingAgency
     * @param string|null $association
     */
    public function __construct($messageId = null, $identifier = 'DESADV', $version = 'D', $release = '96B',
                                $controllingAgency = 'UN', $association = 'ITEK35')
    {
        parent::__construct($identifier, $version, $release, $controllingAgency, $messageId, $association);
        $this->items = [];
    }

    /**
     * @param $item Item
     */
    public function addItem($item)
    {
        $this->items[] = $item;
    }


    /**
     * Set deliver note number
     * @param string $documentType
     * @param $number
     * @return $this
     * @throws EdifactException
     */
    public function setDeliveryNoteNumber($documentType, $number)
    {
        $allowed = [
            self::DELIVERY_ADVICE,
            self::DELIVER_NOTE,
            self::DELIVERY_NOTE_ADVICE
        ];
        if (!in_array($documentType, $allowed, true)) {
            throw new EdifactException('document type not allowed here');
        }
        $this->deliveryNoteNumber = ['BGM', $documentType, $number];
        return $this;
    }

    /**
     * @return array
     */
    public function getDeliverNoteNumber()
    {
        return $this->deliveryNoteNumber;
    }

    /**
     * @return array
     */
    public function getShippingDate()
    {
        return $this->shippingDate;
    }

    /**
     * @param string|\DateTime $shippingDate
     * @return $this
     * @throws EdifactException
     */
    public function setShippingDate($shippingDate)
    {
        $this->shippingDate = $this->addDTMSegment($shippingDate, '17');
        return $this;
    }

    /**
     * @return array
     */
    public function getDeliveryDate()
    {
        return $this->deliveryDate;
    }

    /**
     * @param string|\DateTime $deliveryDate
     * @return $this
     * @throws EdifactException
     */
    public function setDeliveryDate($deliveryDate)
    {
        $this->deliveryDate = $this->addDTMSegment($deliveryDate, '11');
        return $this;
    }

    /**
     * @return array
     */
    public function getDeliveryNoteDate()
    {
        return $this->deliveryNoteDate;
    }

    /**
     * @param string|\DateTime $deliveryNoteDate
     * @return $this
     * @throws EdifactException
     */
    public function setDeliveryNoteDate($deliveryNoteDate)
    {
        $this->deliveryNoteDate = $this->addDTMSegment($deliveryNoteDate, '137');
        return $this;
    }

    /**
     * @return array
     */
    public function getTransport()
    {
        return $this->transport;
    }

    /**
     * @param string $trackingCode
     * @param int $type
     * @return $this
     * @throws EdifactException
     */
    public function setTransport($trackingCode, $type = 30)
    {
        $allowed = [
            10, 20, 30, 40, 50, 60, 90
        ];
        if (!array_key_exists($type, $allowed)) {
            throw new EdifactException('transport type not allowed here');
        }
        $this->transport = ['TDT', '13', $trackingCode, $type];

        return $this;
    }


    /**
     * @param null $msgStatus
     * @return $this
     * @throws EdifactException
     */
    public function compose($msgStatus = null)
    {
        $this->composeByKeys([
            'deliveryNoteNumber',
            'deliveryNoteDate',
            'deliveryDate',
            'shippingDate',
            'manufacturerAddress',
            'contactPerson',
            'mailAddress',
            'phoneNumber',
            'faxNumber',
            'wholesalerAddress',
            'deliveryAddress',
            'transport'
        ]);

        foreach ($this->items as $item) {
            $composed = $item->compose();
            foreach ($composed as $entry) {
                $this->messageContent[] = $entry;
            }
        }

        parent::compose();
        return $this;
    }
}