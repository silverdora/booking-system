<?php

namespace App\Repositories;

use App\Framework\Repository;
use App\Models\AppointmentModel;
use PDO;

class AppointmentsRepository extends Repository implements IAppointmentsRepository
{
    public function getAllBySalonId(int $salonId): array
    {
        $sql = 'SELECT
                    id,
                    salonId AS salonId,
                    serviceId AS serviceId,
                    specialistId AS specialistId,
                    customerId AS customerId,
                    startsAt AS startsAt,
                    endsAt AS endsAt
                FROM appointments
                WHERE salonId = :salonId
                ORDER BY startsAt';

        $stmt = $this->getConnection()->prepare($sql);
        $stmt->execute([':salonId' => $salonId]);

        return $stmt->fetchAll(PDO::FETCH_CLASS, '\App\Models\AppointmentModel');
    }

    public function getById(int $salonId, int $id): ?AppointmentModel
    {
        $sql = 'SELECT
                    id,
                    salonId AS salonId,
                    serviceId AS serviceId,
                    specialistId AS specialistId,
                    customerId AS customerId,
                    startsAt AS startsAt,
                    endsAt AS endsAt
                FROM appointments
                WHERE salonId = :salonId AND id = :id';

        $stmt = $this->getConnection()->prepare($sql);
        $stmt->execute([
            ':salonId' => $salonId,
            ':id' => $id,
        ]);

        $stmt->setFetchMode(PDO::FETCH_CLASS, '\App\Models\AppointmentModel');
        $appointment = $stmt->fetch();

        return $appointment ?: null;
    }

    public function create(AppointmentModel $appointment): void
    {
        $sql = 'INSERT INTO appointments
                    (salonId, serviceId, specialistId, customerId, startsAt, endsAt)
                VALUES
                    (:salonId, :serviceId, :specialistId, :customerId, :startsAt, :endsAt)';

        $stmt = $this->getConnection()->prepare($sql);
        $stmt->execute([
            ':salonId' => $appointment->salonId,
            ':serviceId' => $appointment->serviceId,
            ':specialistId' => $appointment->specialistId,
            ':customerId' => $appointment->customerId,
            ':startsAt' => $appointment->startsAt,
            ':endsAt' => $appointment->endsAt
        ]);

        $appointment->id = (int)$this->getConnection()->lastInsertId();
    }

    public function update(int $salonId, int $id, AppointmentModel $appointment): void
    {
        $sql = 'UPDATE appointments
            SET serviceId = :serviceId,
                specialistId = :specialistId,
                customerId = :customerId,
                startsAt = :startsAt,
                endsAt = :endsAt
            WHERE salonId = :salonId AND id = :id';

        $stmt = $this->getConnection()->prepare($sql);
        $stmt->execute([
            ':salonId' => $salonId,
            ':id' => $id,
            ':serviceId' => $appointment->serviceId,
            ':specialistId' => $appointment->specialistId,
            ':customerId' => $appointment->customerId,
            ':startsAt' => $appointment->startsAt,
            ':endsAt' => $appointment->endsAt
        ]);
    }


    public function delete(int $salonId, int $id): void
    {
        $sql = 'DELETE FROM appointments WHERE salonId = :salonId AND id = :id';

        $stmt = $this->getConnection()->prepare($sql);
        $stmt->execute([
            ':salonId' => $salonId,
            ':id' => $id,
        ]);
    }
    public function isSpecialistAvailable(
        int $salonId,
        int $specialistId,
        string $startsAt,
        string $endsAt,
        ?int $ignoreAppointmentId = null
    ): bool {
        $sql = 'SELECT COUNT(*)
            FROM appointments
            WHERE salonId = :salonId
              AND specialistId = :specialistId
              AND startsAt < :endsAt
              AND endsAt > :startsAt';

        $params = [
            ':salonId' => $salonId,
            ':specialistId' => $specialistId,
            ':startsAt' => $startsAt,
            ':endsAt' => $endsAt,
        ];

        if ($ignoreAppointmentId !== null) {
            $sql .= ' AND id != :ignoreId';
            $params[':ignoreId'] = $ignoreAppointmentId;
        }

        $stmt = $this->getConnection()->prepare($sql);
        $stmt->execute($params);

        return ((int)$stmt->fetchColumn()) === 0;
    }


    public function getAppointmentsBySpecialistAndDate(int $salonId, int $specialistId, string $date): array
    {
        // date = 'YYYY-MM-DD'
        $from = $date . ' 00:00:00';
        $to = $date . ' 23:59:59';

        $sql = 'SELECT id, salonId, serviceId, specialistId, customerId, startsAt, endsAt
            FROM appointments
            WHERE salonId = :salonId
              AND specialistId = :specialistId
              AND startsAt BETWEEN :from AND :to
            ORDER BY startsAt';

        $stmt = $this->getConnection()->prepare($sql);
        $stmt->execute([
            ':salonId' => $salonId,
            ':specialistId' => $specialistId,
            ':from' => $from,
            ':to' => $to,
        ]);

        return $stmt->fetchAll(PDO::FETCH_CLASS, '\App\Models\AppointmentModel');
    }



}
