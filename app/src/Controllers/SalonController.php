<?php

namespace App\Controllers;
use App\Enums\UserRole;
use App\Framework\Authentication;
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
        try {
            \App\Framework\Authentication::requireLogin();

            $ownerId = (int)($_SESSION['user']['id'] ?? 0);
            if ($ownerId <= 0) {
                http_response_code(403);
                echo 'Not logged in';
                return;
            }

            $salon = new \App\Models\SalonModel($_POST);

            $newSalonId = $this->salonService->create($salon, $ownerId);

            $_SESSION['user']['salonId'] = $newSalonId;

            header('Location: /appointments');
            exit;

        } catch (\InvalidArgumentException $e) {
            http_response_code(400);

            $salon = new \App\Models\SalonModel($_POST);
            $error = $e->getMessage();

            require __DIR__ . '/../Views/salons/create.php';
        }
    }
    


    private function requireSalonOwner(int $salonIdFromRoute): void
    {
        Authentication::requireLogin();
        Authentication::requireRole([UserRole::Owner]);

        $sessionSalonId = (int)($_SESSION['user']['salonId'] ?? 0);

        if ($sessionSalonId <= 0 || $sessionSalonId !== $salonIdFromRoute) {
            http_response_code(403);
            echo 'Forbidden';
            exit;
        }
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
        $this->requireSalonOwner($id);

        $this->salonService->delete($id);

        header('Location: /salons');
        exit;
    }


    public function edit($id): void
    {
        $id = (int)$id;
        $this->requireSalonOwner($id);

        $salon = $this->salonService->getById($id);
        if (!$salon) {
            http_response_code(404);
            echo 'Salon not found';
            return;
        }

        require __DIR__ . '/../Views/salons/edit.php';
    }


    public function update($id): void
{
    $id = (int)$id;
    $this->requireSalonOwner($id);

    try {
        $salon = new SalonModel($_POST);
        $this->salonService->update($id, $salon);

        header('Location: /salons');
        exit;
    } catch (\InvalidArgumentException $e) {
        http_response_code(400);

        $salon = new SalonModel($_POST);
        $salon->id = $id;

        $error = $e->getMessage();
        require __DIR__ . '/../Views/salons/edit.php';
    }
}



}