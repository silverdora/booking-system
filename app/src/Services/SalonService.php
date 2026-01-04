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

    public function get(int $id): ?SalonModel
    {
        return $this->salonRepository->get($id);
    }

    public function update(SalonModel $salon): void
    {
        $this->salonRepository->update($salon);
    }

    public function delete(int $id): void
    {
        $this->salonRepository->delete($id);
    }
}

