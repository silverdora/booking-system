<?php
namespace App\Services;

use App\Models\SalonModel;

interface ISalonService
{
    public function getAll(): array;

    public function create(SalonModel $salon, int $ownerId): int;

    public function getById(int $id): ?SalonModel;

    public function update(int $id, SalonModel $Salon): void;

    public function delete(int $id): void;
}