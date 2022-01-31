<?php

namespace Src\Models\ValueObjects;

class DistancePerTime {
    private $KMPerTime;
    private $KMPerMinute;
    private $KMPerSeconds;

    public function __construct(Time $time,Time $time2, DistanceDomainService $distance, DistanceDomainService $distance2)
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

    public function getKMPerTime()
    {
        return $this->KMPerTime;
    }
    public function getKMPerMinute()
    {
        return $this->KMPerMinute;
    }
    public function getKMPerSeconds()
    {
        return $this->KMPerSeconds;
    }
}
