<?php

namespace App\Taxes;

class Detector
{
    protected int $seuil;

    public function __construct(int $seuil)
    {
        $this->seuil = $seuil;
    }

    /**
     * @param $val
     * @return bool
     */
    public function detect($val): bool
    {
        return $val > $this->seuil;
    }
}
