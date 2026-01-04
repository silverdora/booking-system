<?php
namespace App\Repositories;

use App\Repositories\ISalonRepository;
use App\Models\SalonModel;
use App\Framework\Repository;


class SalonRepository extends Repository implements ISalonRepository
{
    public function getAll(): array
    {
        $sql = 'SELECT id, name, type, phone, email, city, address FROM salons';
        $result = $this->getConnection()->query($sql);

        return $result->fetchAll(\PDO::FETCH_CLASS, '\App\Models\SalonModel');
    }


    public function create(SalonModel $salon): void
    {
        // TODO: Implement create() method.
    }

    public function get(int $id): ?ArticleModel
    {
        // TODO: Implement get() method.
    }

    public function update(SalonModel $salon): void
    {
        // TODO: Implement update() method.
    }

    public function delete(int $id): void
    {
        // TODO: Implement delete() method.
    }
}