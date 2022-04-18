<?php

namespace Src\Entities;

use Src\ValueObjects\Fee;
use Src\ValueObjects\Distance;
use Src\ValueObjects\CM;
use Src\ValueObjects\M;
use Src\ValueObjects\KM;
use Src\ValueObjects\Time;
use Src\ValueObjects\DistancePerTime;

/**
 */
class FeeMeter {
    /** @var Fee */
    private $fee;

    /** @var Distance */
    private $firstRideLimit;

    /** @var Time */
    private $time;

    /** @var Distance */
    private $distance;

    const FIRST_RIDE_DISTANCE = 1.052;
    const FIRST_RIDE_FEE = 410;

    const NORMAL_ADD_FEE = 80;
    const NORMAL_ADD_DISTANCE = 237;

    const SLOW_CONDITION = 10;
    const SLOW_ADD_FEE = 80;
    const SLOW_ADD_TIME = '00:01:30';

    const NIGHT_MAGNIFICATION = 1.25;

    public function __construct()
    {
        $this->fee = new Fee(self::FIRST_RIDE_FEE);
        $this->firstRideLimit = new Distance(new KM(self::FIRST_RIDE_DISTANCE));
    }

    /**
     * 時間、走行距離から料金の加算を行う
     * @param Time
     * @param Time
     * @param Distance
     * @param Distance
     * @return void
     */
    public function calcFee(
        Time $time,
        Time $time2,
        Distance $distance,
        Distance $distance2)
    {
        // 低速料金時は、加算
        $DPerTime = new DistancePerTime($time, $time2, $distance, $distance2);
        if ($DPerTime->getKMPerTime() < self::SLOW_CONDITION) {
            $this->calcSlowFee($time, $time2);
        }

        // 走行距離
        $runDistance = new Distance(
            new M(
                $distance2->getM()->getValue()
            )
        );

        if ($time->getIsNight() || $time2->getIsNight()) {
            // 深夜なので、距離に深夜倍率を掛ける
            $runDistance = new Distance(
                new M($runDistance->getM()->getValue() * self::NIGHT_MAGNIFICATION)
            );
        }

        // 初乗り距離から走行距離を消費していく
        $this->firstRideLimit = new Distance(
            new M(
                $this->firstRideLimit->getM()->getValue()
                - $runDistance->getM()->getValue()
            )
        );
        if ($this->firstRideLimit->getKM()->getValue() > 0) {
            // 初乗り距離の範囲で収まった
            return;
        }


        $runDistanceRMFirstRide = $this->firstRideLimit->getM()->getValue() * -1;

        $incrementCount = (int)ceil(
            $runDistanceRMFirstRide /
            self::NORMAL_ADD_DISTANCE);
        $this->fee->increment($incrementCount * self::NORMAL_ADD_FEE);
    }

    /**
     * @param  Distance
     * @return Distance
     */
    private function calcFirstRide(Distance $runDistance)
    {
        if ($this->firstRideLimit->getKM()->getValue() > $runDistance->getKM()->getValue()) {
            // 走行距離が初乗り範囲内
            $this->firstRideLimit =
                new Distance(
                    new KM(
                        $this->firstRideLimit->getKM()->getValue() -
                        $runDistance->getKM()->getValue()
                    )
                );

            // 走行距離はすべて消費された
            return new Distance(new KM(0));
        }

        $runDistance =
            new Distance(
                new KM(
                    $runDistance->getKM()->getValue()
                    -
                    $this->firstRideLimit->getKM()->getValue()
                )
            );
        return $runDistance;
    }

    /**
     * 低速料金の計算
     * @param Time $time
     * @param Time $time2
     * @return void
     */
    private function calcSlowFee(Time $time, Time $time2)
    {
        $runTimeSecond = $time2->getTime() - $time->getTime();

        if ($time->getIsNight() || $time2->getIsNight()) {
            $runTimeSecond *= self::NIGHT_MAGNIFICATION;
        }

        $incrementCount = (int)floor($runTimeSecond / 90);

        $this->fee->increment($incrementCount * self::SLOW_ADD_FEE);
    }

    public function getFee()
    {
        return $this->fee;
    }
}
