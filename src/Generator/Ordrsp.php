<?php
/**
 * Created by PhpStorm.
 * User: Sascha
 * Date: 23.01.2018
 * Time: 15:13
 */

namespace EDI\Generator;

use EDI\Generator\Traits\ContactPerson;
use EDI\Generator\Traits\NameAndAddress;

/**
 * Class Ordrsp
 * @url http://www.unece.org/trade/untdid/d96b/trmd/ordrsp_s.htm
 * @package EDI\Generator
 */
class Ordrsp extends Message
{
    use ContactPerson,
        NameAndAddress;

    /** @var array */
    protected $orderConfirmationNumber;
    /** @var array */
    protected $orderConfirmationDate;
    /** @var array */
    protected $deliveryDate;
    /** @var array */
    protected $orderNumber;
    /** @var array */
    protected $positionSeparator;
    /** @var array */
    protected $items = [];
    /** @var array  */
    protected $composeKeys = [
        'orderConfirmationNumber',
        'orderConfirmationDate',
        'deliveryDate',
        'orderNumber',
        'manufacturerAddress',
        'wholesalerAddress',
        'deliveryAddress',
        'contactPerson',
        'mailAddress',
        'phoneNumber',
        'faxNumber',
        'positionSeparator',
    ];

    /**
     * Ordrsp constructor.
     * @param null $messageId
     * @param string $identifier
     * @param string $version
     * @param string $release
     * @param string $controllingAgency
     * @param string $association
     */
    public function __construct(
        $messageId = null,
        $identifier = 'ORDRSP',
        $version = 'D',
        $release = '96B',
        $controllingAgency = 'UN',
        $association = 'ITEK35'
    )
    {
        parent::__construct(
            $identifier,
            $version,
            $release,
            $controllingAgency,
            $messageId,
            $association
        );
    }

    /**
     * @param null $msgStatus
     * @return $this
     * @throws EdifactException
     */
    public function compose($msgStatus = null)
    {
        $this->composeByKeys();

        foreach ($this->items as $item) {
            $composed = $item->compose();
            foreach ($composed as $entry) {
                $this->messageContent[] = $entry;
            }
        }

        parent::compose();
        return $this;
    }

    /**
     * @return array
     */
    public function getOrderConfirmationNumber()
    {
        return $this->orderConfirmationNumber;
    }


    /**
     * @param string $orderConfirmationNumber
     * @param string $documentType
     * @return Ordrsp
     */
    public function setOrderConfirmationNumber($orderConfirmationNumber, $documentType = '231')
    {
        $this->orderConfirmationNumber = ['BGM', $documentType, $orderConfirmationNumber];
        return $this;
    }

    /**
     * @return array
     */
    public function getOrderConfirmationDate()
    {
        return $this->orderConfirmationDate;
    }

    /**
     * @param string|\DateTime $orderConfirmationDate
     * @return Ordrsp
     * @throws EdifactException
     */
    public function setOrderConfirmationDate($orderConfirmationDate)
    {
        $this->orderConfirmationDate = $this->addDTMSegment($orderConfirmationDate, '4');
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
     * @return Ordrsp
     * @throws EdifactException
     */
    public function setDeliveryDate($deliveryDate)
    {
        $this->deliveryDate = $this->addDTMSegment($deliveryDate, '2');;
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
     * @return Ordrsp
     */
    public function setOrderNumber($orderNumber)
    {
        $this->orderNumber = $this->addRFFSegment('VN', $orderNumber);
        return $this;
    }

    /**
     * @return array
     */
    public function getPositionSeparator()
    {
        return $this->positionSeparator;
    }

    /**
     * @return Ordrsp
     */
    public function setPositionSeparator()
    {
        $this->positionSeparator = ['UNS', 'S'];
        return $this;
    }


}