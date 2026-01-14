<?php

namespace App\Repositories;

use App\Framework\Repository;
use App\Models\UserModel;
use PDO;

class UsersRepository extends Repository implements IUsersRepository
{
    public function specialistCanDoService(int $specialistId, int $serviceId): bool
    {
        $sql = 'SELECT COUNT(*)
            FROM specialistSalonServices
            WHERE serviceId = :serviceId AND specialistId = :specialistId';

        $stmt = $this->getConnection()->prepare($sql);
        $stmt->execute([
            ':serviceId' => $serviceId,
            ':specialistId' => $specialistId,
        ]);

        return ((int)$stmt->fetchColumn()) > 0;
    }

    public function getSpecialistOptionsByServiceId(int $serviceId): array
    {
        $sql = 'SELECT u.id, u.firstName, u.lastName
            FROM specialistSalonServices sss
            INNER JOIN users u ON u.id = sss.specialistId
            WHERE sss.serviceId = :serviceId
              AND u.role = "specialist"
            ORDER BY u.lastName, u.firstName';

        $stmt = $this->getConnection()->prepare($sql);
        $stmt->execute([':serviceId' => $serviceId]);

        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        return array_map(function ($row) {
            $fullName = trim(($row['firstName'] ?? '') . ' ' . ($row['lastName'] ?? ''));
            return [
                'id' => (int)$row['id'],
                'name' => $fullName !== '' ? $fullName : ('Specialist #' . (int)$row['id']),
            ];
        }, $rows);
    }
    public function getAllByRole(string $role): array
    {
        $sql = 'SELECT id, role, firstName, lastName, email, phone, salonId, password
                FROM users
                WHERE role = :role
                ORDER BY lastName, firstName';

        $stmt = $this->getConnection()->prepare($sql);
        $stmt->bindValue(':role', $role, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_CLASS, '\App\Models\UserModel');
    }

    public function getById(int $id): ?UserModel
    {
        $sql = 'SELECT id, role, firstName, lastName, email, phone, salonId, password
                FROM users
                WHERE id = :id';

        $stmt = $this->getConnection()->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $stmt->setFetchMode(PDO::FETCH_CLASS, '\App\Models\UserModel');
        $user = $stmt->fetch();

        return $user ?: null;
    }

    public function create(UserModel $user): void
    {
        $sql = 'INSERT INTO users (role, firstName, lastName, email, phone, salonId, password)
                VALUES (:role, :firstName, :lastName, :email, :phone, :salonId, :password)';

        $stmt = $this->getConnection()->prepare($sql);

        // execute array to avoid bind mistakes + handles null nicely
        $stmt->execute([
            ':role' => $user->role,
            ':firstName' => $user->firstName,
            ':lastName' => $user->lastName,
            ':email' => $user->email,
            ':phone' => $user->phone,
            ':salonId' => $user->salonId,          // can be null
            ':password' => $user->password // can be null
        ]);

        // store inserted id back into model
        $user->id = (int)$this->getConnection()->lastInsertId();
    }

    public function update(int $id, UserModel $user): void
    {
        $sql = 'UPDATE users
                SET role = :role,
                    firstName = :firstName,
                    lastName = :lastName,
                    email = :email,
                    phone = :phone,
                    salonId = :salonId,
                    password = :password
                WHERE id = :id';

        $stmt = $this->getConnection()->prepare($sql);

        $stmt->execute([
            ':id' => $id,
            ':role' => $user->role,
            ':firstName' => $user->firstName,
            ':lastName' => $user->lastName,
            ':email' => $user->email,
            ':phone' => $user->phone,
            ':salonId' => $user->salonId,
            ':password' => $user->password
        ]);
    }

    public function delete(int $id): void
    {
        $sql = 'DELETE FROM users WHERE id = :id';
        $stmt = $this->getConnection()->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function getByEmail(string $email): ?array
    {
        $sql = 'SELECT id, role, firstName, lastName, email, phone, salonId, password
                FROM users
                WHERE email = :email
                LIMIT 1';

        $stmt = $this->getConnection()->prepare($sql);
        $stmt->execute([':email' => $email]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function createUser(array $data): int
    {
        $sql = 'INSERT INTO users (role, firstName, lastName, email, phone, salonId, password)
                VALUES (:role, :firstName, :lastName, :email, :phone, :salonId, :password)';

        $stmt = $this->getConnection()->prepare($sql);
        $stmt->execute([
            ':role' => $data['role'],
            ':firstName' => $data['firstName'],
            ':lastName' => $data['lastName'],
            ':email' => $data['email'],
            ':phone' => $data['phone'],
            ':salonId' => $data['salonId'],
            ':password' => $data['password'],
        ]);

        return (int)$this->getConnection()->lastInsertId();
    }

    public function getSpecialistOptions(int $salonId): array
    {
        $sql = 'SELECT id, firstName, lastName
            FROM users
            WHERE role = "specialist" AND salonId = :salonId
            ORDER BY lastName, firstName';

        $stmt = $this->getConnection()->prepare($sql);
        $stmt->execute([':salonId' => $salonId]);

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(function ($row) {
            $fullName = trim(($row['firstName'] ?? '') . ' ' . ($row['lastName'] ?? ''));
            return [
                'id' => (int)$row['id'],
                'name' => $fullName !== '' ? $fullName : ('Specialist #' . (int)$row['id']),
            ];
        }, $rows);
    }

    public function getCustomerOptions(): array
    {
        $sql = 'SELECT id, firstName, lastName
            FROM users
            WHERE role = "customer"
            ORDER BY lastName, firstName';

        $stmt = $this->getConnection()->prepare($sql);
        $stmt->execute();

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(function ($row) {
            $fullName = trim(($row['firstName'] ?? '') . ' ' . ($row['lastName'] ?? ''));
            return [
                'id' => (int)$row['id'],
                'name' => $fullName !== '' ? $fullName : ('Customer #' . (int)$row['id']),
            ];
        }, $rows);
    }

}

