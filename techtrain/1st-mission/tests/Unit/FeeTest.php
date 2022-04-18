<?php

use PHPUnit\Framework\TestCase;
use Src\ValueObjects\Fee;
use Src\ValueObjects\M;
use Src\ValueObjects\CM;
use Src\ValueObjects\Distance;

class FeeTest extends TestCase {
    public function test_fee計算()
    {
        $fee = new Fee(0);
        $this->assertSame(0, $fee->getValue());

        $fee->increment(100);
        $this->assertSame(100, $fee->getValue());

        $fee->increment(1000);
        $this->assertSame(1100, $fee->getValue());
    }
}
