<?php

namespace App\ViewModels;

use App\Models\SalonServiceModel;

class SalonServiceDetailViewModel
{
    public int $salonId;
    public SalonServiceModel $service;
    public string $title;

    public function __construct(int $salonId, SalonServiceModel $service)
    {
        $this->salonId = $salonId;
        $this->service = $service;
        $this->title = $service->name;
    }
}

