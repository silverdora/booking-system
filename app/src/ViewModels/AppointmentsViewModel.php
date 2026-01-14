<?php

namespace App\ViewModels;

use App\Models\AppointmentModel;

class AppointmentsViewModel
{
    public string $title;
    public ?int $salonId;


    /** @var AppointmentsListItemViewModel[] */
    public array $appointments;




    /**
     * @param AppointmentsListItemViewModel[] $appointments
     */
    public function __construct(?int $salonId, array $appointments, string $title)
    {
        $this->salonId = $salonId;
        $this->appointments = $appointments;
        $this->title = $title;
    }

    public static function forSalon(int $salonId, array $appointments): self
    {
        return new self($salonId, $appointments, "Appointments (Salon #{$salonId})");
    }

    public static function forCustomer(array $appointments): self
    {
        return new self(null, $appointments, 'My appointments');
    }
}



