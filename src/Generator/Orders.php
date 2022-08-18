<?php

namespace EDI\Generator;

use EDI\Generator\Orders\Item;
use EDI\Generator\Traits\ContactPerson;
use EDI\Generator\Traits\NameAndAddress;
use EDI\Generator\Traits\TransportData;
use EDI\Generator\Traits\VatAndCurrency;

/**
 * Class Orders
 * @url http://www.unece.org/trade/untdid/d96b/trmd/orders_s.htm
 * @package EDI\Generator
 */
class Orders extends Message
{
    use ContactPerson;
    use NameAndAddress;
    use TransportData;
    use VatAndCurrency;

    /** @var array */
    protected $orderNumber;

    /** @var array */
    protected $orderDate;

    /** @var array */
    protected $orderContact;

    /** @var array */
    protected $purchasingContact;

    /** @var array */
    protected $documentDate;

    /** @var array */
    protected $deliveryDate;

    /** @var array */
    protected $deliveryDateLatest;

    /** @var array */
    protected $deliveryDateEarliest;

    /** @var array */
    protected $accountNumber;

    /** @var array */
    protected $collectiveOrderNumber;

    /** @var array */
    protected $internalIdentifier;

    /** @var array */
    protected $objectNumber;

    /** @var array */
    protected $objectDescription1;

    /** @var array */
    protected $objectDescription2;

    /** @var array */
    protected $orderDescription;

    /** @var array */
    protected $orderNotification;

    /** @var array */
    protected $deliveryTerms;

    /** @var array */
    protected $items;

    /** @var array */
    protected $composeKeys = [
        'orderNumber',
        'orderDate',
        'documentDate',
        'deliveryDate',
        'deliveryDateLatest',
        'deliveryDateEarliest',
        'orderDescription',
        'orderNotification',
        'accountNumber',
        'orderContact',
        'purchasingContact',
        'buyerAddress',
        'consigneeAddress',
        'deliveryPartyAddress',
        'messageRecipientAddress',
        'documentMessageSenderAddress',
        'storeKeeperAddress',
        'invoiceAddress',
        'supplierAddress',
        'internalIdentifier',
        'objectNumber',
        'objectDescription1',
        'objectDescription2',
        'vatNumber',
        'currency',
        'manufacturerAddress',
        'wholesalerAddress',
        'contactPerson',
        'mailAddress',
        'phoneNumber',
        'faxNumber',
        'deliveryAddress',
        'transportData',
        'deliveryTerms',
    ];

