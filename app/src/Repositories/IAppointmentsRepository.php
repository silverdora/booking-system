<?php

namespace App\Repositories;

use App\Models\AppointmentModel;

interface IAppointmentsRepository
{
    /** @return AppointmentModel[] */
    public function getAllBySalonId(int $salonId): array;
    public function getById(int $salonId, int $id): ?AppointmentModel;
    public function create(AppointmentModel $appointment): void;
    public function update(int $salonId, int $id, AppointmentModel $appointment): void;
    public function delete(int $salonId, int $id): void;
    public function isSpecialistAvailable(
        int $salonId,
        int $specialistId,
        string $startsAt,
        ?int $ignoreAppointmentId = null
    ): bool;
}


