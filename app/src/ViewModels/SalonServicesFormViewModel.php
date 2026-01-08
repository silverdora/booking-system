<?php

namespace App\ViewModels;

use App\Models\SalonServiceModel;

class SalonServicesFormViewModel
{
    public int $salonId;
    public SalonServiceModel $service;
    public bool $isEdit;
    public string $action;
    public string $title;

    public function __construct(int $salonId, SalonServiceModel $service, bool $isEdit)
    {
        $this->salonId = $salonId;
        $this->service = $service;
        $this->isEdit = $isEdit;

        $this->title = $isEdit ? 'Edit service' : 'Create service';

        $this->action = $isEdit
            ? "/salons/{$salonId}/services/{$service->id}/edit"
            : "/salons/{$salonId}/services/create";
    }
}

