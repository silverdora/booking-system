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

    /**
     * @throws \Throwable
     */
    public function create(SalonModel $salon, int $ownerId): int
    {
        $this->validateSalon($salon);
        return $this->salonRepository->createAndAssignToOwner($salon, $ownerId);
    }



    public function update(int $id, SalonModel $salon): void
    {
        $existing = $this->salonRepository->getById($id);
        if (!$existing) {
            throw new \InvalidArgumentException('Salon not found');
        }

        $this->validateSalon($salon);
        $this->salonRepository->update($id, $salon);
    }

    /**
     * @throws \InvalidArgumentException
     */
    private function validateSalon(SalonModel $salon): void
    {
        // sanitize
        $salon->name    = trim((string)($salon->name ?? ''));
        $salon->type    = trim((string)($salon->type ?? ''));
        $salon->city    = trim((string)($salon->city ?? ''));
        $salon->address = trim((string)($salon->address ?? ''));
        $salon->phone   = trim((string)($salon->phone ?? ''));
        $salon->email   = trim((string)($salon->email ?? ''));

        // presence (REQUIRED)
        if (
            $salon->name === '' ||
            $salon->city === '' ||
            $salon->address === '' ||
            $salon->phone === '' ||
            $salon->email === ''
        ) {
            throw new \InvalidArgumentException('Missing required fields');
        }

        // email type
        if (!filter_var($salon->email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('Invalid email format');
        }

        // phone type
        if (!preg_match('/^[0-9\-\+\(\)\s\.]+$/', $salon->phone)) {
            throw new \InvalidArgumentException('Invalid phone format');
        }

        $digitsOnly = preg_replace('/\D+/', '', $salon->phone) ?? '';
        if (mb_strlen($digitsOnly) < 7) {
            throw new \InvalidArgumentException('Invalid phone format');
        }

        // normalize phone
        $salon->phone = preg_replace('/\s+/', ' ', trim($salon->phone));
    }


    public function getById(int $id): ?SalonModel
    {
        return $this->salonRepository->getById($id);
    }

    public function delete(int $id): void
    {
        $this->salonRepository->delete($id);
    }
}

