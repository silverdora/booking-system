<?php
namespace App\Services;

use App\Services\ISalonService;
use App\Models\SalonModel;
use App\Repositories\ISalonRepository;
use App\Repositories\SalonRepository;

class SalonService implements ISalonService
{
    private ISalonRepository $salonRepository;
    public function __construct()
    {
        $this->salonRepository = new SalonRepository();
    }
    public function getAll(): array
    {
        return $this->salonRepository->getAll();
    }
    public function create(SalonModel $salon): void
    {
        $this->salonRepository->create($salon);
    }

    public function getById(int $id): ?SalonModel
    {
        return $this->salonRepository->getById($id);
    }

    public function update(int $id, SalonModel $salon): void
    {
        $this->salonRepository->update($id, $salon);
    }

    public function delete(int $id): void
    {
        $this->salonRepository->delete($id);
    }
}

