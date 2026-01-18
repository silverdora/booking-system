<?php
namespace App\Repositories;

use App\Models\SalonModel;
use App\Framework\Repository;

interface ISalonRepository
{
    public function getAll(): array;
    public function create(SalonModel $salon, int $ownerId): int;


    public function getById(int $id): ?SalonModel;
    public function update(int $id, SalonModel $salon): void;
    public function delete(int $id): void;
    public function getNameById(int $id): ?string;
    public function createWithOwnerAndAssignToUser(SalonModel $salon, int $ownerId): int;
}
?>