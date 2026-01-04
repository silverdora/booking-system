<?php
namespace App\Services;

use App\Models\SalonModel;

interface ISalonService
{
    public function getAll(): array;

    public function create(SalonModel $salon): void;

    public function get(int $id): ?SalonModel;

    public function update(SalonModel $Salon): void;

    public function delete(int $id): void;
}