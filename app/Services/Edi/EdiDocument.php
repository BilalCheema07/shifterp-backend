<?php

namespace App\Services\Edi;

use Carbon\Carbon;

class EdiDocument
{

    protected $ediDocument = '';

    protected $ediInfoSegments = [];
    protected $ediHeaderSegments = [];
    protected $ediLoopSegments = [];
    protected $ediFooterSegments = [];

    const EDI_DOCUMENT_TYPE_852 = '852';
    const EDI_DOCUMENT_TYPE_997 = '997';
    const EDI_DOCUMENT_TYPE_940 = '940';
    const EDI_DOCUMENT_TYPE_945 = '945';
    const EDI_DOCUMENT_TYPE_944 = '944';
    const EDI_DOCUMENT_TYPE_204 = '204';
    const EDI_DOCUMENT_TYPE_856 = '856';
    const EDI_START_INDEX = 0;

    const EDI_SEGMENT_ISA = 'ISA';
    const EDI_SEGMENT_ST = 'ST';
    const EDI_SEGMENT_GS = 'GS';
    const EDI_SEGMENT_IEA = 'IEA';
    const EDI_SEGMENT_GE = 'GE';
    const EDI_SEGMENT_SE = 'SE';
    const EDI_SEGMENT_LX = 'LX';
    const EDI_SEGMENT_LIN = 'LIN';

    const EDI_SEGMENT_VALUE_SEPARATOR = '*';
    const EDI_LINE_SEPARATOR = '~';

    const EDI_HEADER_KEYS = [
        self::EDI_SEGMENT_ISA,
        self::EDI_SEGMENT_GS,
        self::EDI_SEGMENT_ST
    ];

    const EDI_FOOTER_KEYS = [
        self::EDI_SEGMENT_IEA,
        self::EDI_SEGMENT_GE,
        self::EDI_SEGMENT_SE
    ];

    protected $loopSegmentKey = self::EDI_SEGMENT_LX;
    protected $typeSegmentKey = 'ST.2';

    protected $startDate = null;

    protected $docType = null;


    public function __construct($ediContents = null)
    {
        $this->setLoopSegment(self::EDI_SEGMENT_LX);
        $this->setStartFromDate(Carbon::now()->timestamp);

        $this->ediDocument = $ediContents;
        $this->parseSegments();
    }

    public function typeIs($ediType)
    {
        $typeInDocument = $this->search('ST.2');

        return ($ediType == $typeInDocument);
    }

    public function getType()
    {
        if (empty($this->docType)) {
            $this->docType = $this->search($this->typeSegmentKey);
        }

        return $this->docType;
    }

    protected function getSegmentParts($segment)
    {
        return explode(self::EDI_SEGMENT_VALUE_SEPARATOR, $segment);
    }

    private function isFooterSegment($key)
    {
        return in_array($key, self::EDI_FOOTER_KEYS);
    }

    private function isHeaderSegment($key)
    {
        return in_array($key, self::EDI_HEADER_KEYS);
    }

    private function isLoopSegment($key)
    {
        return $key == $this->loopSegmentKey;
    }

    private function isInfoSegment($key)
    {
        return !(($this->isFooterSegment($key) || $this->isHeaderSegment($key) || $this->isLoopSegment($key)));
    }

    public function refreshSegments()
    {
        $this->parseSegments();
    }

    public function setLoopSegment($loopSegment)
    {
        $this->loopSegmentKey = $loopSegment;
    }

    protected function parseSegments()
    {
        if (empty($this->ediDocument)) {
            return;
        }

        $this->ediHeaderSegments = [];
        $this->ediFooterSegments = [];
        $this->ediInfoSegments = [];
        $this->ediLoopSegments = [];

        $segments = explode('~', $this->ediDocument);

        $segmentIndexes = [
            'ediLoopSegments' => 1,
            'ediHeaderSegments' => 1,
            'ediInfoSegments' => 1,
            'ediFooterSegments' => 1,
        ];

        $loopIndex = 0;

        $inLoop = false;

        foreach ($segments as $segment) {

            if (empty($segment)) {
                continue;
            }

            $segmentParts = $this->getSegmentParts($segment);
            $segmentKey = $segmentParts[0];

            switch ($segmentKey) {
                case $this->isLoopSegment($segmentKey):
                    $inLoop = true;
                    $segmentArray = 'ediLoopSegments';
                    $loopIndex++;
                    break;

                case $this->isFooterSegment($segmentKey):
                    $inLoop = false;

                    $segmentArray = 'ediFooterSegments';
                    break;

                case $this->isHeaderSegment($segmentKey):
                    $inLoop = false;
                    $segmentArray = 'ediHeaderSegments';
                    break;

                default:
                    if ($inLoop) {
                        $segmentArray = 'ediLoopSegments';
                    } else {
                        $segmentArray = 'ediInfoSegments';
                    }

                    break;
            }

            $currentSegmentIndex = $segmentIndexes[$segmentArray];

            foreach ($segmentParts as $i => $value) {
                if ($inLoop) {
                    $this->{$segmentArray}[$loopIndex][$currentSegmentIndex][$segmentKey][$i+1] = $value;
                } else {
                    $this->{$segmentArray}[$currentSegmentIndex][$segmentKey][$i+1] = $value;
                }

            }

            $segmentIndexes[$segmentArray]++;

        }
    }

    public function getLongDate($startDate = 'last saturday')
    {
        return Carbon::parse($startDate)->format("Ymd");
    }

