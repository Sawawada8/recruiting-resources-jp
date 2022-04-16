<?php

namespace Src\ValueObjects;

/**
 * 距離class
 */
class Distance {
    /** @var KM */
    private $km;

    /** @var M */
    private $m;

    /** @var CM */
    private $cm;

    public function __construct(IDistance $distance)
    {
        $distance_class = get_class($distance);

        switch($distance_class) {
            case 'Src\ValueObjects\KM':
                $this->km = $distance;
                $this->m = new M($distance->getValue() * 1000);
                $this->cm = new CM($distance->getValue() * 1000 * 100);
                break;
            case 'Src\ValueObjects\M':
                $this->km = new KM($distance->getValue() / 1000);
                $this->m = $distance;
                $this->cm = new CM($distance->getValue() * 100);
                break;
            case 'Src\ValueObjects\CM':
                $this->km = new KM($distance->getValue() / 1000 / 10);
                $this->m = new M($distance->getValue() / 100);
                $this->cm = $distance;
                break;
        }
    }

    /**
     * @return KM
     */
    public function getKM()
    {
        return $this->km;
    }

    /**
     * @return M
     */
    public function getM()
    {
        return $this->m;
    }

    /**
     * @return CM
     */
    public function getCM()
    {
        return $this->cm;
    }

}

