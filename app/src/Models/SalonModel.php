<?php

namespace App\Models;

class SalonModel
{
    public ?int $id = null;
    public string $name;
    public string $type;
    public string $description;
    public string $phone;
    public string $email;
    public string $city;
    public string $address;

    public function __construct(array $data = [])
    {
        if (empty($data)){
            return;
        }
        $this->id = isset($data['id']) && $data['id'] !== '' ? (int)$data['id'] : null;
        $this->name = $data['name'] ?? '';
        $this->type = $data['type'] ?? '';
        $this->description = $data['description'] ?? '';
        $this->phone = $data['phone'] ?? '';
        $this->email = $data['email'] ?? '';
        $this->city = $data['city'] ?? '';
        $this->address = $data['address'] ?? '';
    }

}