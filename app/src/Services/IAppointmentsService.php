<?php

namespace App\Services;

use App\Models\AppointmentModel;
use App\Models\SalonServiceModel;

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
    /**
     * @return array<int, array{startsAt:string, endsAt:string}>
     */
    public function getAvailableSlotsBySpecialist(
        int $salonId,
        int $specialistId,
        string $date,
        int $durationMinutes
    ): array;

    //public function getServiceById(int $salonId, int $serviceId): ?SalonServiceModel;
    /** @return AppointmentModel[] */
    public function getAllByCustomerId(int $customerId): array;
    public function getByIdForCustomer(int $customerId, int $id): ?AppointmentModel;
}

