<?php

namespace App\Services;

use App\Models\SalonServiceModel;

interface ISalonServicesService
{
    /** @return SalonServiceModel[] */
    public function getAllBySalonId(int $salonId): array;
    public function getById(int $salonId, int $id): ?SalonServiceModel;

    /** @return array<int, array{id:int,name:string}> */
    public function getSpecialistOptions(int $salonId): array;

    /** @return int[] */
    public function getAssignedSpecialistIds(int $serviceId): array;

    /** @return array<int, array{id:int,name:string}> */
    public function getSpecialistsForService(int $serviceId): array;

    public function create(int $salonId, SalonServiceModel $service, array $specialistIds): void;
    public function update(int $salonId, int $id, SalonServiceModel $service, array $specialistIds): void;

    public function delete(int $salonId, int $id): void;
}


