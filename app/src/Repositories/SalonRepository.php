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
        $sql = 'INSERT INTO salons (name, type, address, city, phone, email) VALUES (:name, :type, :address, :city, :phone, :email)';
        $stmt = $this->getConnection()->prepare($sql);
        $stmt->bindParam(':name', $salon->name, \PDO::PARAM_STR);
        $stmt->bindParam(':type', $salon->type, \PDO::PARAM_STR);
        $stmt->bindParam(':address', $salon->address, \PDO::PARAM_STR);
        $stmt->bindParam(':city', $salon->city, \PDO::PARAM_STR);
        $stmt->bindParam(':phone', $salon->phone, \PDO::PARAM_STR);
        $stmt->bindParam(':email', $salon->email, \PDO::PARAM_STR);

        $stmt->execute();
    }

    public function getById(int $id): ?SalonModel
    {
        $sql = 'SELECT id, name, type, phone, email, city, address FROM salons WHERE id = :id';

        $stmt = $this->getConnection()->prepare($sql);
        $stmt->bindValue('id', $id, \PDO::PARAM_INT);
        $stmt->execute();

        $stmt->setFetchMode(\PDO::FETCH_CLASS, '\App\Models\SalonModel');
        $salon = $stmt->fetch();

        return $salon ?: null;
    }

    public function update(int $id, SalonModel $salon): void
    {
        $sql = 'UPDATE salons
            SET name = :name,
                type = :type,
                address = :address,
                city = :city,
                phone = :phone,
                email = :email
            WHERE id = :id';

        $stmt = $this->getConnection()->prepare($sql);

        $stmt->execute([
            ':id' => $id,
            ':name' => $salon->name,
            ':type' => $salon->type,
            ':address' => $salon->address,
            ':city' => $salon->city,
            ':phone' => $salon->phone,
            ':email' => $salon->email,
        ]);
    }


    public function delete(int $id): void
    {
        $sql = 'DELETE FROM salons WHERE id = :id';
        $stmt = $this->getConnection()->prepare($sql);
        $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
    }
}