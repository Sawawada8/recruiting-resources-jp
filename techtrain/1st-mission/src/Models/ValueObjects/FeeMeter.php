<?php

namespace Src\Models\ValueObjects;

/**
 */
class FeeMeter {
    /** @var Fee */
    private $fee;

    /** @var DistanceDomainService */
    private $firstRideLimit;

    /** @var Time */
    private $time;

    /** @var DistanceDomainService */
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
        $this->firstRideLimit = new DistanceDomainService(new KM(self::FIRST_RIDE_DISTANCE));
    }

    /**
     * @return void
     */
    public function calcFee(
        Time $time,
        Time $time2,
        DistanceDomainService $distance,
        DistanceDomainService $distance2)
    {
        $runDistance = new DistanceDomainService(
            new M(
                $distance2->getM()->getValue()
                -
                $distance->getM()->getValue()
            )
        );
        $runDistanceRMFirstRide = $this->calcFirstRide($runDistance);

        if ($runDistanceRMFirstRide->getKM()->getValue() == 0) {
            return;
        }

        $DPerTime = new DistancePerTime($time, $time2, $distance, $distance2);

        if ($DPerTime->getKMPerTime() < self::SLOW_CONDITION) {
            // 低速料金
            // 時間いっぱい指定のりょうきんでおｋ
            $runTime = new Time($time2->getTime() - $time->getTime());
            $runTimeSecond = $runTime->getSecond();
            $a = (int)$runTimeSecond / 90;

            $this->fee->increment($a * self::SLOW_ADD_FEE);
            return;
        }

        $aa = (int)floor($runDistanceRMFirstRide->getM()->getValue() / 237);
        $this->fee->increment($aa * 80);
    }

    /**
     * @return DistanceDomainService
     */
    private function calcFirstRide($runDistance)
    {
        if ($this->firstRideLimit->getKM()->getValue() > $runDistance->getKM()->getValue()) {
            // 初乗り範囲
            $this->firstRideLimit =
                new DistanceDomainService(
                    new KM(
                        $this->firstRideLimit->getKM()->getValue() -
                        $runDistance->getKM()->getValue()
                    )
                );

            // 走行距離はすべて消費された
            return new DistanceDomainService(new KM(0));
        }

        $runDistance =
            new DistanceDomainService(
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
