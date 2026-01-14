<?php

namespace App\Controllers;
use App\Models\SalonModel;
use App\Services\ISalonService;
use App\Services\SalonService;
use App\ViewModels\SalonsViewModel;
use App\ViewModels\SalonDetailViewModel;


class SalonController
{
    private ISalonService $salonService;

    public function __construct()
    {
        $this->salonService = new SalonService();
    }

    //list of all salons
    public function index(): void
    {
        $salons = $this->salonService->getAll();
        $vm = new SalonsViewModel($salons);
        require __DIR__ . '/../Views/salons/index.php';
    }

    // create a new salon view
    public function create(): void
    {
        require __DIR__ . '/../Views/salons/create.php';
    }

    // create a new salon POST form
    public function addNewSalon(): void
    {
        $salon = new SalonModel($_POST);
        $this->salonService->create($salon);

        // Redirect back to archive so the new salon shows up
        header('Location: /salons');
        exit;
    }

    //show single salon
    public function showOneSalon(int $id): void
    {
        $salon = $this->salonService->getById($id);
        if ($salon === null) {
            // simple 404
            http_response_code(404);
            echo "Salon not found";
            return;
        }
        $vm = new SalonDetailViewModel($salon);
        require __DIR__ . '/../Views/salons/salon.php';

    }
    public function delete($id): void
    {
        $id = (int)$id;
        $this->salonService->delete($id);
        header('Location: /salons');
        exit;
    }

    public function edit($id): void
    {
        $id = (int)$id;
        $salon = $this->salonService->getById($id);
        if (!$salon) {
            echo 'Salon not found';
            return;
        }

        require __DIR__ . '/../Views/salons/edit.php';
    }

    public function update($id): void
    {
        $id = (int)$id;
        $salon = new SalonModel($_POST);
        $this->salonService->update($id, $salon);

        header('Location: /salons');
        exit;
    }

}