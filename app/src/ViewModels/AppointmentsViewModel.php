<?php

namespace App\ViewModels;

use App\Models\AppointmentModel;

class AppointmentsViewModel
{
    public string $title;
    public int $salonId;

    /** @var AppointmentModel[] */
    public array $appointments;

    /**
     * @param AppointmentModel[] $appointments
     */
    public function __construct(int $salonId, array $appointments)
    {
        $this->salonId = $salonId;
        $this->appointments = $appointments;
        $this->title = "Appointments (Salon #{$salonId})";
    }
}


