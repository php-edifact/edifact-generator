<?php

namespace EDI\Generator;

/**
 * Class Movins
 * @package EDI\Generator
 */
class Movins extends Message
{
    private $messageDate;
    private $vessel;
    private $placeOfDeparture;
    private $eta;
    private $voyageNumber;

    private $handling;

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
        $sMessageType = 'MOVINS',
        $sMessageVersionNumber = 'D',
        $sMessageReleaseNumber = '95B',
        $sMessageControllingAgencyCoded = 'UN',
        $sAssociationAssignedCode = 'SMDG20'
    ) {
        parent::__construct(
            $sMessageType,
            $sMessageVersionNumber,
            $sMessageReleaseNumber,
            $sMessageControllingAgencyCoded,
            $sMessageReferenceNumber,
            $sAssociationAssignedCode
        );

        $this->messageDate = self::dtmSegment(137, date('YmdHi'));
        $this->handling = [];
    }

    /**
     * Date of the message submission
     * @param $dtm
     * @return \EDI\Generator\Movins
     */
    public function setMessageDate($dtm)
    {
        $this->messageDate = self::dtmSegment(137, $dtm, 101);

        return $this;
    }

    /**
     * Vessel call information
     * @param $extVoyage
     * @param $line
     * @param $vslName
     * @param $callsign
     * @return \EDI\Generator\Movins
     */
    public function setVessel($extVoyage, $line, $vslName, $callsign)
    {
        $this->vessel = self::tdtSegment(20, $extVoyage, '', '', [$line, 172, 20], '', '', [$callsign, 103, 11, $vslName]);

        return $this;
    }

    /**
     * Place of Departure
     * @param $loc
     * @return \EDI\Generator\Movins
     */
    public function setPlaceOfDeparture($loc)
    {
        $this->placeOfDeparture = self::locSegment(5, [$loc, 139, 6]);

        return $this;
    }

    /**
     * Estimated Time of Arrival
     * @param $dtm
     * @return \EDI\Generator\Movins
     */
    public function setEta($dtm)
    {
        $this->eta = self::dtmSegment(101, $dtm);

        return $this;
    }

    /**
     * @param $number
     * @return \EDI\Generator\Movins
     */
    public function setVoyageNumber($number)
    {
        $this->voyageNumber = self::rffSegment('VON', $number);

        return $this;
    }

    public function addHandlingGroup($handlingType) {
        $this->handling[$handlingType] = [];

        return $this;
    }

    public function addContainer($handlingType, $containerGroup)
    {
        $this->handling[$handlingType][] = $containerGroup;

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
    public function compose(?string $sMessageFunctionCode = "9", ?string $sDocumentNameCode = "121", ?string $sDocumentIdentifier = null): parent
    {
        $this->messageContent = [
            ['BGM', $sDocumentNameCode, $this->messageID, $sMessageFunctionCode]
        ];

        $this->messageContent[] = $this->messageDate;
        $this->messageContent[] = $this->vessel;
        $this->messageContent[] = $this->placeOfDeparture;
        $this->messageContent[] = $this->eta;
        $this->messageContent[] = $this->voyageNumber;

        foreach ($this->handling as $handlingCode => $container) {
            $this->messageContent[] = ['HAN', $handlingCode];
            foreach ($container as $cntr) {
                $content = $cntr->compose();
                foreach ($content as $segment) {
                    $this->messageContent[] = $segment;
                }
            }
        }

        return parent::compose();
    }
}
