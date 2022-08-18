<?php

namespace EDI\Generator;

/**
 * Class Aperak
 * @package EDI\Generator
 */
class Aperak extends Message
{
    private $dtmSend;
    private $previousMessage;
    private $references;
    private $errors;

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
        $sMessageType = 'APERAK',
        $sMessageVersionNumber = 'D',
        $sMessageReleaseNumber = '04A',
        $sMessageControllingAgencyCoded = 'UN',
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

        $this->dtmSend = self::dtmSegment(137, date('YmdHi'));
        $this->references = [];
        $this->errors = [];
    }

    /**
     * @param $number
     * @return $this
     */
    public function setPreviousMessage($number)
    {
        $this->previousMessage = self::rffSegment('ACW', $number);

        return $this;
    }

    /**
     * @param $qualifier data element 1153 (BM bill of lading, BN booking, SQ sequence, AAQ container, AAB proforma invoice)
     * @param $data value
     * @return $this
     */
    public function addReference($qualifier, $data)
    {
        $this->references[] = self::rffSegment($qualifier, $data);

        return $this;
    }

    /**
     *
     * @param $errorCode
     * @param $errorMessage
     * @return \EDI\Generator\Coparn
     */
    public function addError($errorCode, $errorMessage = null)
    {
        $this->errors[] = ['ERC', $errorCode];
        if ($errorMessage !== null) {
            $this->errors[] = self::addFTXSegment($errorMessage, 'AAO');
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
    public function compose(?string $sMessageFunctionCode = "5", ?string $sDocumentNameCode = "34", ?string $sDocumentIdentifier = null): parent
    {
        $this->messageContent = [
            ['BGM', $sDocumentNameCode, $this->messageID, $sMessageFunctionCode],
        ];

        if ($this->dtmSend !== null) {
            $this->messageContent[] = $this->dtmSend;
        }
        if ($this->previousMessage !== null) {
            $this->messageContent[] = $this->previousMessage;
        }

        foreach ($this->references as $rff) {
            $this->messageContent[] = $rff;
        }

        foreach ($this->errors as $err) {
            $this->messageContent[] = $err;
        }

        return parent::compose($sMessageFunctionCode, $sDocumentNameCode, $sDocumentIdentifier);
    }
}
