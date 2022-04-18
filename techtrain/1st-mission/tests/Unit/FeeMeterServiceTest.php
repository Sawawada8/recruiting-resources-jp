<?php

use PHPUnit\Framework\TestCase;
use Src\Applications\FeeMeterService;
use Exception;

/**
 * 料金メーターサービスオブジェクトテスト
 */
class FeeMeterServiceTest extends TestCase {
    private $feeMeterService;

    protected function setUp(): void
    {
        $this->feeMeterService = new FeeMeterService();
    }

    public function test_例外テスト()
    {
        $this->expectException(Exception::class);

        $this->feeMeterService->calcTotalFee([ ]);
        $this->feeMeterService->calcTotalFee(null);
    }

    public function test_料金計算テスト()
    {
        $result = $this->feeMeterService->calcTotalFee([
            '13:50:00.000 0.0',
            '13:50:10.000 1052.0',
        ]);

        // 初乗り範囲内なので410
        $this->assertSame($result, 410);
    }

    public function test_低速を含む料金テスト()
    {
        $result = $this->feeMeterService->calcTotalFee([
            '13:50:00.000 0.0',
            '13:50:10.000 1052.0',
            '13:56:10.000 1.0',
            // '13:59:10.000 10.0',
        ]);

        // 初乗り410
        // + (低速で6m(360s) => 80円 * 4 ) 320
        // + (初乗りから + 1m なので、) 80
        $this->assertSame($result, 410 + 320 + 80);
    }

    public function test_低速を含む料金テスト（切り捨ての確認）()
    {
        $result = $this->feeMeterService->calcTotalFee([
            '13:50:00.000 0.0',
            '13:50:10.000 1052.0',
            '13:57:10.000 1.0',
            // '13:59:10.000 10.0',
        ]);

        // 初乗り410
        // + (低速で7m(420s) => 80円 * floor(420 / 90 ) = 80 * 4 ) 320
        // + (初乗りから + 1m なので、) 80
        $this->assertSame($result, 410 + 320 + 80);
    }

    public function test_深夜料金テスト()
    {
        $result = $this->feeMeterService->calcTotalFee([
            '21:58:00.000 0.0',
            '21:59:00.000 1052.0',
            '22:00:01.000 1000.0',
            // '13:59:10.000 10.0',
        ]);

        // 初乗り410
        // + (1kmの深夜料金 ceil(1000 * 1.25 / 237) * 80 ) = 480
        $this->assertSame($result, 410 + 480);
    }

    public function test_深夜料金＋低速計算テスト()
    {
        $result = $this->feeMeterService->calcTotalFee([
            '21:58:00.000 0.0',
            '21:59:00.000 1052.0',
            '22:59:00.000 1000.0',
            // '13:59:10.000 10.0',
        ]);

        // 初乗り410
        // + (1kmの深夜料金 ceil(1000 * 1.25 / 237) * 80 ) = 480
        // + (深夜に低速で1h(3600s * 1.25) => 80円 * 50) 4000
        $this->assertSame($result, 410 + 480 + 4000);
    }
}
