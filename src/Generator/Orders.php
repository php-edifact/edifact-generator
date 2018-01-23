<?php
/**
 * Created by PhpStorm.
 * User: Sascha
 * Date: 23.01.2018
 * Time: 16:30
 */

namespace EDI\Generator;

use EDI\Generator\Traits\ContactPerson;
use EDI\Generator\Traits\NameAndAddress;


/**
 * Class Orders
 * @url http://www.unece.org/trade/untdid/d96b/trmd/orders_s.htm
 * @package EDI\Generator
 */
class Orders extends Message
{
    use ContactPerson, NameAndAddress;
}