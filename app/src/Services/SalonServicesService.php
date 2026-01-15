<?php

namespace App\Services;

use App\Models\SalonServiceModel;
use App\Repositories\ISalonServicesRepository;
use App\Repositories\SalonServicesRepository;
use App\Repositories\IUsersRepository;
use App\Repositories\UsersRepository;
class SalonServicesService implements ISalonServicesService
{
    private ISalonServicesRepository $salonServicesRepository;
    private IUsersRepository $usersRepository;
    public function __construct()
    {
        $this->salonServicesRepository = new SalonServicesRepository();
        $this->usersRepository = new UsersRepository();
    }

    public function getAllBySalonId(int $salonId): array
    {
        return $this->salonServicesRepository->getAllBySalonId($salonId);
    }

    public function getById(int $salonId, int $id): ?SalonServiceModel
    {
        return $this->salonServicesRepository->getById($salonId, $id);
    }

    public function getSpecialistOptions(int $salonId): array
    {
        return $this->usersRepository->getSpecialistOptions($salonId);
    }

    public function getAssignedSpecialistIds(int $serviceId): array
    {
        return $this->salonServicesRepository->getAssignedSpecialistIds($serviceId);
    }

    public function getSpecialistsForService(int $serviceId): array
    {
        return $this->salonServicesRepository->getSpecialistsForService($serviceId);
    }

    public function create(int $salonId, SalonServiceModel $service, array $specialistIds): void
    {
        $service->salonId = $salonId;

        if (trim($service->name) === '') {
            throw new \InvalidArgumentException('Service name is required.');
        }

        $specialistIds = $this->normalizeSpecialistIds($specialistIds);
        $this->validateSpecialistsBelongToSalon($salonId, $specialistIds);

        $this->salonServicesRepository->createWithSpecialists($service, $specialistIds);
    }


    public function update(int $salonId, int $id, SalonServiceModel $service, array $specialistIds): void
    {
        if (trim($service->name) === '') {
            throw new \InvalidArgumentException('Service name is required.');
        }

        $specialistIds = $this->normalizeSpecialistIds($specialistIds);
        $this->validateSpecialistsBelongToSalon($salonId, $specialistIds);

        $this->salonServicesRepository->updateWithSpecialists($salonId, $id, $service, $specialistIds);
    }


    public function delete(int $salonId, int $id): void
    {
        $this->salonServicesRepository->delete($salonId, $id);
    }

    private function normalizeSpecialistIds(array $ids): array
    {
        $ids = array_values(array_unique(array_map('intval', $ids)));
        $ids = array_filter($ids, fn($x) => $x > 0);

        return array_values($ids);
    }
    private function validateSpecialistsBelongToSalon(int $salonId, array $specialistIds): void
    {
        if (empty($specialistIds)) {
            throw new \InvalidArgumentException('Please select at least one specialist.');
        }

        $options = $this->getSpecialistOptions($salonId);
        $allowedIds = array_map(fn($o) => (int)$o['id'], $options);
        $allowedSet = array_flip($allowedIds);

        foreach ($specialistIds as $sid) {
            if (!isset($allowedSet[$sid])) {
                throw new \InvalidArgumentException('Invalid specialist selected.');
            }
        }
    }
}

