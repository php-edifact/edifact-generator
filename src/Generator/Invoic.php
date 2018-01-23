<?php
/**
 * Created by PhpStorm.
 * User: Sascha
 * Date: 23.01.2018
 * Time: 16:15
 */

namespace EDI\Generator;

use EDI\Generator\Traits\ContactPerson;
use EDI\Generator\Traits\NameAndAddress;

/**
 * Class Invoic
 * @url http://www.unece.org/trade/untdid/d96b/trmd/invoic_s.htm
 * @package EDI\Generator
 */
class Invoic extends Message
{
    use ContactPerson, NameAndAddress;

}