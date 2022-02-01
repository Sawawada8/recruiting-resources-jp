<?php

use PHPUnit\Framework\TestCase;
use Src\Models\ValueObjects\DistancePerTime;
use Src\Models\ValueObjects\Time;
use Src\Models\ValueObjects\Distance;
use Src\Models\ValueObjects\M;

class DistancePerTimeTest extends TestCase {
    public function testD()
    {
        $time = new Time('10:00:00.000');
        $time2 = new Time('11:00:00.000');
        $distance = new Distance(new M(0));
        $distance2 = new Distance(new M(1000));

        $dPerTime = new DistancePerTime($time, $time2, $distance, $distance2);
        $this->assertSame(
            1 / ((strtotime('02:00:00') - strtotime('01:00:00')) / 60 / 60) ,
            (int)$dPerTime->getKMPerTime()
        );
    }
}
