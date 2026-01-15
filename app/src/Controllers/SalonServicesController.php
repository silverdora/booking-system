<?php

namespace App\Controllers;

use App\Enums\UserRole;
use App\Framework\Authentication;
use App\Models\SalonServiceModel;
use App\Services\ISalonServicesService;
use App\Services\SalonServicesService;
use App\ViewModels\SalonServicesViewModel;
use App\ViewModels\SalonServiceDetailViewModel;
use App\ViewModels\SalonServicesFormViewModel;

class SalonServicesController
{
    private ISalonServicesService $service;

    public function __construct()
    {
        $this->service = new SalonServicesService();
    }

    public function index($salonId): void
    {
        $salonId = (int)$salonId;
        $services = $this->service->getAllBySalonId($salonId);
        $vm = new SalonServicesViewModel($salonId, $services);
        require __DIR__ . '/../Views/salons/services/index.php';
    }

    public function create($salonId): void
    {
        Authentication::requireRole([strtolower(UserRole::Owner->value)]);
        $salonId = (int)$salonId;
        $service = new SalonServiceModel(['salonId' => $salonId]);

        $specialistOptions = $this->service->getSpecialistOptions($salonId);
        $selectedSpecialistIds = [];

        $vm = new SalonServicesFormViewModel($salonId, $service, false, $specialistOptions, $selectedSpecialistIds);
        require __DIR__ . '/../Views/salons/services/create.php';
    }

    public function store($salonId): void
    {
        Authentication::requireRole([strtolower(UserRole::Owner->value)]);
        $salonId = (int)$salonId;

        try {
            $service = new SalonServiceModel($_POST);
            $specialistIds = $_POST['specialistIds'] ?? [];

            $this->service->create($salonId, $service, $specialistIds);

            header("Location: /salons/{$salonId}/services");
            exit;
        } catch (\InvalidArgumentException $e) {
            http_response_code(400);
            echo $e->getMessage();
        }
    }


    public function show($salonId, $id): void
    {

        $salonId = (int)$salonId;
        $id = (int)$id;

        $service = $this->service->getById($salonId, $id);
        if (!$service) {
            http_response_code(404);
            echo 'Service not found';
            return;
        }

        $specialists = $this->service->getSpecialistsForService($id);

        $vm = new SalonServiceDetailViewModel($salonId, $service, $specialists);
        require __DIR__ . '/../Views/salons/services/show.php';
    }


    public function edit($salonId, $id): void
    {
        Authentication::requireRole([strtolower(UserRole::Owner->value)]);
        $salonId = (int)$salonId;
        $id = (int)$id;

        $service = $this->service->getById($salonId, $id);
        if (!$service) {
            http_response_code(404);
            echo 'Service not found';
            return;
        }

        $specialistOptions = $this->service->getSpecialistOptions($salonId);
        $selectedSpecialistIds = $this->service->getAssignedSpecialistIds($id);

        $vm = new SalonServicesFormViewModel($salonId, $service, true, $specialistOptions, $selectedSpecialistIds);
        require __DIR__ . '/../Views/salons/services/edit.php';
    }


    public function update($salonId, $id): void
    {
        Authentication::requireRole([strtolower(UserRole::Owner->value)]);
        $salonId = (int)$salonId;
        $id = (int)$id;

        try {
            $service = new SalonServiceModel($_POST);
            $specialistIds = $_POST['specialistIds'] ?? [];

            $this->service->update($salonId, $id, $service, $specialistIds);

            header("Location: /salons/{$salonId}/services/{$id}");
            exit;
        } catch (\InvalidArgumentException $e) {
            http_response_code(400);
            echo $e->getMessage();
        }
    }


    public function delete($salonId, $id): void
    {
        Authentication::requireRole([strtolower(UserRole::Owner->value)]);
        $salonId = (int)$salonId;
        $id = (int)$id;

        $this->service->delete($salonId, $id);

        header("Location: /salons/{$salonId}/services");
        exit;
    }
}

