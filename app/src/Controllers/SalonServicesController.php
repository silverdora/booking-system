<?php

namespace App\Controllers;

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
        $salonId = (int)$salonId;
        $service = new SalonServiceModel(['salonId' => $salonId]);
        $vm = new SalonServicesFormViewModel($salonId, $service, false);
        require __DIR__ . '/../Views/salons/services/create.php';
    }
    public function store($salonId): void
    {
        $salonId = (int)$salonId;
        try {
            $service = new SalonServiceModel($_POST);
            $this->service->create($salonId, $service);
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
        $vm = new SalonServiceDetailViewModel($salonId, $service);
        require __DIR__ . '/../Views/salons/services/show.php';
    }

    public function edit($salonId, $id): void
    {
        $salonId = (int)$salonId;
        $id = (int)$id;
        $service = $this->service->getById($salonId, $id);
        if (!$service) {
            http_response_code(404);
            echo 'Service not found';
            return;
        }
        $vm = new SalonServicesFormViewModel($salonId, $service, true);
        require __DIR__ . '/../Views/salons/services/edit.php';
    }

    public function update($salonId, $id): void
    {
        $salonId = (int)$salonId;
        $id = (int)$id;

        try {
            $service = new SalonServiceModel($_POST);
            $this->service->update($salonId, $id, $service);

            header("Location: /salons/{$salonId}/services/{$id}");
            exit;
        } catch (\InvalidArgumentException $e) {
            http_response_code(400);
            echo $e->getMessage();
        }
    }

    public function delete($salonId, $id): void
    {
        $salonId = (int)$salonId;
        $id = (int)$id;

        $this->service->delete($salonId, $id);

        header("Location: /salons/{$salonId}/services");
        exit;
    }
}

