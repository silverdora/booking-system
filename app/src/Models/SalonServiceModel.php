<?php

namespace App\Models;

class SalonServiceModel
{
    public ?int $id = null;
    public ?int $salonId = null;
    //public ?int $specialistId = null;
    public string $name = '';
    public ?float $price = null;
    public ?int $durationMinutes = null;

    public function __construct(array $data = [])
    {
        if (empty($data)) return;

        $this->id = isset($data['id']) && $data['id'] !== '' ? (int)$data['id'] : null;
        $this->salonId = isset($data['salonId']) && $data['salonId'] !== '' ? (int)$data['salonId'] : null;
        //$this->specialistId = isset($data['specialistId']) && $data['specialistId'] !== '' ? (int)$data['specialistId'] : null;
        $this->name = $data['name'] ?? '';
        $this->price = isset($data['price']) && $data['price'] !== '' ? (float)$data['price'] : null;
        $this->durationMinutes = isset($data['durationMinutes']) && $data['durationMinutes'] !== '' ? (int)$data['durationMinutes'] : null;
    }
}
