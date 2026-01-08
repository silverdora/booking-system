<?php

namespace App\Services;

use App\Models\SalonServiceModel;
use App\Repositories\ISalonServicesRepository;
use App\Repositories\SalonServicesRepository;

class SalonServicesService implements ISalonServicesService
{
    private ISalonServicesRepository $salonServicesRepository;

    public function __construct()
    {
        $this->salonServicesRepository = new SalonServicesRepository();
    }

    public function getAllBySalonId(int $salonId): array
    {
        return $this->salonServicesRepository->getAllBySalonId($salonId);
    }

    public function getById(int $salonId, int $id): ?SalonServiceModel
    {
        return $this->salonServicesRepository->getById($salonId, $id);
    }

    public function create(int $salonId, SalonServiceModel $service): void
    {
        // enforce relationship in service layer
        $service->salonId = $salonId;

        if (trim($service->name) === '') {
            throw new \InvalidArgumentException('Service name is required.');
        }

        $this->salonServicesRepository->create($service);
    }

    public function update(int $salonId, int $id, SalonServiceModel $service): void
    {
        if (trim($service->name) === '') {
            throw new \InvalidArgumentException('Service name is required.');
        }

        $this->salonServicesRepository->update($salonId, $id, $service);
    }

    public function delete(int $salonId, int $id): void
    {
        $this->salonServicesRepository->delete($salonId, $id);
    }
}

