<?php

namespace Src;

use Src\Applications\FeeMeterService;
use Src\Entities\FeeMeter;

require __DIR__ . '/../vendor/autoload.php';


while ($line = fgets(STDIN)) {
    // 配列を生成する
    $input_datas[] = trim($line);
}


$feeMeterService = new FeeMeterService(new FeeMeter());

echo $feeMeterService->calcTotalFee($input_datas);
