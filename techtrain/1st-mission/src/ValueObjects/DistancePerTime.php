<?php

namespace Src\ValueObjects;

/**
 * 速度class
 * 1km あたりの 時間、分、秒 の値を持つ
 */
class DistancePerTime {
    /** @var int */
    private $KMPerTime;

    /** @var int */
    private $KMPerMinute;

    /** @var int */
    private $KMPerSeconds;

    public function __construct(Time $time,Time $time2, Distance $distance, Distance $distance2)
    {
        $this->KMPerTime =
            ($distance2->getKM()->getValue() - $distance->getKM()->getValue())
            /
            (($time2->getTotalTime() - $time->getTotalTime()) / 60 / 60);
        $this->KMPerMinute =
            ($distance2->getKM()->getValue() - $distance->getKM()->getValue())
            /
            (($time2->getTotalTime() - $time->getTotalTime()) / 60);
        $this->KMPereSeconds =
            ($distance2->getKM()->getValue() - $distance->getKM()->getValue())
            /
            (($time2->getTotalTime() - $time->getTotalTime()));
    }

    /**
     * @return int
     */
    public function getKMPerTime()
    {
        return $this->KMPerTime;
    }

    /**
     * @return int
     */
    public function getKMPerMinute()
    {
        return $this->KMPerMinute;
    }

    /**
     * @return int
     */
    public function getKMPerSeconds()
    {
        return $this->KMPerSeconds;
    }
}
