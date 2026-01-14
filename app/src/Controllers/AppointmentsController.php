<?php

namespace App\Controllers;

use App\Enums\UserRole;
use App\Framework\Authentication;
use App\Models\AppointmentModel;
use App\Services\IAppointmentsService;
use App\Services\AppointmentsService;
use App\ViewModels\AppointmentsViewModel;
use App\ViewModels\AppointmentDetailViewModel;
use App\ViewModels\AppointmentsFormViewModel;

class AppointmentsController
{
    private IAppointmentsService $service;

    public function __construct()
    {
        $this->service = new AppointmentsService();
    }
    public function receptionistChooseService(): void
    {
        $this->requireReceptionist();
        $salonId = $this->getSalonIdFromSession();

        $services = $this->service->getServiceOptions($salonId);
        $customers = $this->service->getCustomerOptions();

        $title = 'Create appointment — choose customer & service';
        require __DIR__ . '/../Views/appointments/receptionist_choose_service.php';
    }

    public function receptionistChooseDate(): void
    {
        $this->requireReceptionist();
        $salonId = $this->getSalonIdFromSession();

        $serviceId = (int)($_GET['serviceId'] ?? 0);
        $customerId = (int)($_GET['customerId'] ?? 0);

        if ($serviceId <= 0 || $customerId <= 0) {
            http_response_code(400);
            echo 'serviceId and customerId are required.';
            return;
        }

        $title = 'Create appointment — choose date';
        require __DIR__ . '/../Views/appointments/receptionist_choose_date.php';
    }

    public function receptionistChooseSlot(): void
    {
        $this->requireReceptionist();
        $salonId = $this->getSalonIdFromSession();

        $serviceId = (int)($_GET['serviceId'] ?? 0);
        $customerId = (int)($_GET['customerId'] ?? 0);
        $date = (string)($_GET['date'] ?? '');

        if ($serviceId <= 0 || $customerId <= 0 || $date === '') {
            http_response_code(400);
            echo 'serviceId, customerId and date are required.';
            return;
        }

        try {
            $specialistsWithSlots = $this->service->getSpecialistsWithSlots($salonId, $serviceId, $date);

            $title = 'Create appointment — choose slot';
            require __DIR__ . '/../Views/appointments/receptionist_choose_slot.php';
        } catch (\InvalidArgumentException $e) {
            http_response_code(400);
            echo $e->getMessage();
        }
    }

    public function receptionistConfirm(): void
    {
        $this->requireReceptionist();
        $salonId = $this->getSalonIdFromSession();

        try {
            $appointment = new AppointmentModel($_POST);
            $appointment->salonId = $salonId;

            $this->service->create($salonId, $appointment);

            header('Location: /appointments');
            exit;
        } catch (\InvalidArgumentException $e) {
            http_response_code(400);
            echo $e->getMessage();
        }
    }

    private function requireReceptionist(): void
    {
        Authentication::requireRole([UserRole::Receptionist, UserRole::Owner]);
    }

    private function requireCustomer(): void
    {
        Authentication::requireRole([UserRole::Customer]);
    }

    public function bookChooseService($salonId): void
    {
        $this->requireCustomer();
        $salonId = (int)$salonId;

        // list of salon services
        $services = $this->service->getServiceOptions($salonId);

        $title = 'Choose service';
        require __DIR__ . '/../Views/appointments/choose_service.php';
    }
    public function bookChooseDate($salonId): void
    {
        $this->requireCustomer();
        $salonId = (int)$salonId;

        $serviceId = (int)($_GET['serviceId'] ?? 0);
        if ($serviceId <= 0) {
            http_response_code(400);
            echo 'serviceId is required.';
            return;
        }

        // View: choose date (calendar)
        $title = 'Choose date';
        $serviceId = $serviceId;
        require __DIR__ . '/../Views/appointments/choose_date.php';
    }

    public function bookChooseSlot($salonId): void
    {
        $this->requireCustomer();
        $salonId = (int)$salonId;

        $serviceId = (int)($_GET['serviceId'] ?? 0);
        $date = (string)($_GET['date'] ?? '');

        if ($serviceId <= 0 || $date === '') {
            http_response_code(400);
            echo 'serviceId and date are required.';
            return;
        }

        try {
            $specialistsWithSlots = $this->service->getSpecialistsWithSlots($salonId, $serviceId, $date);

            $title = 'Choose slot';
            require __DIR__ . '/../Views/appointments/choose_slot.php';
        } catch (\InvalidArgumentException $e) {
            http_response_code(400);
            echo $e->getMessage();
        }
    }

    public function bookConfirm($salonId): void
    {
        $this->requireCustomer();
        $salonId = (int)$salonId;

        try {
            $appointment = new AppointmentModel($_POST);
            $appointment->customerId = (int)($_SESSION['user']['id'] ?? 0);
            $this->service->create($salonId, $appointment);

            header('Location: /appointments');
            exit;
        } catch (\InvalidArgumentException $e) {
            http_response_code(400);
            echo $e->getMessage();
        }
    }
    public function availableSlots($specialistId): void
    {
        $this->requireCustomer();

        $salonId = (int)($_GET['salonId'] ?? 0);
        $serviceId = (int)($_GET['serviceId'] ?? 0);
        $date = (string)($_GET['date'] ?? '');

        if ($salonId <= 0 || $serviceId <= 0 || $date === '') {
            http_response_code(400);
            echo 'salonId, serviceId and date are required.';
            return;
        }

        $specialistId = (int)$specialistId;

        // duration from salonServices
        $service = $this->service->getServiceById($salonId, $serviceId);
        if (!$service) {
            http_response_code(404);
            echo 'Service not found.';
            return;
        }

        $slots = $this->service->getAvailableSlotsBySpecialist(
            $salonId,
            $specialistId,
            $date,
            (int)$service->durationMinutes
        );

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($slots, JSON_UNESCAPED_UNICODE);
    }



