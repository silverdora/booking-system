<?php

namespace App\ViewModels;

use App\Models\AppointmentModel;
class AppointmentsListItemViewModel
{
    public AppointmentModel $appointment;

    public string $salonName;
    public string $serviceName;
    public string $specialistName;
    public string $customerName;

    public function __construct(
        AppointmentModel $appointment,
        string $salonName,
        string $serviceName,
        string $specialistName,
        string $customerName
    ) {
        $this->appointment = $appointment;
        $this->salonName = $salonName;
        $this->serviceName = $serviceName;
        $this->specialistName = $specialistName;
        $this->customerName = $customerName;
    }
}
