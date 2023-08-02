<?php

namespace App\Services\Edi;

use Carbon\Carbon;

class FritoLayEdiDocument extends EdiDocument
{

    protected $loopSegmentKey = 'LX';

    public function header($isaUniqueNumber, $ediDocumentType)
    {
        $dateLong = $this->getLongDate();
        $dateShort = $this->getShortDate();
        $time = $this->getTime();

        $isaReceiverNumber = '0073268790005';
        $isaSenderNumber = '2084674992';

        $this->add([
            "ISA*00*          *00*          *12*$isaSenderNumber     *14*$isaReceiverNumber  *$dateShort*$time*|*00501*$isaUniqueNumber*0*T*>~",
            "GS*PD*$isaSenderNumber*$isaReceiverNumber*$dateLong*$time*1*X*005010~",
            "ST*$ediDocumentType*000000001~"
        ]);

        return $this;
    }

    public function headerAsSender($isaUniqueNumber, $ediDocumentType, $stSegment = '000000001', $gsSegment = 'SW')
    {
        $dateLong = $this->getLongDate();
        $dateShort = $this->getShortDate();
        $time = $this->getTime();

        $isaReceiverNumber = '0073268790000';
        $isaSenderNumber = '2084674992';

        $this->add([
            "ISA*00*          *00*          *12*$isaSenderNumber     *14*$isaReceiverNumber  *$dateShort*$time*|*00501*$isaUniqueNumber*0*T*>~",
            "GS*$gsSegment*$isaSenderNumber*$isaReceiverNumber*$dateLong*$time*1*X*005010~",
            "ST*$ediDocumentType*$stSegment~"
        ]);

        return $this;
    }

    public function footer($isaUniqueNumber, $seSegment = '000000001')
    {
        $totalSegments = $this->getSegmentCount();

        $this->add([
            "SE*$totalSegments*$seSegment~",
            "GE*1*1~",
            "IEA*1*$isaUniqueNumber~"
        ]);

        return $this;
    }

    public function xqSegment($startDate = 'last saturday')
    {
        $dateLong = $this->getLongDate($startDate);

        $this->add([
            "XQ*H*$dateLong~",
        ]);

        return $this;
    }

    public function w17Segment($poNumber, $padLength = false)
    {
        $dateLong = $this->getLongDate('now');

        if ($padLength) {
            $poNumber = str_pad($poNumber, $padLength, '0', STR_PAD_LEFT);
        }

        $this->add([
            "W17*J*$dateLong*95337023*$poNumber~",
        ]);

        return $this;
    }

    public function w06Segment($poNumber)
    {
        $this->add([
            "W06*J*****$poNumber~",
        ]);

        return $this;
    }

    public function lxSegment($lineNumber = 1)
    {
        $this->add([
            "LX*$lineNumber~",
        ]);

        return $this;
    }

    public function w14Summary($totalQTY)
    {
        $this->add([
            "W14*$totalQTY~",
        ]);

        return $this;
    }

    public function w12Segment($amountShipped, $externalProductId)
    {
        $this->add([
            "W12*AN**$amountShipped****BP*$externalProductId~",
        ]);

        return $this;
    }

    public function w07Segment($amountReceived, $externalProductId)
    {
        $this->add([
            "W07*$amountReceived*CA**BP*$externalProductId~",
        ]);

        return $this;
    }

    public function n9Segment($field1, $field2)
    {
        $this->add([
            "N9*$field1*$field2~",
        ]);

        return $this;
    }

    public function g62Segment($subKey, $shippingDate, $time = false)
    {
        if ($time) {
            $this->add([
                "G62*$subKey*$shippingDate*0*$time~",
            ]);
        } else {
            $this->add([
                "G62*$subKey*$shippingDate~",
            ]);
        }


        return $this;
    }

    public function n9DailyInventorySegment()
    {
        $shiftNumber = 2;

        $this->add([
            "N9*71*$shiftNumber~",
            "N9*8X*PFG-PROD~",
        ]);

        return $this;
    }

    public function n9WeeklyInventorySegment($shift = 1)
    {
        $this->add([
            "N9*8X*PFG-PI~",
            "N9*71*$shift~",
        ]);

        return $this;
    }

    public function n9ExpirationSegment($expirationDate)
    {
        $this->add([
            "N9*ZZ**Expiration Date*$expirationDate~",
        ]);

        return $this;
    }

    public function n9ManufactureDate($manufactureDate)
    {
        $this->add([
            "N9*ZM**MFG DATE*$manufactureDate~",
        ]);

        return $this;
    }

    public function n1Segment($fields = [])
    {
        // N1*LW*FRITO LAY~
        //N1*VN*xxxxxxxxxxxxxxxxxx*FA*12345~

        $this->add([
            "N1*" . implode("*", $fields) . "~"
        ]);

        return $this;
    }

    public function n3Segment($address)
    {
        $this->add([
            "N3*$address~",
        ]);

        return $this;
    }

    public function n4Segment($city, $state, $zip)
    {
        // N4*xxxxxxxxxxxxxx*TX*12345~
        $this->add([
            "N4*$city*$state*$zip~",
        ]);

        return $this;
    }

    public function linSegment($ediItemUPC, $ediExternalProductCode, $itemDescription)
    {
        $this->add([
            "LIN**UA*$ediItemUPC*VN*$ediExternalProductCode*F7*$itemDescription*TP*FG~",
        ]);

        return $this;
    }

    public function zaSegment($quantity, $field1 = 'QN', $unitOfMeasure = 'CA', $receiveDate = false)
    {
        if (empty($receiveDate)) {
            $this->add([
                "ZA*$field1*$quantity*$unitOfMeasure~",
            ]);
        } else {
            $this->add([
                "ZA*$field1*$quantity*$unitOfMeasure*184*$receiveDate~",
            ]);
        }

        return $this;
    }

    public function zaSegmentLong($unitOfMeasure = 'CA')
    {
        $dateLong = $this->getLongDate();

        $this->add([
            "ZA*QA*0*$unitOfMeasure*184*$dateLong~",
        ]);

        return $this;
    }

    public function qtySegment($type, $quantity = 0, $unitOfMeasure = 'CA')
    {
        $this->add([
            "QTY*$type*$quantity*$unitOfMeasure~",
        ]);

        return $this;
    }
}
