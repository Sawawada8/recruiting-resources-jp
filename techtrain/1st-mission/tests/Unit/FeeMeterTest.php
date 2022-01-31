<?php

use PHPUnit\Framework\TestCase;
use Src\Models\ValueObjects\FeeMeter;
use Src\Models\ValueObjects\Time;
use Src\Models\ValueObjects\DistanceDomainService;
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
            new DistanceDomainService(new M(0)),
            new DistanceDomainService(new M(237000 + 1052)) // 1000 * 80 + 410
        );
        $this->assertSame($this->feeMeter->getFee()->getValue(), 410 + 1000*80);
    }
}
