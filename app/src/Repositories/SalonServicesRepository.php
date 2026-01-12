<?php

namespace App\Repositories;

use App\Framework\Repository;
use App\Models\SalonServiceModel;
use PDO;

class SalonServicesRepository extends Repository implements ISalonServicesRepository
{
    public function getAllBySalonId(int $salonId): array
    {
        $sql = 'SELECT id, salonId, specialistId, name, price, durationMinutes
                FROM salonServices
                WHERE salonId = :salonId
                ORDER BY name';

        $stmt = $this->getConnection()->prepare($sql);
        $stmt->execute([':salonId' => $salonId]);

        return $stmt->fetchAll(PDO::FETCH_CLASS, '\App\Models\SalonServiceModel');
    }

    public function getById(int $salonId, int $id): ?SalonServiceModel
    {
        $sql = 'SELECT id, salonId, specialistId, name, price, durationMinutes
                FROM salonServices
                WHERE salonId = :salonId AND id = :id';

        $stmt = $this->getConnection()->prepare($sql);
        $stmt->execute([
            ':salonId' => $salonId,
            ':id' => $id,
        ]);

        $stmt->setFetchMode(PDO::FETCH_CLASS, '\App\Models\SalonServiceModel');
        $service = $stmt->fetch();

        return $service ?: null;
    }

    public function create(SalonServiceModel $service): void
    {
        $sql = 'INSERT INTO salonServices (salonId, specialistId, name, price, durationMinutes)
                VALUES (:salonId, :specialistId, :name, :price, :durationMinutes)';

        $stmt = $this->getConnection()->prepare($sql);
        $stmt->execute([
            ':salonId' => $service->salonId,
            ':specialistId' => $service->specialistId,
            ':name' => $service->name,
            ':price' => $service->price,
            ':durationMinutes' => $service->durationMinutes,
        ]);

        $service->id = (int)$this->getConnection()->lastInsertId();
    }

    public function update(int $salonId, int $id, SalonServiceModel $service): void
    {
        $sql = 'UPDATE salonServices
                SET name = :name,
                    specialistId = :specialistId,
                    price = :price,
                    durationMinutes = :durationMinutes
                WHERE salonId = :salonId AND id = :id';

        $stmt = $this->getConnection()->prepare($sql);
        $stmt->execute([
            ':salonId' => $salonId,
            ':specialistId' => $service->specialistId,
            ':id' => $id,
            ':name' => $service->name,
            ':price' => $service->price,
            ':durationMinutes' => $service->durationMinutes,
        ]);
    }

    public function delete(int $salonId, int $id): void
    {
        $sql = 'DELETE FROM salonServices WHERE salonId = :salonId AND id = :id';
        $stmt = $this->getConnection()->prepare($sql);
        $stmt->execute([
            ':salonId' => $salonId,
            ':id' => $id,
        ]);
    }

    public function getOptionsBySalonId(int $salonId): array
    {
        $sql = 'SELECT id, name
            FROM salonServices
            WHERE salonId = :salonId
            ORDER BY name';

        $stmt = $this->getConnection()->prepare($sql);
        $stmt->execute([':salonId' => $salonId]);

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(function ($row) {
            return [
                'id' => (int)$row['id'],
                'name' => (string)$row['name'],
            ];
        }, $rows);
    }

}

