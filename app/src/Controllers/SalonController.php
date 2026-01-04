<?php

namespace App\Controllers;

use App\Services\ISalonService;
use App\Services\SalonService;
use App\ViewModels\SalonsViewModel;

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
        // Basic server-side validation
        $name        = trim($_POST['name'] ?? '');
        $type        = trim($_POST['type'] ?? '');
        $address     = trim($_POST['address'] ?? '');
        $city        = trim($_POST['city'] ?? '');
        $phone       = trim($_POST['phone'] ?? '');
        $email       = trim($_POST['email'] ?? '');

        if ($name === '' || $address === '' || $city === '') {
            echo 'Name, address and city are required.';
            return;
        }

        $stmt = $this->connection->prepare(
            'INSERT INTO salons (name, type, address, city, phone, email)
             VALUES (:name, :type, :address, :city, :phone, :email)'
        );

        $stmt->bindParam('name', $name);
        $stmt->bindParam('type', $type);
        $stmt->bindParam('address', $address);
        $stmt->bindParam('city', $city);
        $stmt->bindParam('phone', $phone);
        $stmt->bindParam('email', $email);

        $stmt->execute();

        // Redirect back to archive so the new salon shows up
        //add success page later
        header('Location: /salons');
        exit;
    }

    //show single salon
    public function showOneSalon(int $id): void
    {
        $stmt = $this->connection->prepare('SELECT * FROM salons WHERE id = :id');
        $stmt->bindValue('id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $salon = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$salon) { echo 'Salon not found'; return; }

        require __DIR__ . '/../Views/salons/salon.php';
    }

}