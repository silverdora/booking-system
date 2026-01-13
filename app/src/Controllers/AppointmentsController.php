<?php

namespace App\Controllers;

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

    public function availableSlots($specialistId): void
    {
        $salonId = $this->getSalonId(); // from session or from query
        $specialistId = (int)$specialistId;

        $date = isset($_GET['date']) ? (string)$_GET['date'] : null; // YYYY-MM-DD

        $slots = $this->service->getAvailableSlotsBySpecialist($salonId, $specialistId, $date);

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
        $salonId = $this->getSalonIdFromSession();

        $appointments = $this->service->getAllBySalonId($salonId);
        $vm = new AppointmentsViewModel($salonId, $appointments);

        require __DIR__ . '/../Views/appointments/index.php';
    }

    public function create(): void
    {
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
        $salonId = $this->getSalonIdFromSession();
        $id = (int)$id;

        $appointment = $this->service->getById($salonId, $id);
        if (!$appointment) {
            http_response_code(404);
            echo 'Appointment not found';
            return;
        }

        $vm = new AppointmentDetailViewModel($salonId, $appointment);
        require __DIR__ . '/../Views/appointments/show.php';
    }

    public function edit($id): void
    {
        $salonId = $this->getSalonIdFromSession();
        $id = (int)$id;

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
        $salonId = $this->getSalonIdFromSession();
        $id = (int)$id;

        $this->service->delete($salonId, $id);

        header("Location: /appointments");
        exit;
    }

}

