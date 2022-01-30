<?php

namespace Src\Models\ValueObjects;

use Exception;

class DistanceDomainService {
    /** @var KM */
    private $km;

    /** @var M */
    private $m;

    /** @var CM */
    private $cm;

    public function __construct(IDistance $distance)
    {
        $distance_class = get_class($distance);
        if (!in_array($distance_class, [
            'Src\Models\ValueObjects\KM',
            'Src\Models\ValueObjects\M',
            'Src\Models\ValueObjects\CM',
        ])) {
            throw new Exception('KM, M, CM のいずれかのオブジェクトを設定してくだいさい.');
        }
        switch($distance_class) {
            case 'Src\Models\ValueObjects\KM':
                $this->km = $distance;
                $this->m = new M($distance->getValue() * 1000);
                $this->cm = new CM($distance->getValue() * 1000 * 100);
                break;
            case 'Src\Models\ValueObjects\M':
                $this->km = new KM($distance->getValue() / 1000);
                $this->m = $distance;
                $this->cm = new CM($distance->getValue() * 100);
                break;
            case 'Src\Models\ValueObjects\CM':
                $this->km = new KM($distance->getValue() / 1000 / 10);
                $this->m = new M($distance->getValue() / 100);
                $this->cm = $distance;
                break;
        }
    }

    public function getKM()
    {
        return $this->km;
    }
    public function getM()
    {
        return $this->m;
    }
    public function getCM()
    {
        return $this->cm;
    }

}