    /**
     * Orders constructor.
     * @param null $messageId
     * @param string $identifier
     * @param string $version
     * @param string $release
     * @param string $controllingAgency
     * @param string $association
     */
    public function __construct(
        $messageId = null,
        $identifier = 'ORDERS',
        $version = 'D',
        $release = '96B',
        $controllingAgency = 'UN',
        $association = 'ITEK35'
    ) {
        parent::__construct(
            $identifier,
            $version,
            $release,
            $controllingAgency,
            $messageId,
            $association
        );
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
     * @return $this
     * @throws EdifactException
     */
    public function compose()
    {
        $this->composeByKeys();

        foreach ($this->items as $item) {
            $composed = $item->compose();
            foreach ($composed as $entry) {
                $this->messageContent[] = $entry;
            }
        }

        // Segment Group 11 : Separator & Control Total
        $this->messageContent[] = ['UNS', 'S'];
        $this->messageContent[] = ['CNT', ['2', (string)count($this->items)]];

        parent::compose();
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
     * @param string $documentType
     * @return Orders
     * @throws EdifactException
     */
    public function setOrderNumber($orderNumber, $documentType = '220')
    {
        $this->isAllowed($documentType, [
            '120',
            '126',
            '220',
            '221',
            '224',
            '225',
            '226',
            '227',
            '228',
            '248',
            '258',
            '348',
            '350',
            '400',
            '401',
            '402',
            '447',
            '452',
            'YA8',
            'YS8',
            'YK8',
            '22B',
            '22E',
            '23E'
        ]);
        $this->orderNumber = ['BGM', $documentType, $orderNumber, '9'];
        return $this;
    }

    /**
     * Order number without documentType validation
     * @param $orderNumber
     * @param string $documentType
     * @return $this
     */
    public function setCustomOrderNumber($orderNumber, $documentType = '220')
    {
        $this->orderNumber = ['BGM', $documentType, $orderNumber, '9'];
        return $this;
    }

    /**
     * @return array
     */
    public function getOrderDate()
    {
        return $this->orderDate;
    }

    /**
     * @param array $orderDate
     * @return Orders
     * @throws EdifactException
     */
    public function setOrderDate($orderDate)
    {
        $this->orderDate = $this->addDTMSegment($orderDate, '4');
        return $this;
    }

    /**
     * @return array
     */
    public function getOrderContact()
    {
        return $this->orderContact;
    }

    /**
     * @param string $name
     * @param string $identifier
     * @return Orders
     */
    public function setOrderContact($name, $identifier = '')
    {
        $this->orderContact = ['CTA', 'OC', [$identifier, $name]];
        return $this;
    }

    /**
     * @return array
     */
    public function getPurchasingContact()
    {
        return $this->purchasingContact;
    }

    /**
     * @param string $name
     * @param string $identifier
     * @return Orders
     */
    public function setPurchasingContact($name, $identifier = '')
    {
        $this->purchasingContact = ['CTA', 'PD', [$identifier, $name]];
        return $this;
    }

    /**
     * @return array
     */
    public function getDocumentDate()
    {
        return $this->documentDate;
    }

    /**
     * @param string|\DateTime $documentDate
     * @param int $formatQuantifier
     * @return $this
     * @throws \EDI\Generator\EdifactException
     */
    public function setDocumentDate($documentDate, $formatQuantifier = EdifactDate::DATETIME)
    {
        $this->documentDate = $this->addDTMSegment($documentDate, '137', $formatQuantifier);
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
     * @param int $formatQuantifier
     * @return $this
     * @throws \EDI\Generator\EdifactException
     */
    public function setDeliveryDate($deliveryDate, $formatQuantifier = EdifactDate::DATETIME)
    {
        $this->deliveryDate = $this->addDTMSegment($deliveryDate, '2', $formatQuantifier);
        return $this;
    }

    /**
     * @return array
     */
    public function getDeliveryDateLatest()
    {
        return $this->deliveryDateLatest;
    }

    /**
     * @param $deliveryDate
     * @param int $formatQuantifier
     * @return $this
     * @throws \EDI\Generator\EdifactException
     */
    public function setDeliveryDateLatest($deliveryDate, $formatQuantifier = EdifactDate::DATETIME)
    {
        $this->deliveryDateLatest = $this->addDTMSegment($deliveryDate, '63', $formatQuantifier);
        return $this;
    }

    /**
     * @return array
     */
    public function getDeliveryDateEarliest()
    {
        return $this->deliveryDateEarliest;
    }

    /**
     * @param string|\DateTime $deliveryDateEarliest
     * @param int $formatQuantifier
     * @return $this
     * @throws \EDI\Generator\EdifactException
     */
    public function setDeliveryDateEarliest($deliveryDateEarliest, $formatQuantifier = EdifactDate::DATETIME)
    {
        $this->deliveryDateEarliest = $this->addDTMSegment($deliveryDateEarliest, '64', $formatQuantifier);
        return $this;
    }

    /**
     * @param $accountNumber
     * @return Orders
     * @throws \EDI\Generator\EdifactException
     */
    public function setAccountNumber($accountNumber)
    {
        $this->accountNumber = $this->addRFFSegment('ADE', $accountNumber);
        return $this;
    }

    /**
     * @return array
     */
    public function getAccountNumber()
    {
        return $this->accountNumber;
    }

    /**
     * @return array
     */
    public function getCollectiveOrderNumber()
    {
        return $this->collectiveOrderNumber;
    }

    /**
     * set a reference for qualifier ACD
     * @param string $collectiveOrderNumber
     * @return Orders
     */
    public function setCollectiveOrderNumber($collectiveOrderNumber)
    {
        $this->collectiveOrderNumber = $this->addRFFSegment('ACD', $collectiveOrderNumber);
        return $this;
    }

    /**
     * @return array
     */
    public function getInternalIdentifier()
    {
        return $this->internalIdentifier;
    }

    /**
     * set a reference for qualifier AAS
     * @param string $internalIdentifier
     * @return Orders
     */
    public function setInternalIdentifier($internalIdentifier)
    {
        $this->internalIdentifier = $this->addRFFSegment('AAS', $internalIdentifier);
        return $this;
    }

    /**
     * @return array
     */
    public function getObjectNumber()
    {
        return $this->objectNumber;
    }

    /**
     * set a reference for qualifier AEP
     * @param string $objectNumber
     * @return Orders
     */
    public function setObjectNumber($objectNumber)
    {
        $this->objectNumber = $this->addRFFSegment('AEP', $objectNumber);
        return $this;
    }

    /**
     * @return array
     */
    public function getObjectDescription1()
    {
        return $this->objectDescription1;
    }

    /**
     * set a reference for qualifier AFO
     * @param string $objectDescription1
     * @return Orders
     */
    public function setObjectDescription1($objectDescription1)
    {
        $this->objectDescription1 = $this->addRFFSegment('AFO', $objectDescription1);
        return $this;
    }

    /**
     * @return array
     */
    public function getObjectDescription2()
    {
        return $this->objectDescription2;
    }

    /**
     * set a reference for qualifier AFP
     * @param string $objectDescription2
     * @return Orders
     */
    public function setObjectDescription2($objectDescription2)
    {
        $this->objectDescription2 = $this->addRFFSegment('AFP', $objectDescription2);
        return $this;
    }

    /**
     * @return array
     */
    public function getOrderDescription()
    {
        return $this->orderDescription;
    }

    /**
     * @param string $orderDescription
     * @return Orders
     */
    public function setOrderDescription($orderDescription)
    {
        $this->orderDescription = self::addFTXSegment($orderDescription, 'ORI');
        return $this;
    }

    /**
     * @return array
     */
    public function getOrderNotification()
    {
        return $this->orderNotification;
    }

    /**
     * @param string $orderNotification
     * @return Orders
     */
    public function setOrderNotification($orderNotification)
    {
        $this->orderNotification = self::addFTXSegment($orderNotification, 'DIN');
        return $this;
    }

    /**
     * @return array
     */
    public function getDeliveryTerms()
    {
        return $this->deliveryTerms;
    }

    /**
     * @param string $deliveryTerms
     * @return Orders
     * @throws EdifactException
     */
    public function setDeliveryTerms($deliveryTerms)
    {
        $this->isAllowed(
            $deliveryTerms,
            ['CAF', 'CIP', 'CPT', 'DDP', 'DAF', 'FCA', 'CAI', 'ZZZ']
        );
        $this->deliveryTerms = ['TOD', '6', '', $deliveryTerms];
        return $this;
    }
}
