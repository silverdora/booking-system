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


    public function create(SalonModel $salon, int $ownerId): int
    {
        $sql = 'INSERT INTO salons (ownerId, name, type, address, city, phone, email)
            VALUES (:ownerId, :name, :type, :address, :city, :phone, :email)';

        $stmt = $this->getConnection()->prepare($sql);
        $stmt->execute([
            ':ownerId' => $ownerId,
            ':name' => $salon->name,
            ':type' => $salon->type,
            ':address' => $salon->address,
            ':city' => $salon->city,
            ':phone' => $salon->phone,
            ':email' => $salon->email,
        ]);

        return (int)$this->getConnection()->lastInsertId();
    }

    public function createAndAssignToOwner(SalonModel $salon, int $ownerId): int
    {
        $pdo = $this->getConnection();
        $pdo->beginTransaction();

        try {
            $newSalonId = $this->create($salon, $ownerId);

            $stmt = $pdo->prepare('UPDATE users SET salonId = :salonId WHERE id = :userId');
            $stmt->execute([
                ':salonId' => $newSalonId,
                ':userId' => $ownerId,
            ]);

            $pdo->commit();
            return $newSalonId;

        } catch (\Throwable $e) {
            $pdo->rollBack();
            throw $e;
        }
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
    public function getNameById(int $id): ?string
    {
        $sql = 'SELECT name FROM salons WHERE id = :id';
        $stmt = $this->getConnection()->prepare($sql);
        $stmt->execute([':id' => $id]);
        $name = $stmt->fetchColumn();
        return $name !== false ? (string)$name : null;
    }

    public function createWithOwnerAndAssignToUser(SalonModel $salon, int $ownerId): int
    {
        $pdo = $this->getConnection();
        $pdo->beginTransaction();

        try {
            // 1) insert salon
            $sql = 'INSERT INTO salons (name, type, address, city, phone, email, ownerId)
                VALUES (:name, :type, :address, :city, :phone, :email, :ownerId)';

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':name' => $salon->name,
                ':type' => $salon->type,
                ':address' => $salon->address,
                ':city' => $salon->city,
                ':phone' => $salon->phone,
                ':email' => $salon->email,
                ':ownerId' => $ownerId,
            ]);

            $newSalonId = (int)$pdo->lastInsertId();

            // 2) update user salonId
            $stmt2 = $pdo->prepare('UPDATE users SET salonId = :salonId WHERE id = :userId');
            $stmt2->execute([
                ':salonId' => $newSalonId,
                ':userId' => $ownerId,
            ]);

            $pdo->commit();
            return $newSalonId;

        } catch (\Throwable $e) {
            $pdo->rollBack();
            throw $e;
        }
    }


}