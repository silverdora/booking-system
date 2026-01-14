<?php

namespace App\ViewModels;

use App\Models\AppointmentModel;

class AppointmentDetailViewModel
{
    public string $title;
    public AppointmentModel $appointment;

    public bool $isCustomer;

    public function __construct(AppointmentModel $appointment, bool $isCustomer)
    {
        $this->appointment = $appointment;
        $this->isCustomer = $isCustomer;
        $this->title = "Appointment #{$appointment->id}";
    }
}



