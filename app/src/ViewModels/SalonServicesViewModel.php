<?php

namespace App\ViewModels;

use App\Models\SalonServiceModel;

class SalonServicesViewModel
{
    /** @var SalonServiceModel[] */
    public array $services;

    public int $salonId;

    public string $title;

    public function __construct(int $salonId, array $services)
    {
        $this->salonId = $salonId;
        $this->services = $services;
        $this->title = "Services for salon #{$salonId}";
    }
}