    private function getSalonIdFromSession(): int
    {
        if (!isset($_SESSION['user']) || !isset($_SESSION['user']['salonId'])) {
            http_response_code(403);
            echo 'No salon selected or not logged in.';
            exit;
        }

        $salonId = (int)$_SESSION['user']['salonId'];

        if ($salonId <= 0) {
            http_response_code(400);
            echo 'Invalid salonId in session.';
            exit;
        }

        return $salonId;
    }

    public function index(): void
    {
        Authentication::requireLogin();

        $role = strtolower(trim((string)($_SESSION['user']['role'] ?? '')));
        $userId = (int)($_SESSION['user']['id'] ?? 0);

        if ($role === strtolower(UserRole::Customer->value)) {
            if ($userId <= 0) {
                http_response_code(403);
                echo 'Not logged in.';
                return;
            }
            $vm = $this->service->buildIndexViewModelForCustomer($userId);
            require __DIR__ . '/../Views/appointments/index.php';
            return;
        }

        // staff/owner view: appointments for salon in session
        $salonId = $this->getSalonIdFromSession();
        $vm = $this->service->buildIndexViewModelForSalon($salonId);
        require __DIR__ . '/../Views/appointments/index.php';
    }

    public function create(): void
    {
        $this->requireReceptionist();

        $salonId = $this->getSalonIdFromSession();
        $appointment = new AppointmentModel(['salonId' => $salonId]);
        $services = $this->service->getServiceOptions($salonId);
        $specialists = $this->service->getSpecialistOptions($salonId);
        $customers = $this->service->getCustomerOptions();

        $vm = new AppointmentsFormViewModel(
            $salonId,
            $appointment,
            false,
            'Create appointment',
            '/appointments/create',
            $services,
            $specialists,
            $customers,
            []
        );

        require __DIR__ . '/../Views/appointments/create.php';
    }


    public function store(): void
    {
        $this->requireReceptionist();

        $salonId = $this->getSalonIdFromSession();

        try {
            $appointment = new AppointmentModel($_POST);
            $this->service->create($salonId, $appointment);

            header("Location: /appointments");
            exit;
        } catch (\InvalidArgumentException $e) {
            $errors = array_filter(array_map('trim', explode("\n", $e->getMessage())));

            $appointment = new AppointmentModel($_POST);

            $vm = new AppointmentsFormViewModel(
                $salonId,
                $appointment,
                false,
                'Create appointment',
                '/appointments/create',
                $this->service->getServiceOptions($salonId),
                $this->service->getSpecialistOptions($salonId),
                $this->service->getCustomerOptions(),
                $errors
            );

            require __DIR__ . '/../Views/appointments/create.php';
        }
    }
    public function show($id): void
    {
        Authentication::requireLogin();

        $id = (int)$id;
        $role = strtolower(trim((string)($_SESSION['user']['role'] ?? '')));
        $userId = (int)($_SESSION['user']['id'] ?? 0);
        $isCustomer = ($role === strtolower(UserRole::Customer->value));

        if ($role === strtolower(UserRole::Customer->value)) {
            $vm = $this->service->buildDetailViewModelForCustomer($userId, $id);
        } else {
            $salonId = $this->getSalonIdFromSession();
            $vm = $this->service->buildDetailViewModelForSalon($salonId, $id);
        }

        if (!$vm) {
            http_response_code(404);
            echo 'Appointment not found';
            return;
        }

        require __DIR__ . '/../Views/appointments/show.php';
    }


    public function edit($id): void
    {
        $salonId = $this->getSalonIdFromSession();
        $id = (int)$id;
        $this->requireReceptionist();
        $appointment = $this->service->getById($salonId, $id);
        if (!$appointment) {
            http_response_code(404);
            echo 'Appointment not found';
            return;
        }

        $services = $this->service->getServiceOptions($salonId);
        $specialists = $this->service->getSpecialistOptions($salonId);
        $customers = $this->service->getCustomerOptions();

        $vm = new AppointmentsFormViewModel(
            $salonId,
            $appointment,
            true,
            'Edit appointment',
            "/appointments/{$id}/edit",
            $services,
            $specialists,
            $customers,
            []
        );

        require __DIR__ . '/../Views/appointments/edit.php';
    }


    public function update($id): void
    {
        $this->requireReceptionist();
        $salonId = $this->getSalonIdFromSession();
        $id = (int)$id;

        try {
            $appointment = new AppointmentModel($_POST);

            $this->service->update($salonId, $id, $appointment);

            header("Location: /appointments/{$id}");
            exit;
        } catch (\InvalidArgumentException $e) {
            $errors = array_filter(array_map('trim', explode("\n", $e->getMessage())));

            // keep user's input so the form stays filled
            $appointment = new AppointmentModel($_POST);

            $services = $this->service->getServiceOptions($salonId);
            $specialists = $this->service->getSpecialistOptions($salonId);
            $customers = $this->service->getCustomerOptions();

            $vm = new AppointmentsFormViewModel(
                $salonId,
                $appointment,
                true,
                'Edit appointment',
                "/appointments/{$id}/edit",
                $services,
                $specialists,
                $customers,
                $errors
            );

            require __DIR__ . '/../Views/appointments/edit.php';
        }
    }



    public function delete($id): void
    {
        $this->requireReceptionist();

        $salonId = $this->getSalonIdFromSession();
        $id = (int)$id;

        $this->service->delete($salonId, $id);

        header("Location: /appointments");
        exit;
    }

}

