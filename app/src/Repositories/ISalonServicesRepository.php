<?php

namespace App\Repositories;

use App\Models\SalonServiceModel;

interface ISalonServicesRepository
{
    /** @return SalonServiceModel[] */
    public function getAllBySalonId(int $salonId): array;
    public function getById(int $salonId, int $id): ?SalonServiceModel;
    public function create(SalonServiceModel $service): void;
    public function update(int $salonId, int $id, SalonServiceModel $service): void;
    public function delete(int $salonId, int $id): void;
    public function getOptionsBySalonId(int $salonId): array;
    public function getNameById(int $id): ?string;
    /** @return int[] */
    public function getAssignedSpecialistIds(int $serviceId): array;

    public function setAssignedSpecialists(int $serviceId, array $specialistIds): void;

    /** @return array<int, array{id:int,name:string}> */
    public function getSpecialistsForService(int $serviceId): array;
    public function createWithSpecialists(SalonServiceModel $service, array $specialistIds): void;

    public function updateWithSpecialists(int $salonId, int $id, SalonServiceModel $service, array $specialistIds): void;

}

