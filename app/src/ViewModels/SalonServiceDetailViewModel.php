<?php

namespace App\ViewModels;

use App\Models\SalonServiceModel;

class SalonServiceDetailViewModel
{
    public int $salonId;
    public SalonServiceModel $service;
    public string $title;

    /** @var array<int, array{id:int,name:string}> */
    public array $specialists;

    public function __construct(int $salonId, SalonServiceModel $service, array $specialists = [])
    {
        $this->salonId = $salonId;
        $this->service = $service;
        $this->specialists = $specialists;
        $this->title = $service->name;
    }
}


