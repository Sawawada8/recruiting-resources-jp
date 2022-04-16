<?php

use PHPUnit\Framework\TestCase;
use Src\ValueObjects\KM;
use Src\ValueObjects\M;
use Src\ValueObjects\CM;
use Src\ValueObjects\Distance;

class DistanceTest extends TestCase {
    public function testKMTodo()
    {
        $km = new KM(1);
        $this->assertSame($km->getValue(),1);
    }
    public function testMTodo()
    {
        $m = new M(1);
        $this->assertSame($m->getValue(),1);
    }
    public function testCMTodo()
    {
        $cm = new CM(1);
        $this->assertSame($cm->getValue(),1);
    }

    public function testDistance()
    {
        $distance = new Distance(new KM(1));

        $this->assertSame($distance->getKM()->getValue(),1);
        $this->assertSame($distance->getM()->getValue(),1000);
        $this->assertSame($distance->getCM()->getValue(),100000);

        $distance = new Distance(new M(1));

        $this->assertSame($distance->getKM()->getValue(),0.001);
        $this->assertSame($distance->getM()->getValue(),1);
        $this->assertSame($distance->getCM()->getValue(),100);
    }
}
