<?php

namespace App\ViewModels;

use App\Models\AppointmentModel;

class AppointmentDetailViewModel
{
    public string $title;
    public bool $isCustomer;

    public AppointmentModel $appointment;

    public string $salonName;
    public string $serviceName;
    public string $specialistName;
    public string $customerName;
    public bool $canManage;
    public bool $canCancel;

    public function __construct(
        AppointmentModel $appointment,
        bool $isCustomer,
        bool $canManage,
        bool $canCancel,
        string $salonName,
        string $serviceName,
        string $specialistName,
        string $customerName
    ) {
        $this->appointment = $appointment;
        $this->isCustomer = $isCustomer;
        $this->canManage = $canManage;
        $this->canCancel = $canCancel;
        $this->salonName = $salonName;
        $this->serviceName = $serviceName;
        $this->specialistName = $specialistName;
        $this->customerName = $customerName;

        $this->title = "Appointment #{$appointment->id} — {$serviceName}";
    }
}


