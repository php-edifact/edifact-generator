<?php

namespace EDI\Generator;

/**
 * Class EdifactCurrency
 * @url http://www.unece.org/trade/untdid/d96b/uncl/uncl6343.htm
 * @package EDI\Generator
 */
class EdifactCurrency
{
    public const CURRENCY_CUSTOMS_VALUATIONS = '1';
    public const CURRENCY_INSURANCE = '2';
    public const CURRENCY_HOME = '3';
    public const CURRENCY_INVOICING = '4';
    public const CURRENCY_ACCOUNT = '5';
    public const CURRENCY_REFERENCE = '6';
    public const CURRENCY_TARGET = '7';
    public const CURRENCY_PRICE_LIST = '8';
    public const CURRENCY_ORDER = '9';
    public const CURRENCY_PRICING = '10';
    public const CURRENCY_PAYMENT = '11';
    public const CURRENCY_QUOTATION = '12';
    public const CURRENCY_RECIPIENT_LOCAL = '13';
    public const CURRENCY_SUPPLIER = '14';
    public const CURRENCY_SENDER_LOCAL = '15';
    public const CURRENCY_TARIFF = '16';
    public const CURRENCY_CHARGE_CALCULATION = '17';
}
