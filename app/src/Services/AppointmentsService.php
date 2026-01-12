<?php

namespace App\Services;

use App\Models\AppointmentModel;
use App\Repositories\IAppointmentsRepository;
use App\Repositories\AppointmentsRepository;
use App\Repositories\ISalonServicesRepository;
use App\Repositories\SalonServicesRepository;
use App\Repositories\IUsersRepository;
use App\Repositories\UsersRepository;

class AppointmentsService implements IAppointmentsService
{
    private IAppointmentsRepository $appointmentsRepository;
    private ISalonServicesRepository $salonServicesRepository;
    private IUsersRepository $usersRepository;

    public function __construct()
    {
        $this->appointmentsRepository = new AppointmentsRepository();
        $this->salonServicesRepository = new SalonServicesRepository();
        $this->usersRepository = new UsersRepository();
    }

    public function getAllBySalonId(int $salonId): array
    {
        return $this->appointmentsRepository->getAllBySalonId($salonId);
    }

    public function getById(int $salonId, int $id): ?AppointmentModel
    {
        return $this->appointmentsRepository->getById($salonId, $id);
    }

    public function create(int $salonId, AppointmentModel $appointment): void
    {
        $appointment->salonId = $salonId;
        $this->validate($appointment);
        // server-side availability validation
        if (!$this->appointmentsRepository->isSpecialistAvailable($salonId, $appointment->specialistId, $appointment->startsAt)) {
            throw new \InvalidArgumentException('This specialist is not available for the selected time slot.');
        }
        $this->appointmentsRepository->create($appointment);
    }

    public function update(int $salonId, int $id, AppointmentModel $appointment): void
    {
        // enforce relationship in service layer
        $appointment->salonId = $salonId;
        $appointment->id = $id;
        $this->validate($appointment);

        if (!$this->appointmentsRepository->isSpecialistAvailable($salonId, $appointment->specialistId, $appointment->startsAt, $id)) {
            throw new \InvalidArgumentException('This specialist is not available for the selected time slot.');
        }


        $this->appointmentsRepository->update($salonId, $id, $appointment);
    }

    public function delete(int $salonId, int $id): void
    {
        $this->appointmentsRepository->delete($salonId, $id);
    }

    private function validate(AppointmentModel $appointment): void
    {
        $errors = [];

        if ($appointment->serviceId <= 0) $errors[] = 'Service is required.';
        if ($appointment->specialistId <= 0) $errors[] = 'Specialist is required.';
        if ($appointment->customerId <= 0) $errors[] = 'Customer is required.';

        if (trim($appointment->startsAt) === '' ) {
            $errors[] = 'Start time is required.';
        } else {
            $start = strtotime($appointment->startsAt);
            if ($start === false) $errors[] = 'Invalid date/time format.';
        }

        if (!empty($errors)) {
            // join so controller can split OR just display as a list
            throw new \InvalidArgumentException(implode("\n", $errors));
        }
    }

    public function getServiceOptions(int $salonId): array
    {
        return $this->salonServicesRepository->getOptionsBySalonId($salonId);
    }

    public function getSpecialistOptions(int $salonId): array
    {
        return $this->usersRepository->getSpecialistOptions($salonId);
    }

    public function getCustomerOptions(): array
    {
        return $this->usersRepository->getCustomerOptions();
    }

}

