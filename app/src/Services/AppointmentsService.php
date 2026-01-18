<?php

namespace App\Services;

use App\Models\AppointmentModel;
use App\Repositories\IAppointmentsRepository;
use App\Repositories\AppointmentsRepository;
use App\Repositories\ISalonRepository;
use App\Repositories\ISalonServicesRepository;
use App\Repositories\SalonRepository;
use App\Repositories\SalonServicesRepository;
use App\Repositories\IUsersRepository;
use App\Repositories\UsersRepository;
use App\ViewModels\AppointmentDetailViewModel;
use App\ViewModels\AppointmentsListItemViewModel;
use App\ViewModels\AppointmentsViewModel;

class AppointmentsService implements IAppointmentsService
{
    private IAppointmentsRepository $appointmentsRepository;
    private ISalonServicesRepository $salonServicesRepository;
    private IUsersRepository $usersRepository;
    private ISalonRepository $salonRepository;
    private const WORK_START = '09:00:00';
    private const WORK_END = '21:00:00';
    private const SLOT_MINUTES = 15;

    /** @var array<int, string> */
    private array $serviceNameCache = [];
    /** @var array<int, string> */
    private array $userNameCache = [];
    /** @var array<int, string> */
    private array $salonNameCache = [];


    public function __construct()
    {
        $this->appointmentsRepository = new AppointmentsRepository();
        $this->salonServicesRepository = new SalonServicesRepository();
        $this->usersRepository = new UsersRepository();
        $this->salonRepository = new SalonRepository();
    }
    public function buildIndexViewModelForSpecialist(int $salonId, int $specialistId, string $viewMode = 'week', string $baseDate = ''): AppointmentsViewModel
    {
        $base = $this->parseBaseDate($baseDate);
        [$rangeStart, $rangeEnd, $days] = $this->getRangeAndDays($viewMode, $base);

        $appointments = $this->appointmentsRepository->getAllBySalonIdAndSpecialistId($salonId, $specialistId);

        $filtered = array_values(array_filter($appointments, function($a) use ($rangeStart, $rangeEnd) {
            $ts = strtotime((string)$a->startsAt);
            if ($ts === false) return false;
            return $ts >= $rangeStart->getTimestamp() && $ts <= $rangeEnd->getTimestamp();
        }));

        $items = array_map(fn(\App\Models\AppointmentModel $a) => $this->mapListItem($a), $filtered);

        // schedule grid
        $schedule = [];
        foreach ($days as $day) {
            $schedule[$day] = [];

            $t = new \DateTimeImmutable($day . ' ' . self::WORK_START);
            $end = new \DateTimeImmutable($day . ' ' . self::WORK_END);
            $step = new \DateInterval('PT' . self::SLOT_MINUTES . 'M');

            while ($t < $end) {
                $schedule[$day][$t->format('H:i')] = [];
                $t = $t->add($step);
            }
        }

        $times = [];
        if (!empty($days) && isset($schedule[$days[0]]) && is_array($schedule[$days[0]])) {
            $times = array_keys($schedule[$days[0]]);
        }

        foreach ($items as $it) {
            $startTs = strtotime((string)$it->appointment->startsAt);
            if ($startTs === false) continue;

            $day = date('Y-m-d', $startTs);
            $hm  = date('H:i', $startTs);

            if (isset($schedule[$day][$hm])) {
                $schedule[$day][$hm][] = $it;
            }
        }

        $specialistName = $this->getUserName($specialistId, 'Specialist');
        $title = "My appointments — {$specialistName}";

        // specialist doesn't see owner links
        $ownerLinks = [];

        return new AppointmentsViewModel(
            $salonId,
            $items,
            $title,
            false,  // isCustomer
            false,  // canCreate
            false,  // canManage
            false,  // canCancel
            null,
            null,
            true,

            $viewMode,
            $base->format('Y-m-d'),
            $days,
            $schedule,
            $ownerLinks,
            $times
        );
    }

    public function deleteByCustomer(int $customerId, int $appointmentId): void
    {
        // check that appointment exists and belongs to this customer
        $appt = $this->appointmentsRepository->getByIdForCustomer($customerId, $appointmentId);
        if (!$appt) {
            throw new \InvalidArgumentException('Appointment not found.');
        }

        $this->appointmentsRepository->deleteByCustomer($customerId, $appointmentId);
    }

