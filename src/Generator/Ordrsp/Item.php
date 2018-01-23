<?php
/**
 * Created by PhpStorm.
 * User: Sascha
 * Date: 23.01.2018
 * Time: 08:44
 */

namespace EDI\Generator\Ordrsp;

use EDI\Generator\Base;
use EDI\Generator\EdifactException;

/**
 * Class Item
 * @package EDI\Generator\Ordrsp
 */
class Item extends Base
{
    /** @var array */
    protected $position;
    /** @var array */
    protected $quantity;
    /** @var array */
    protected $orderNumber;
    /** @var array */
    protected $positionOnOrder;
    /** @var array */
    protected $deliveryNoteNumber;
    /** @var array */
    protected $positionDeliveryNote;
    /** @var array */
    protected $description;

    /**
     * @return array
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param string $position
     * @param string $articleNumber
     * @param string $numberType
     * @return Item
     */
    public function setPosition($position, $articleNumber, $numberType = 'MF')
    {
        $this->position = ['LIN', $position, '', $articleNumber, $numberType];
        return $this;
    }

    /**
     * @return array
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param array $quantity
     * @param $unit
     * @return Item
     * @throws EdifactException
     */
    public function setQuantity($quantity, $unit = 'PCE')
    {
        $allowed = ['CMK', 'CMQ', 'CMT', 'DZN', 'GRM', 'HLT', 'KGM', 'KTM', 'LTR', 'MMT', 'MTK', 'MTQ', 'MTR', 'NRL',
            'PCE', 'PR', 'SET', 'TNE'];

        if (!in_array($unit, $allowed)) {
            throw new EdifactException($unit . ' is not allowed for quantity unit');
        }
        $this->quantity = ['QTY', '12', $quantity, $unit];
        return $this;
    }

    /**
     * @return array
     */
    public function getOrderNumber()
    {
        return $this->orderNumber;
    }

    /**
     * @param string $orderNumber
     * @return Item
     */
    public function setOrderNumber($orderNumber)
    {
        $this->orderNumber = $this->addRFFSegment('VN', $orderNumber);
        return $this;
    }

    /**
     * @return array
     */
    public function getPositionOnOrder()
    {
        return $this->positionOnOrder;
    }

    /**
     * @param string $positionOnOrder
     * @return Item
     */
    public function setPositionOnOrder($positionOnOrder)
    {
        $this->positionOnOrder = $this->addRFFSegment('LI', $positionOnOrder);
        return $this;
    }

    /**
     * @return array
     */
    public function getDeliveryNoteNumber()
    {
        return $this->deliveryNoteNumber;
    }

    /**
     * @param string $deliveryNoteNumber
     * @return Item
     */
    public function setDeliveryNoteNumber($deliveryNoteNumber)
    {
        $this->deliveryNoteNumber = $this->addRFFSegment('AAJ', $deliveryNoteNumber);
        return $this;
    }

    /**
     * @return array
     */
    public function getPositionDeliveryNote()
    {
        return $this->positionDeliveryNote;
    }

    /**
     * @param array $positionDeliveryNote
     * @return Item
     */
    public function setPositionDeliveryNote($positionDeliveryNote)
    {
        $this->positionDeliveryNote = $this->addRFFSegment('LI', $positionDeliveryNote);
        return $this;
    }

    /**
     * @return array
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return Item
     */
    public function setDescription($description)
    {
        $this->description = [
            'IMD',
            '',
            '',
            'ZU',
            '',
            '89',
            $description
        ];
        return $this;
    }

    /**
     * @return array
     * @throws \EDI\Generator\EdifactException
     */
    public function compose()
    {
        return $this->composeByKeys([
            'position',
            'quantity',
            'orderNumber',
            'positionOnOrder',
            'deliveryNoteNumber',
            'positionDeliveryNote',
        ]);
    }
}