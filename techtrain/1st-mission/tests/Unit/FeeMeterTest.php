<?php

use PHPUnit\Framework\TestCase;
use Src\Entities\FeeMeter;
use Src\ValueObjects\Time;
use Src\ValueObjects\Distance;
use Src\ValueObjects\M;

/**
 * 料金メーターオブジェクトテスト
 */
class FeeMeterTest extends TestCase {
    private $feeMeter;

    protected function setUp(): void
    {
        $this->feeMeter = new FeeMeter();
    }

    public function test_初期化時の初乗り料金のテスト()
    {
        $this->assertSame($this->feeMeter->getFee()->getValue(), 410);
    }

    public function test_初乗り料金＋所定距離のテスト()
    {
        // 初乗り料金 + 1km（通常速度）分の料金
        $this->feeMeter->calcFee(
            new Time('13:00:00.000'),
            new Time('13:00:10.000'),
            new Distance(new M(0)),
            new Distance(
                new M(
                    FeeMeter::NORMAL_ADD_DISTANCE * 1000 + 1052)
                ) // 80 * 1000  + 410
        );
        $this->assertSame($this->feeMeter->getFee()->getValue(), 410 + 1000*80);
    }

    public function test_低速料金に対するテスト()
    {
        // １時間で初乗り距離＋1kmの移動
        // 時速2052m で低速料金判定
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

    public function test_深夜料金に対するテスト()
    {
        $this->feeMeter->calcFee(
            new Time('23:00:00.000'),
            new Time('23:00:01.000'),
            new Distance(new M(0)),
            new Distance(new M(850))
        );
        $this->assertSame($this->feeMeter->getFee()->getValue(), 490);
    }

    public function test_深夜料金＋低速料金テスト()
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
