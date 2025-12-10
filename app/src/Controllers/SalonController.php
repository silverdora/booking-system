<?php

namespace App\Controllers;

use App\Config;
use PDO;

class SalonController
{
    private PDO $connection;

    public function __construct()
    {
        // build PDO connection
        $dsn = 'mysql:host=' . Config::DB_SERVER_NAME .
            ';dbname=' . Config::DB_NAME .
            ';charset=utf8mb4';

        $this->connection = new PDO(
            $dsn,
            Config::DB_USERNAME,
            Config::DB_PASSWORD
        );

        $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    //archive list of all salons
    public function index(): void
    {
        $stmt = $this->connection->query(
            'SELECT id, name, type, city 
             FROM salons 
             ORDER BY name'
        );
        $salons = $stmt->fetchAll(PDO::FETCH_ASSOC);

        require __DIR__ . '/../Views/salons/index.php';
    }

    // create a new salon
    public function store(): void
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
        header('/../src/Views/salons');
        exit;
    }

    //show single salon
    public function showOneSalon(string $name): void
    {
        if (!$name) {
            // later: show proper 404
            echo 'Salon not found';
            return;
        }

        $stmt = $this->connection->prepare(
            'SELECT * FROM salons WHERE name = :name'
        );
        $stmt->bindParam('name', $name, PDO::PARAM_STR);
        $stmt->execute();
        $salon = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$salon) {
            echo 'Salon not found';
            return;
        }

        require __DIR__ . '/../Views/salons/salon.php';
    }
}