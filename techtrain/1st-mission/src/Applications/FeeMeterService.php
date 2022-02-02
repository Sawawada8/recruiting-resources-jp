<?php

namespace Src\Applications;

use Exception;
use Src\Models\ValueObjects\Distance;
use Src\Models\ValueObjects\M;
use Src\Models\ValueObjects\Time;
use Src\Models\ValueObjects\FeeMeter;

class FeeMeterService {
    /** @var FeeMeter */
    private $feeMeter;

    public function __construct()
    {
        $this->feeMeter = new FeeMeter();
    }

    /**
     * @param  array
     * @return int
     */
    public function calcTotalFee($input_datas)
    {
        if (is_null($input_datas) || count($input_datas) == 0) {
            throw new Exception('入力データが不正です。');
        }

        foreach($input_datas as $key => $val) {
            if ($key === 0) {
                continue;
            }
            $data1 = explode(' ', $input_datas[$key-1]);
            $time1 = $data1[0];
            $distance1 = $data1[1];
            $data2 = explode(' ', $input_datas[$key]);
            $time2 = $data2[0];
            $distance2 = $data2[1];

            $this->feeMeter->calcFee(
                new Time($time1),
                new Time($time2),
                new Distance(new M($distance1)),
                new Distance(new M($distance2))
            );
        }

        return $this->feeMeter->getFee()->getValue();
    }
}