    public function getShortDate()
    {
        return Carbon::parse($this->startDate)->format("ymd");
    }

    public function getTime()
    {
        return Carbon::parse($this->startDate)->format("Hi");
    }

    public function add($data = [])
    {
        if (!empty($data)) {
            $this->ediDocument .= implode("\n", $data) . "\n";
        }

        return $this;
    }

    public function setStartFromDate($timestamp)
    {
        $this->startDate = $timestamp;
    }

    public function rawData($niceFormat = false)
    {
        $formattedDoc = $this->ediDocument;

        if ($niceFormat) {
            $formattedDoc = str_replace("~", "~\n", $formattedDoc);
        }

        return $formattedDoc;
    }

    public function getLoopSegment($index = false)
    {
        if (false === $index || empty($this->ediLoopSegments[$index])) {
            return $this->ediLoopSegments;
        } else {
            return $this->ediLoopSegments[$index];
        }
    }

    public function getHeaderSegments()
    {
        return $this->ediHeaderSegments;
    }

    public function getFooterSegments()
    {
        return $this->ediFooterSegments;
    }

    public function getInfoSegments()
    {
        return $this->ediInfoSegments;
    }

    public static function fromFile($file)
    {
        $ediData = file_get_contents($file);

        return new static($ediData);
    }

    private function findIndexValueInSegments($segments, $key, $index, $subKey = null, $subKeyAtEnd = false)
    {

        foreach ($segments as $segmentItems)
        {
            foreach ($segmentItems as $k => $values) {

                $subKeyMatches = true;

                if (!empty($subKey)) {
                    if ($subKeyAtEnd) {
                        $lastIndex = count($values);
                        $subKeyMatches = ($values[$lastIndex] == $subKey);
                    } else {
                        if ($key == $this->loopSegmentKey) {
                            return $values[$this->loopSegmentKey][$index];
                        } else {
                            $subKeyMatches = ($values[2] == $subKey);
                        }
                    }

                }

                if ($k == $key && $subKeyMatches && isset($values[$index])) {
                    return $values[$index];
                }
            }
        }

        return '';
    }

    public function ediLoopExists($index)
    {
        $loopSegments = $this->getLoopSegment();

        return !empty($loopSegments[$index]);
    }

    public function search($string, $subKeyAtEnd = false)
    {
        $returnValue = '';

        $parts = explode('.', $string);

        if (count($parts) >= 2) {

            $segmentKey = $parts[0];

            switch ($segmentKey) {

                case $this->isFooterSegment($segmentKey):

                    if (count($parts) == 2) {
                        $segmentIndex = $parts[1];

                        $returnValue = $this->findIndexValueInSegments(
                            $this->getFooterSegments(),
                            $segmentKey,
                            $segmentIndex
                        );
                    }

                    break;

                case $this->isHeaderSegment($segmentKey):

                    if (count($parts) == 2) {
                        $segmentIndex = $parts[1];

                        $returnValue = $this->findIndexValueInSegments(
                            $this->getHeaderSegments(),
                            $segmentKey,
                            $segmentIndex
                        );
                    }

                    break;

                case $this->isInfoSegment($segmentKey):

                    // Info segments can repeat.
                    if (count($parts) == 2) {
                        $segmentIndex = $parts[1];

                        $returnValue = $this->findIndexValueInSegments(
                            $this->getInfoSegments(),
                            $segmentKey,
                            $segmentIndex
                        );
                    } else if (count($parts) == 3) {
                        $subKey = $parts[1];
                        $segmentIndex = $parts[2];

                        $returnValue = $this->findIndexValueInSegments(
                            $this->getInfoSegments(),
                            $segmentKey,
                            $segmentIndex,
                            $subKey,
                            $subKeyAtEnd
                        );
                    }

                    break;

                case $this->isLoopSegment($segmentKey):

                    if (count($parts) == 4) {

                        $loopIndex = $parts[1];
                        $loopSegmentKey = $parts[2];
                        $loopSegmentIndex = $parts[3];

                        if ($this->ediLoopExists($loopIndex)) {
                            $returnValue = $this->findIndexValueInSegments(
                                $this->getLoopSegment($loopIndex),
                                $loopSegmentKey,
                                $loopSegmentIndex
                            );
                        }
                    } else if (count($parts) == 5) {
                        $loopIndex = $parts[1];
                        $loopSegmentKey = $parts[2];
                        $loopSubKey = $parts[3];
                        $loopSegmentIndex = $parts[4];

                        if ($this->ediLoopExists($loopIndex)) {
                            $returnValue = $this->findIndexValueInSegments(
                                $this->getLoopSegment($loopIndex),
                                $loopSegmentKey,
                                $loopSegmentIndex,
                                $loopSubKey,
                                $subKeyAtEnd
                            );
                        }
                    } else if (count($parts) == 3) {

                        $loopIndex = $parts[1];
                        $segmentIndex = $parts[2];

                        $returnValue = $this->findIndexValueInSegments(
                            $this->getLoopSegment($loopIndex),
                            $segmentKey,
                            $segmentIndex
                        );
                    }

                    break;

            }

        }

        return $returnValue;
    }


    public function getSegmentCount()
    {
        $ediDoc = $this->rawData();

        $segmentCount = count(explode(self::EDI_LINE_SEPARATOR, $ediDoc));

        if ($segmentCount - 2 > 0) {
            $segmentCount -= 2;
        }

        return $segmentCount;
    }
}
