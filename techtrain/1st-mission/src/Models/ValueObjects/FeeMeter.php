<?php

namespace Src\Models\ValueObjects;

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
    const NORMAL_ADD_LIMIT = 237;

    const SLOW_CONDITION = 10;
    const SLOW_ADD_FEE = 80;
    const SLOW_ADD_TIME = '00:01:30';

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
        $runDistance = new Distance(
            new M(
                $distance2->getM()->getValue()
                -
                $distance->getM()->getValue()
            )
        );
        $runDistanceRMFirstRide = $this->calcFirstRide($runDistance);
        if ($runDistanceRMFirstRide->getKM()->getValue() == 0) {
            // 初乗り距離の範囲で収まった
            return;
        }

        $DPerTime = new DistancePerTime($time, $time2, $distance, $distance2);

        if ($DPerTime->getKMPerTime() < self::SLOW_CONDITION) {
            // 低速料金
            // 時間いっぱい指定のりょうきんでおｋ
            $runTimeSecond = $time2->getTime() - $time->getTime();

            $incrementCount = (int)floor($runTimeSecond / 90);

            $this->fee->increment($incrementCount * self::SLOW_ADD_FEE);
            return;
        }

        $incrementCount = (int)floor($runDistanceRMFirstRide->getM()->getValue() / 237);
        $this->fee->increment($incrementCount * 80);
    }

    /**
     * @return Distance
     */
    private function calcFirstRide($runDistance)
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

    public function getFee()
    {
        return $this->fee;
    }
}

class FeeMeterApplicationService {
    public function __construct()
    {
        # code...
    }
}
