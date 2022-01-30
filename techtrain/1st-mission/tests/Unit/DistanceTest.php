<?php

use PHPUnit\Framework\TestCase;
use Src\Models\ValueObjects\KM;
use Src\Models\ValueObjects\M;
use Src\Models\ValueObjects\CM;
use Src\Models\ValueObjects\DistanceDomainService;

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

    public function testDistanceDomainService()
    {
        $distance = new DistanceDomainService(new KM(1));

        $this->assertSame($distance->getKM()->getValue(),1);
        $this->assertSame($distance->getM()->getValue(),1000);
        $this->assertSame($distance->getCM()->getValue(),100000);

        $distance = new DistanceDomainService(new M(1));

        $this->assertSame($distance->getKM()->getValue(),0.001);
        $this->assertSame($distance->getM()->getValue(),1);
        $this->assertSame($distance->getCM()->getValue(),100);
    }
}
