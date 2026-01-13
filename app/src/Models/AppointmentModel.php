<?php

namespace App\Models;

class AppointmentModel
{
    public ?int $id = null;
    public int $salonId;
    public int $serviceId;
    public int $specialistId;
    public int $customerId;
    public string $startsAt;   // YYYY-MM-DD HH:MM:SS
    public string $endsAt;   // YYYY-MM-DD HH:MM:SS

    public function __construct(array $data = [])
    {
        if (empty($data)) {
            return;
        }

        $this->id = isset($data['id']) && $data['id'] !== '' ? (int)$data['id'] : null;
        $this->salonId = (int)($data['salonId'] ?? $data['salonId'] ?? 0);
        $this->serviceId = (int)($data['serviceId'] ?? $data['serviceId'] ?? 0);
        $this->specialistId = (int)($data['specialistId'] ?? $data['specialistId'] ?? 0);
        $this->customerId = (int)($data['customerId'] ?? $data['customerId'] ?? 0);
        $this->startsAt = $data['startsAt'] ?? $data['startsAt'] ?? '';
        $this->endsAt = $data['endsAt'] ?? $data['endsAt'] ?? '';
    }
}


