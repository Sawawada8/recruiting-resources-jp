<?php

use PHPUnit\Framework\TestCase;
use Src\ValueObjects\Time;

class TimeTest extends TestCase {
    public function test_Timeオブジェクトの基本的な挙動テスト()
    {
        $time = new Time('15:00:00.000');
        $this->assertSame(strtotime('15:00:00'), $time->getTime());
        $this->assertSame(0.000, $time->getTimeFloat());
        $this->assertSame(false, $time->getIsNight());
        $this->assertSame(strtotime('15:00:00') + 0.000, $time->getTotalTime());
        $this->assertSame('15:00:00.000', $time->toString());
    }

    public function test_PM22時からAM5時を深夜と判定するかどうか()
    {
        $time = new Time('21:59:59.999');
        $this->assertSame(strtotime('21:59:59'), $time->getTime());
        $this->assertSame(0.999, $time->getTimeFloat());
        $this->assertSame(false, $time->getIsNight());
        $this->assertSame(strtotime('21:59:59') + 0.999, $time->getTotalTime());
        $this->assertSame('21:59:59.999', $time->toString());

        $time = new Time('22:00:00.000');
        $this->assertSame(strtotime('22:00:00'), $time->getTime());
        $this->assertSame(0.000, $time->getTimeFloat());
        $this->assertSame(true, $time->getIsNight());
        $this->assertSame(strtotime('22:00:00') + 0.000, $time->getTotalTime());
        $this->assertSame('22:00:00.000', $time->toString());

        $time = new Time('05:00:00.000');
        $this->assertSame(strtotime('05:00:00'), $time->getTime());
        $this->assertSame(0.000, $time->getTimeFloat());
        $this->assertSame(true, $time->getIsNight());
        $this->assertSame(strtotime('05:00:00') + 0.000, $time->getTotalTime());
        $this->assertSame('05:00:00.000', $time->toString());

        $time = new Time('05:00:00.001');
        $this->assertSame(strtotime('05:00:00'), $time->getTime());
        $this->assertSame(0.001, $time->getTimeFloat());
        $this->assertSame(false, $time->getIsNight());
        $this->assertSame(strtotime('05:00:00') + 0.001, $time->getTotalTime());
        $this->assertSame('05:00:00.001', $time->toString());
    }

    public function test_小数点以下を設定しない値に対するテスト()
    {
        $time = new Time('00:01:30');
        $this->assertSame(strtotime('00:01:30'), $time->getTime());
        $this->assertSame(0.0, $time->getTimeFloat());
        $this->assertSame(90.0, $time->getSecond());
        $this->assertSame('00:01:30.000', $time->toString());
    }

    public function test_24時間を超えた値に対するテスト()
    {
        $time = new Time('25:00:00.000');
        $this->assertSame($time->getTime(), strtotime('24:00:00') + 60*60);

        $time = new Time('48:00:00.000');
        $this->assertSame($time->getTime(), strtotime('00:00:00') + 2*24*60*60);

        $time = new Time('96:00:01.000');
        $this->assertSame($time->getTime(), strtotime('00:00:00') + 4*24*60*60 + 1);
    }
}
