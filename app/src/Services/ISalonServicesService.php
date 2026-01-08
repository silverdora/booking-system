<?php

namespace App\Services;

use App\Models\SalonServiceModel;

interface ISalonServicesService
{
    /** @return SalonServiceModel[] */
    public function getAllBySalonId(int $salonId): array;
    public function getById(int $salonId, int $id): ?SalonServiceModel;
    public function create(int $salonId, SalonServiceModel $service): void;
    public function update(int $salonId, int $id, SalonServiceModel $service): void;
    public function delete(int $salonId, int $id): void;
}

