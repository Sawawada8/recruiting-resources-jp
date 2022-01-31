<?php

namespace Src\Models\ValueObjects;

use Exception;

class Time {
    /** @var int */
    private $time;
    /** @var float */
    private $timeFloat;
    /** @var bool */
    private $isNight;

    private $second;

    const S_NIGHT = '22:00:00';
    const E_NIGHT = '05:00:00';

    public function __construct(string $time)
    {
        $day = 0;
        if (false === strtotime($time)) {
            // over 24
            $hour = (int)explode(':', $time)[0];
            if ($hour > 99) {
                throw new Exception('不正な時間が設定されています。');
            }
            $day = floor($hour / 24) * 24 * 60 * 60;

            $time = (string)($hour % 24) . substr($time, 2);
        }
        $time = explode('.', $time);
        $this->time = strtotime($time[0]) + (int)$day;

        if (!isset($time[1])) {
            $time[1] = '000';
        }
        $float = '0.' . $time[1];
        $this->timeFloat = (float)$float;

        $time = $this->time + $this->timeFloat;
        $this->isNight = (
            strtotime(self::S_NIGHT) <= $time ||
            strtotime(self::E_NIGHT) >= $time
        );
    }

    public function getTime()
    {
        return $this->time;
    }
    public function getTimeFloat()
    {
        return $this->timeFloat;
    }
    public function getIsNight()
    {
        return $this->isNight;
    }
    public function getTotalTime()
    {
        return $this->time + $this->timeFloat;
    }
    public function getSecond()
    {
        return ($this->time + $this->timeFloat) - strtotime('00:00:00');
    }

    public function toString()
    {
        return date('H:i:s', $this->time) . '.' .
            str_replace('0.' ,'',number_format($this->timeFloat, 3));
    }
}



