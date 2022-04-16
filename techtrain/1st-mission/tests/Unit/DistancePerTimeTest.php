<?php

use PHPUnit\Framework\TestCase;
use Src\ValueObjects\DistancePerTime;
use Src\ValueObjects\Time;
use Src\ValueObjects\Distance;
use Src\ValueObjects\M;
use Src\ValueObjects\KM;
use Src\ValueObjects\CM;

/**
 * 速度(DistancePerTime)テスト
 */
class DistancePerTimeTest extends TestCase {
    public function test_時速1M()
    {
        $time = new Time('10:00:00.000');
        $time2 = new Time('11:00:00.000');
        $distance = new Distance(new M(0));
        $distance2 = new Distance(new M(1000));

        // 時速 1M
        $dPerTime = new DistancePerTime($time, $time2, $distance, $distance2);

        $this->assertSame(
            1 /
            ((strtotime('02:00:00') - strtotime('01:00:00')) / 60 / 60) ,
            (int)$dPerTime->getKMPerTime()
        );
    }


    public function test_KMから時速1M()
    {
        $time = new Time('10:00:00.000');
        $time2 = new Time('11:00:00.000');
        $distance = new Distance(new KM(0));
        $distance2 = new Distance(new KM(1));

        // 時速 1M
        $dPerTime = new DistancePerTime($time, $time2, $distance, $distance2);

        $this->assertSame(
            1 /
            ((strtotime('02:00:00') - strtotime('01:00:00')) / 60 / 60) ,
            (int)$dPerTime->getKMPerTime()
        );
    }

    public function test_CMから時速1M()
    {
        $time = new Time('10:00:00.000');
        $time2 = new Time('11:00:00.000');
        $distance = new Distance(new CM(0));
        $distance2 = new Distance(new CM(10000));

        // 時速 1M
        $dPerTime = new DistancePerTime($time, $time2, $distance, $distance2);

        $this->assertSame(
            1 /
            ((strtotime('02:00:00') - strtotime('01:00:00')) / 60 / 60) ,
            (int)$dPerTime->getKMPerTime()
        );
    }
}
