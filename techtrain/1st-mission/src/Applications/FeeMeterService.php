<?php

namespace Src\Applications;

use Src\Models\ValueObjects\FeeMeter;

class FeeMeterService {
    /** @var FeeMeter */
    private $feeMeter;

    public function __construct()
    {
        $this->feeMeter = new FeeMeter();
    }
}
