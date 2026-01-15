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

    /** @var array<int, array{id:int,name:string}> */
    public array $specialistOptions;

    /** @var int[] */
    public array $selectedSpecialistIds;

    public function __construct(
        int $salonId,
        SalonServiceModel $service,
        bool $isEdit,
        array $specialistOptions,
        array $selectedSpecialistIds
    ) {
        $this->salonId = $salonId;
        $this->service = $service;
        $this->isEdit = $isEdit;

        $this->specialistOptions = $specialistOptions;
        $this->selectedSpecialistIds = array_map('intval', $selectedSpecialistIds);

        $this->title = $isEdit ? 'Edit service' : 'Create service';

        $this->action = $isEdit
            ? "/salons/{$salonId}/services/{$service->id}/edit"
            : "/salons/{$salonId}/services/create";
    }

    public function isSelectedSpecialist(int $id): bool
    {
        return in_array($id, $this->selectedSpecialistIds, true);
    }
}