    public function buildIndexViewModelForCustomer(int $customerId): AppointmentsViewModel
    {
        $appointments = $this->appointmentsRepository->getAllByCustomerId($customerId);

        $items = array_map(fn(AppointmentModel $a) => $this->mapListItem($a), $appointments);

        return new AppointmentsViewModel(
            null,
            $items,
            'My appointments',
            true,
            true,
            false,
            'Book new appointment',
            '/salons',
            false,
            true // canCancel
        );

    }
    private function parseBaseDate(string $baseDate): \DateTimeImmutable
    {
        if ($baseDate === '' || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $baseDate)) {
            return new \DateTimeImmutable('today');
        }
        $dt = \DateTimeImmutable::createFromFormat('Y-m-d', $baseDate);
        return $dt ?: new \DateTimeImmutable('today');
    }

    /** @return array{\DateTimeImmutable,\DateTimeImmutable,string[]} [start,end,days] */
    private function getRangeAndDays(string $viewMode, \DateTimeImmutable $base): array
    {
        $viewMode = strtolower($viewMode);
        if ($viewMode !== 'day' && $viewMode !== 'week') {
            $viewMode = 'week';
        }

        if ($viewMode === 'day') {
            $start = $base->setTime(0, 0, 0);
            $end   = $base->setTime(23, 59, 59);
            return [$start, $end, [$base->format('Y-m-d')]];
        }

        // week Monday..Sunday
        $monday = $base->modify('monday this week')->setTime(0, 0, 0);
        $sunday = $monday->modify('+6 days')->setTime(23, 59, 59);

        $days = [];
        for ($d = $monday; $d <= $sunday; $d = $d->modify('+1 day')) {
            $days[] = $d->format('Y-m-d');
        }

        return [$monday, $sunday, $days];
    }

    public function buildIndexViewModelForSalon(int $salonId, string $viewMode = 'week', string $baseDate = ''): AppointmentsViewModel
    {
        $base = $this->parseBaseDate($baseDate);
        [$rangeStart, $rangeEnd, $days] = $this->getRangeAndDays($viewMode, $base);


        $appointments = $this->appointmentsRepository->getAllBySalonId($salonId);


        $filtered = array_values(array_filter($appointments, function($a) use ($rangeStart, $rangeEnd) {
            $ts = strtotime((string)$a->startsAt);
            if ($ts === false) return false;
            return $ts >= $rangeStart->getTimestamp() && $ts <= $rangeEnd->getTimestamp();
        }));


        $items = array_map(fn(\App\Models\AppointmentModel $a) => $this->mapListItem($a), $filtered);

        // Build schedule grid: schedule[day][time] = items starting at that time
        $schedule = [];

        if (!empty($days) && isset($schedule[$days[0]]) && is_array($schedule[$days[0]])) {
            $times = array_keys($schedule[$days[0]]);
        }

        foreach ($days as $day) {
            $schedule[$day] = [];

            $t = new \DateTimeImmutable($day . ' ' . self::WORK_START);
            $end = new \DateTimeImmutable($day . ' ' . self::WORK_END);
            $step = new \DateInterval('PT' . self::SLOT_MINUTES . 'M');

            while ($t < $end) {
                $schedule[$day][$t->format('H:i')] = [];
                $t = $t->add($step);
            }
        }

        $times = [];
        if (!empty($days) && isset($schedule[$days[0]]) && is_array($schedule[$days[0]])) {
            $times = array_keys($schedule[$days[0]]);
        }
        foreach ($items as $it) {
            $startTs = strtotime((string)$it->appointment->startsAt);
            if ($startTs === false) continue;

            $day = date('Y-m-d', $startTs);
            $hm = date('H:i', $startTs);

            if (isset($schedule[$day][$hm])) {
                $schedule[$day][$hm][] = $it;
            }
        }

        // Title
        $salonName = $this->getSalonName($salonId);
        $title = $salonName !== '' ? "Appointments — {$salonName}" : "Appointments (Salon #{$salonId})";

        // Owner links on same page
        $ownerLinks = [
            'staff' => "/salons/{$salonId}/staff",
            'services' => "/salons/{$salonId}/services",
            'editSalon' => "/salons/{$salonId}/edit",
        ];

        return new AppointmentsViewModel(
            $salonId,
            $items,
            $title,
            false,  // isCustomer
            true,   // canCreate
            true,   // canManage
            false,  // canCancel
            'Create appointment',
            '/appointments/receptionist/create',
            true,   // showBackToSalonLink

            $viewMode,
            $base->format('Y-m-d'),
            $days,
            $schedule,
            $ownerLinks,
            $times
        );
    }


    public function buildDetailViewModelForCustomer(int $customerId, int $appointmentId): ?AppointmentDetailViewModel
    {
        $appointment = $this->appointmentsRepository->getByIdForCustomer($customerId, $appointmentId);
        if (!$appointment) return null;

        return $this->mapDetailItem($appointment, true, false, true);
    }

    public function buildDetailViewModelForSalon(int $salonId, int $appointmentId): ?AppointmentDetailViewModel
    {
        $appointment = $this->appointmentsRepository->getById($salonId, $appointmentId);
        if (!$appointment) return null;

        return $this->mapDetailItem($appointment, false, true, true);
    }

    private function mapListItem(AppointmentModel $a): AppointmentsListItemViewModel
    {
        $salonName = $this->getSalonName((int)$a->salonId);
        $serviceName = $this->getServiceName((int)$a->serviceId);
        $specialistName = $this->getUserName((int)$a->specialistId, 'Specialist');
        $customerName = $this->getUserName((int)$a->customerId, 'Customer');

        return new AppointmentsListItemViewModel(
            $a,
            $salonName,
            $serviceName,
            $specialistName,
            $customerName
        );
    }
    private function mapDetailItem(AppointmentModel $a, bool $isCustomer, bool $canManage, bool $canCancel): AppointmentDetailViewModel
    {
        $salonName = $this->getSalonName((int)$a->salonId);
        $serviceName = $this->getServiceName((int)$a->serviceId);
        $specialistName = $this->getUserName((int)$a->specialistId, 'Specialist');
        $customerName = $this->getUserName((int)$a->customerId, 'Customer');

        return new AppointmentDetailViewModel(
            $a,
            $isCustomer,
            $canManage,
            $canCancel,
            $salonName,
            $serviceName,
            $specialistName,
            $customerName
        );
    }

    private function getServiceName(int $serviceId): string
    {
        if ($serviceId <= 0) return '';
        if (isset($this->serviceNameCache[$serviceId])) return $this->serviceNameCache[$serviceId];

        $name = $this->salonServicesRepository->getNameById($serviceId);
        $final = $name !== null && trim($name) !== '' ? $name : "Service #{$serviceId}";
        $this->serviceNameCache[$serviceId] = $final;

        return $final;
    }

    private function getUserName(int $userId, string $fallbackPrefix): string
    {
        if ($userId <= 0) return '';
        if (isset($this->userNameCache[$userId])) return $this->userNameCache[$userId];

        $name = $this->usersRepository->getFullNameById($userId);
        $final = $name !== null && trim($name) !== '' ? $name : "{$fallbackPrefix} #{$userId}";
        $this->userNameCache[$userId] = $final;

        return $final;
    }
    private function getSalonName(int $salonId): string
    {
        if ($salonId <= 0) return '';
        if (isset($this->salonNameCache[$salonId])) return $this->salonNameCache[$salonId];

        $name = $this->salonRepository->getNameById($salonId);
        $final = $name !== null && trim($name) !== '' ? $name : "Salon #{$salonId}";
        $this->salonNameCache[$salonId] = $final;

        return $final;
    }
    public function getAllBySalonId(int $salonId): array
    {
        return $this->appointmentsRepository->getAllBySalonId($salonId);
    }

    public function getById(int $salonId, int $id): ?AppointmentModel
    {
        return $this->appointmentsRepository->getById($salonId, $id);
    }

    private function normalizeAndFillTimes(int $salonId, AppointmentModel $appointment): void
    {
        $errors = [];

        if (trim((string)$appointment->startsAt) === '') {
            $errors[] = 'Start time is required.';
        }

        if ($appointment->serviceId <= 0) {
            $errors[] = 'Service is required.';
        }

        if (!empty($errors)) {
            throw new \InvalidArgumentException(implode("\n", $errors));
        }

        $startTs = strtotime($appointment->startsAt);
        if ($startTs === false) {
            throw new \InvalidArgumentException('Invalid date/time format.');
        }

        $service = $this->salonServicesRepository->getById($salonId, (int)$appointment->serviceId);
        if (!$service) {
            throw new \InvalidArgumentException('Service not found.');
        }
        $duration = (int)$service->durationMinutes;
        if ($duration <= 0) {
            throw new \InvalidArgumentException('Service duration is required.');
        }

        $appointment->startsAt = date('Y-m-d H:i:s', $startTs);
        $appointment->endsAt   = date('Y-m-d H:i:s', $startTs + $duration * 60);
    }

    public function create(int $salonId, AppointmentModel $appointment): void
    {
        $appointment->salonId = $salonId;
        $this->normalizeAndFillTimes($salonId, $appointment);

        $this->validate($appointment);
        if (!$this->usersRepository->specialistCanDoService($appointment->specialistId, $appointment->serviceId)) {
            throw new \InvalidArgumentException('Selected specialist cannot perform this service.');
        }

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
        $this->normalizeAndFillTimes($salonId, $appointment);
        $this->validate($appointment);
        if (!$this->usersRepository->specialistCanDoService($appointment->specialistId, $appointment->serviceId)) {
            throw new \InvalidArgumentException('Selected specialist cannot perform this service.');
        }



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
        if ($appointment->customerId <= 0) $errors[] = 'Customer is required.';

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

            // service should end not later that at 21:00
            if ($slotEnd > $workEnd) {
                break;
            }

            $conflict = false;

            foreach ($appointments as $appt) {
                $apptStart = new \DateTimeImmutable((string)$appt->startsAt);
                $apptEnd = new \DateTimeImmutable((string)$appt->endsAt);

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

        $specialists = $this->usersRepository->getSpecialistOptionsByServiceId($serviceId);


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

    public function getServiceById(int $salonId, int $serviceId)
    {
        return $this->salonServicesRepository->getById($salonId, $serviceId);
    }
    public function getAllByCustomerId(int $customerId): array
    {

        return $this->appointmentsRepository->getAllByCustomerId($customerId);
    }

    public function getByIdForCustomer(int $customerId, int $id): ?AppointmentModel
    {

        return $this->appointmentsRepository->getByIdForCustomer($customerId, $id);
    }

}

