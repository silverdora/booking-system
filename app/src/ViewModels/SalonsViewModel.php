<?php

namespace App\ViewModels;

use App\Models\SalonModel;

class SalonsViewModel
{
    /**
     * @var SalonModel[]
     */
    public array $salons;

    public function __construct(array $salons)
    {
        $this->salons = $salons;
    }
}