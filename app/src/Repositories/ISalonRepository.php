<?php
namespace App\Repositories;

use App\Models\SalonModel;
use App\Framework\Repository;

interface ISalonRepository
{
    public function getAll(): array;
    public function create(SalonModel $salon): void;
    public function get(int $id): ?ArticleModel;
    public function update(SalonModel $salon): void;
    public function delete(int $id): void;
}
?>