<?php

namespace App\ViewModels;

use App\Models\AppointmentModel;

class AppointmentDetailViewModel
{
    public string $title;
    public int $salonId;
    public AppointmentModel $appointment;

    public function __construct(int $salonId, AppointmentModel $appointment)
    {
        $this->salonId = $salonId;
        $this->appointment = $appointment;
        $this->title = "Appointment #{$appointment->id}";
    }
}


