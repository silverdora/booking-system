<?php

namespace App\Services;

use App\Models\AppointmentModel;

interface IAppointmentsService
{
    /** @return AppointmentModel[] */
    public function getAllBySalonId(int $salonId): array;
    public function getById(int $salonId, int $id): ?AppointmentModel;
    public function create(int $salonId, AppointmentModel $appointment): void;
    public function update(int $salonId, int $id, AppointmentModel $appointment): void;
    public function delete(int $salonId, int $id): void;
    public function getServiceOptions(int $salonId): array;
    public function getSpecialistOptions(int $salonId): array;
    public function getCustomerOptions(): array;

}

