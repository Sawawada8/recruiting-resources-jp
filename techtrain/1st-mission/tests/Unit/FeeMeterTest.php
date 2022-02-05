<?php

use PHPUnit\Framework\TestCase;
use Src\Models\ValueObjects\FeeMeter;
use Src\Models\ValueObjects\Time;
use Src\Models\ValueObjects\Distance;
use Src\Models\ValueObjects\KM;
use Src\Models\ValueObjects\M;

class FeeMeterTest extends TestCase {
    private $feeMeter;

    protected function setUp(): void
    {
        $this->feeMeter = new FeeMeter();
    }

    public function testFeeMeterInit()
    {
        $this->assertSame($this->feeMeter->getFee()->getValue(), 410);
    }

    public function testFirstRide()
    {
        $this->feeMeter->calcFee(
            new Time('13:00:00.000'),
            new Time('13:00:10.000'),
            new Distance(new M(0)),
            new Distance(new M(237000 + 1052)) // 1000 * 80 + 410
        );
        $this->assertSame($this->feeMeter->getFee()->getValue(), 410 + 1000*80);
    }

    /**
     * 低速料金テスト
     */
    public function testSlowFee()
    {
        $this->feeMeter->calcFee(
            new Time('13:00:00.000'),
            new Time('14:00:00.000'),
            new Distance(new M(0)),
            // 3600 s / 90 = 40 // 40 * 80 3200
            // 初乗り距離 + 1km
            new Distance(new M(1000 + 1052)) // 3200 + 410
        );
        $this->assertSame($this->feeMeter->getFee()->getValue(), 410 + 3600/90*80);
    }

    public function testNightFee()
    {
        $this->feeMeter->calcFee(
            new Time('23:00:00.000'),
            new Time('23:00:01.000'),
            new Distance(new M(0)),
            new Distance(new M(850))
        );
        $this->assertSame($this->feeMeter->getFee()->getValue(), 490);
    }

    public function testCompositeCondition()
    {
        // 低速料金 + 深夜料金
        $this->feeMeter->calcFee(
            new Time('23:00:00.000'),
            new Time('24:00:00.000'),
            new Distance(new M(0)),
            new Distance(new M(2000))
        );
        $this->assertSame($this->feeMeter->getFee()->getValue(), (int)(410 + 3600/90*80 * 1.25));
    }
}
