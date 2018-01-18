<?php
/**
 * Created by PhpStorm.
 * User: Sascha
 * Date: 18.01.2018
 * Time: 14:07
 */

namespace EDI\Generator\Desadv;

/**
 * Class DespatchAdvice
 * Generating a Despatch Advice
 *
 * @package EDI\Generator\Desadv
 */
class DespatchAdvice
{
    /** @var string */
    private $deliveryNoteNumber;

    /** @var string */
    private $deliveryNoteDate;

    /** @var string */
    private $shippingDate;

    /** @var string */
    private $deliveryDate;


    /**
     * DespatchAdvice constructor.
     */
    public function __construct()
    {

    }

    /**
     * @param string $deliveryNoteNumber
     * @return DespatchAdvice
     */
    public function setDeliveryNoteNumber($deliveryNoteNumber)
    {
        $this->deliveryNoteNumber = $deliveryNoteNumber;
        return $this;
    }

    /**
     * @param string $deliveryNoteDate
     * @return DespatchAdvice
     */
    public function setDeliveryNoteDate($deliveryNoteDate)
    {
        $this->deliveryNoteDate = $deliveryNoteDate;
        return $this;
    }

    /**
     * @param string $shippingDate
     * @return DespatchAdvice
     */
    public function setShippingDate($shippingDate)
    {
        $this->shippingDate = $shippingDate;
        return $this;
    }

    /**
     * @param string $deliveryDate
     * @return DespatchAdvice
     */
    public function setDeliveryDate($deliveryDate)
    {
        $this->deliveryDate = $deliveryDate;
        return $this;
    }


}