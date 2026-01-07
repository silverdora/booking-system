<?php

namespace App\Models;

class UserModel
{
    public ?int $id = null;

    public string $role;
    public string $firstName;
    public string $lastName;

    public string $email;
    public string $phone;

    public ?int $salonId = null;

    public ?string $password = null;

    public function __construct(array $data = [])
    {
        if (empty($data)) {
            return;
        }

        $this->id = isset($data['id']) && $data['id'] !== '' ? (int)$data['id'] : null;

        $this->role = $data['role'] ?? '';

        $this->firstName = $data['firstName'] ?? '';
        $this->lastName  = $data['lastName'] ?? '';

        $this->email = $data['email'] ?? '';
        $this->phone = $data['phone'] ?? '';

        $this->salonId = isset($data['salonId']) && $data['salonId'] !== ''
            ? (int)$data['salonId']
            : null;

        $this->password = $data['password'] ?? null;
    }
}

