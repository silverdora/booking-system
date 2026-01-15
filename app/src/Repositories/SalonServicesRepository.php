<?php

namespace App\Repositories;

use App\Framework\Repository;
use App\Models\SalonServiceModel;
use PDO;

class SalonServicesRepository extends Repository implements ISalonServicesRepository
{
    public function getAssignedSpecialistIds(int $serviceId): array
    {
        $sql = 'SELECT specialistId
            FROM specialistSalonServices
            WHERE serviceId = :serviceId';

        $stmt = $this->getConnection()->prepare($sql);
        $stmt->execute([':serviceId' => $serviceId]);

        return array_map('intval', $stmt->fetchAll(PDO::FETCH_COLUMN));
    }

    public function setAssignedSpecialists(int $serviceId, array $specialistIds): void
    {
        // Clean duplicates + normalize ints
        $specialistIds = array_values(array_unique(array_map('intval', $specialistIds)));
        $specialistIds = array_filter($specialistIds, fn($x) => $x > 0);

        // Remove all
        $sqlDelete = 'DELETE FROM specialistSalonServices WHERE serviceId = :serviceId';
        $stmtDel = $this->getConnection()->prepare($sqlDelete);
        $stmtDel->execute([':serviceId' => $serviceId]);

        // Insert selected
        if (empty($specialistIds)) {
            return;
        }

        $sqlInsert = 'INSERT INTO specialistSalonServices (serviceId, specialistId)
                  VALUES (:serviceId, :specialistId)';
        $stmtIns = $this->getConnection()->prepare($sqlInsert);

        foreach ($specialistIds as $sid) {
            $stmtIns->execute([
                ':serviceId' => $serviceId,
                ':specialistId' => $sid,
            ]);
        }
    }

    public function getSpecialistsForService(int $serviceId): array
    {
        $sql = 'SELECT u.id, u.firstName, u.lastName
            FROM specialistSalonServices sss
            INNER JOIN users u ON u.id = sss.specialistId
            WHERE sss.serviceId = :serviceId
              AND u.role = "specialist"
            ORDER BY u.lastName, u.firstName';

        $stmt = $this->getConnection()->prepare($sql);
        $stmt->execute([':serviceId' => $serviceId]);

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(function ($row) {
            $fullName = trim(($row['firstName'] ?? '') . ' ' . ($row['lastName'] ?? ''));
            return [
                'id' => (int)$row['id'],
                'name' => $fullName !== '' ? $fullName : ('Specialist #' . (int)$row['id']),
            ];
        }, $rows);
    }

    public function getNameById(int $id): ?string
    {
        $sql = 'SELECT name FROM salonServices WHERE id = :id';
        $stmt = $this->getConnection()->prepare($sql);
        $stmt->execute([':id' => $id]);
        $name = $stmt->fetchColumn();
        return $name !== false ? (string)$name : null;
    }

    public function getAllBySalonId(int $salonId): array
    {
        $sql = 'SELECT id, salonId, name, price, durationMinutes
                FROM salonServices
                WHERE salonId = :salonId
                ORDER BY name';

        $stmt = $this->getConnection()->prepare($sql);
        $stmt->execute([':salonId' => $salonId]);

        return $stmt->fetchAll(PDO::FETCH_CLASS, '\App\Models\SalonServiceModel');
    }

    public function getById(int $salonId, int $id): ?SalonServiceModel
    {
        $sql = 'SELECT id, salonId, name, price, durationMinutes
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
        $sql = 'INSERT INTO salonServices (salonId, name, price, durationMinutes)
                VALUES (:salonId, :name, :price, :durationMinutes)';

        $stmt = $this->getConnection()->prepare($sql);
        $stmt->execute([
            ':salonId' => $service->salonId,
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
                    price = :price,
                    durationMinutes = :durationMinutes
                WHERE salonId = :salonId AND id = :id';

        $stmt = $this->getConnection()->prepare($sql);
        $stmt->execute([
            ':salonId' => $salonId,
            ':id' => $id,
            ':name' => $service->name,
            ':price' => $service->price,
            ':durationMinutes' => $service->durationMinutes,
        ]);
    }

    public function createWithSpecialists(SalonServiceModel $service, array $specialistIds): void
    {
        $pdo = $this->getConnection();

        try {
            $pdo->beginTransaction();

            // Create service
            $sql = 'INSERT INTO salonServices (salonId, name, price, durationMinutes)
                VALUES (:salonId, :name, :price, :durationMinutes)';

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':salonId' => $service->salonId,
                ':name' => $service->name,
                ':price' => $service->price,
                ':durationMinutes' => $service->durationMinutes,
            ]);

            $service->id = (int)$pdo->lastInsertId();

            // Set specialists
            $this->setAssignedSpecialists((int)$service->id, $specialistIds);

            $pdo->commit();
        } catch (\Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            throw $e;
        }
    }

    public function updateWithSpecialists(int $salonId, int $id, SalonServiceModel $service, array $specialistIds): void
    {
        $pdo = $this->getConnection();

        try {
            $pdo->beginTransaction();

            // Update service
            $sql = 'UPDATE salonServices
                SET name = :name,
                    price = :price,
                    durationMinutes = :durationMinutes
                WHERE salonId = :salonId AND id = :id';

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':salonId' => $salonId,
                ':id' => $id,
                ':name' => $service->name,
                ':price' => $service->price,
                ':durationMinutes' => $service->durationMinutes,
            ]);

            // Update specialists
            $this->setAssignedSpecialists($id, $specialistIds);

            $pdo->commit();
        } catch (\Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            throw $e;
        }
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
        $sql = 'SELECT id, name, durationMinutes
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
                'durationMinutes' => (int)$row['durationMinutes']
            ];
        }, $rows);
    }

}

