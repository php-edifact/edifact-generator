<?php

namespace EDI\Generator;

/**
 * Class Westim
 * @package EDI\Generator
 */
class Westim extends Message
{
    private $_day;
    private $_estimateReference;

    private $_dtmATR;
    private $_currency;
    private $_labourRate;
    private $_nadDED;
    private $_nadLED;
    private $_equipment;
    private $_fullEmpty;

    private $_damages;

    private $_costTotals;
    private $_totalMessageAmounts;

    /**
     * Construct.
     *
     * @param mixed $sMessageReferenceNumber (0062)
     * @param string $sMessageType (0065)
     * @param string $sMessageVersionNumber (0052)
     * @param string $sMessageReleaseNumber (0054)
     * @param string $sMessageControllingAgencyCoded (0051)
     * @param string $sAssociationAssignedCode (0057)
     */
    public function __construct(
        $sMessageReferenceNumber = null,
        $sMessageType = 'WESTIM',
        $sMessageVersionNumber = '0',
        $sMessageReleaseNumber = null,
        $sMessageControllingAgencyCoded = null,
        $sAssociationAssignedCode = null
    ) {
        parent::__construct(
            $sMessageType,
            $sMessageVersionNumber,
            $sMessageReleaseNumber,
            $sMessageControllingAgencyCoded,
            $sMessageReferenceNumber,
            $sAssociationAssignedCode
        );

        $this->_estimateReference = $sMessageReferenceNumber;

        $this->_damages = [];
    }

    /**
     * $day = YYMMDD (used also in RFF+EST)
     * @param $day
     * @param null $time
     * @return \EDI\Generator\Westim
     */
    public function setTransactionDate($day, $time = null)
    {
        $this->_day = $day;
        $dt = $day;
        if ($time !== null) {
            $dt = [$day, $time];
        }
        $this->_dtmATR = ['DTM', 'ATR', $dt];

        return $this;
    }

    /**
     * $currency = XXX (three letter code)
     * @param $currency
     * @return \EDI\Generator\Westim
     */
    public function setCurrency($currency)
    {
        $this->_currency = ['ACA', $currency, ['STD', 0]];

        return $this;
    }

    /**
     * $labourRate = \d+.\d{2}
     * @param $labourRate
     * @return \EDI\Generator\Westim
     */
    public function setLabourRate($labourRate)
    {
        $this->_labourRate = ['LBR', $labourRate];

        return $this;
    }

    /**
     * Can be equal to the sender and receiver ID in UNH
     * @param $from
     * @param $to
     * @return \EDI\Generator\Westim
     */
    public function setPartners($from, $to)
    {
        $this->_nadDED = ['NAD', 'DED', $from];
        $this->_nadLED = ['NAD', 'LED', $to];

        return $this;
    }

    /**
     * Container number separated between letters and numbers
     * @param $ownerCode
     * @param $serial
     * @param $isoSize
     * @param int $maximumGrossWeight
     * @return \EDI\Generator\Westim
     */
    public function setContainer($ownerCode, $serial, $isoSize, $maximumGrossWeight = 0)
    {
        $this->_equipment = ['EQF', 'CON', [$ownerCode, $serial], $isoSize, ['MGW', $maximumGrossWeight, 'KGM']];

        return $this;
    }

    /**
     * Full or Empty
     * @param $fullEmpty
     * @return \EDI\Generator\Westim
     */
    public function setFullEmpty($fullEmpty)
    {
        $this->_fullEmpty = ['CUI', '', '', 'E'];

        return $this;
    }

    /**
     * Full or Empty
     * @param \EDI\Generator\Westim\Damage $damage
     * @return \EDI\Generator\Westim
     */
    public function addDamage(Westim\Damage $damage)
    {
        $this->_damages[] = $damage;

        return $this;
    }

    /**
     * @param $responsibility
     * @param $labour
     * @param $material
     * @param $handling
     * @param $tax
     * @param $invoiceAmount
     * @return \EDI\Generator\Westim
     */
    public function setCostTotals($responsibility, $labour, $material, $handling, $tax, $invoiceAmount)
    {
        $this->_costTotals = ['CTO', $responsibility, $labour, $material, $handling, $tax, $invoiceAmount];

        return $this;
    }

    /**
     * @param $grandTotal
     * @param null $authorizedAmount
     * @param null $taxRate
     * @return \EDI\Generator\Westim
     */
    public function setTotalMessageAmounts($grandTotal, $authorizedAmount = null, $taxRate = null)
    {
        $this->_totalMessageAmounts = ['TMA', $grandTotal];
        if ($authorizedAmount !== null) {
            $this->_totalMessageAmounts[] = ['TMA', $grandTotal, '', '', '', '', $authorizedAmount];
        }
        if ($taxRate !== null) {
            $this->_totalMessageAmounts[] = ['TMA', $grandTotal, '', '', '', '', $authorizedAmount, '', $taxRate];
        }

        return $this;
    }

    /**
     * Compose.
     *
     * @param mixed $sMessageFunctionCode (1225)
     * @param mixed $sDocumentNameCode (1001)
     * @param mixed $sDocumentIdentifier (1004)
     *
     * @return \EDI\Generator\Message ::compose()
     * @throws \EDI\Generator\EdifactException
     */
    public function compose(?string $sMessageFunctionCode = null, ?string $sDocumentNameCode = null, ?string $sDocumentIdentifier = null): parent
    {
        $this->messageContent = [];

        $this->messageContent[] = $this->_dtmATR;
        $this->messageContent[] = ['RFF', 'EST', $this->_estimateReference, $this->_day];
        $this->messageContent[] = $this->_currency;
        $this->messageContent[] = $this->_labourRate;
        $this->messageContent[] = $this->_nadLED;
        $this->messageContent[] = $this->_nadDED;
        $this->messageContent[] = $this->_equipment;

        if ($this->_fullEmpty !== null) {
            $this->messageContent[] = $this->_fullEmpty;
        }

        $this->messageContent[] = ['ECI', 'D'];

        foreach ($this->_damages as $damage) {
            $content = $damage->compose();

            foreach ($content as $segment) {
                $this->messageContent[] = $segment;
            }
        }

        $this->messageContent[] = $this->_costTotals;
        $this->messageContent[] = $this->_totalMessageAmounts;

        return parent::compose($sMessageFunctionCode, $sDocumentNameCode, $sDocumentIdentifier);
    }
}
