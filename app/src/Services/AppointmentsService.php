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
    private const WORK_START = '09:00:00';
    private const WORK_END = '21:00:00';
    private const SLOT_MINUTES = 15;


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
        if (!$this->appointmentsRepository->isSpecialistAvailable(
            $salonId,
            $appointment->specialistId,
            $appointment->startsAt,
            $appointment->endsAt
        )) {
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

        if (!$this->appointmentsRepository->isSpecialistAvailable(
            $salonId,
            $appointment->specialistId,
            $appointment->startsAt,
            $appointment->endsAt,
            $id
        )) {
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
        if ($appointment->customerId <= 0) $errors[] = 'Client is required.';

        if (trim($appointment->startsAt) === '' || trim($appointment->endsAt) === '') {
            $errors[] = 'Start and end time are required.';
        } else {
            $startTs = strtotime($appointment->startsAt);
            $endTs = strtotime($appointment->endsAt);

            if ($startTs === false || $endTs === false) {
                $errors[] = 'Invalid date/time format.';
            } else if ($endTs <= $startTs) {
                $errors[] = 'End time must be after start time.';
            } else {
                // Check working hours boundaries
                $startTime = date('H:i:s', $startTs);
                $endTime = date('H:i:s', $endTs);

                if ($startTime < self::WORK_START) {
                    $errors[] = 'Start time cannot be earlier than 09:00.';
                }
                if ($endTime > self::WORK_END) {
                    $errors[] = 'End time cannot be later than 21:00.';
                }
            }
        }

        if (!empty($errors)) {
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
    /**
     * @return array<int, array{startsAt:string, endsAt:string}>
     */
    public function getAvailableSlotsBySpecialist(
        int $salonId,
        int $specialistId,
        string $date,
        int $durationMinutes
    ): array {
        if ($durationMinutes <= 0) {
            throw new \InvalidArgumentException('Invalid service duration.');
        }

        $appointments = $this->appointmentsRepository
            ->getAppointmentsBySpecialistAndDate($salonId, $specialistId, $date);

        $workStart = new \DateTimeImmutable($date . ' ' . self::WORK_START);
        $workEnd = new \DateTimeImmutable($date . ' ' . self::WORK_END);

        $step = new \DateInterval('PT' . self::SLOT_MINUTES . 'M');
        $len = new \DateInterval('PT' . (int)$durationMinutes . 'M');

        $available = [];

        for ($slotStart = $workStart; $slotStart < $workEnd; $slotStart = $slotStart->add($step)) {
            $slotEnd = $slotStart->add($len);

            // услуга должна закончиться не позже 21:00
            if ($slotEnd > $workEnd) {
                break;
            }

            $conflict = false;

            foreach ($appointments as $appt) {
                $apptStart = new \DateTimeImmutable((string)$appt->startsAt);
                $apptEnd = new \DateTimeImmutable((string)$appt->endsAt);

                // Пересечение интервалов:
                // slotStart < apptEnd && slotEnd > apptStart
                if ($slotStart < $apptEnd && $slotEnd > $apptStart) {
                    $conflict = true;
                    break;
                }
            }

            if (!$conflict) {
                $available[] = [
                    'startsAt' => $slotStart->format('Y-m-d H:i:s'),
                    'endsAt' => $slotEnd->format('Y-m-d H:i:s'),
                ];
            }
        }

        return $available;
    }
    /**
     * @return array<int, array{
     *   specialist: array{id:int, name:string},
     *   slots: array<int, array{startsAt:string, endsAt:string}>
     * }>
     */
    public function getSpecialistsWithSlots(int $salonId, int $serviceId, string $date): array
    {

        $service = $this->salonServicesRepository->getById($salonId, $serviceId);
        if (!$service) {
            throw new \InvalidArgumentException('Service not found.');
        }

        $duration = (int)$service->durationMinutes;
        if ($duration <= 0) {
            throw new \InvalidArgumentException('Service duration is required.');
        }

        $specialists = $this->usersRepository->getSpecialistOptionsBySalonService($salonId, $serviceId);

        $result = [];

        foreach ($specialists as $sp) {
            $slots = $this->getAvailableSlotsBySpecialist(
                $salonId,
                (int)$sp['id'],
                $date,
                $duration
            );

            $result[] = [
                'specialist' => $sp,
                'slots' => $slots,
            ];
        }

        return $result;
    }



}

