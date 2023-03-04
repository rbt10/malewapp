<?php

namespace App\Services;

use App\Repository\SpecialiteProvinceRepository;

class NavProvinces
{
    private SpecialiteProvinceRepository $provinceRepository;

    /**
     * @param SpecialiteProvinceRepository $provinceRepository
     */
    public function __construct(SpecialiteProvinceRepository $provinceRepository)
    {
        $this->provinceRepository = $provinceRepository;
    }

    public function provinces(){
        return $this->provinceRepository->findAll();
    }


}