<?php

namespace EDI\Generator;

/**
 * Class EdifactCurrency
 * @url http://www.unece.org/trade/untdid/d96b/uncl/uncl6343.htm
 * @package EDI\Generator
 */
class EdifactCurrency
{
    const CURRENCY_CUSTOMS_VALUATIONS = '1';
    const CURRENCY_INSURANCE = '2';
    const CURRENCY_HOME = '3';
    const CURRENCY_INVOICING = '4';
    const CURRENCY_ACCOUNT = '5';
    const CURRENCY_REFERENCE = '6';
    const CURRENCY_TARGET = '7';
    const CURRENCY_PRICE_LIST = '8';
    const CURRENCY_ORDER = '9';
    const CURRENCY_PRICING = '10';
    const CURRENCY_PAYMENT = '11';
    const CURRENCY_QUOTATION = '12';
    const CURRENCY_RECIPIENT_LOCAL = '13';
    const CURRENCY_SUPPLIER = '14';
    const CURRENCY_SENDER_LOCAL = '15';
    const CURRENCY_TARIFF = '16';
    const CURRENCY_CHARGE_CALCULATION = '17';
}