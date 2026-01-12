<?php

namespace App\ViewModels;

use App\Models\AppointmentModel;

class AppointmentsFormViewModel
{
    public int $salonId;
    public AppointmentModel $appointment;
    public bool $isEdit;

    /** @var array<int, array{id:int, name:string}> */
    public array $services;

    /** @var array<int, array{id:int, name:string}> */
    public array $specialists;

    /** @var array<int, array{id:int, name:string}> */
    public array $customers;

    /** @var string[] */
    public array $errors;

    public string $title;
    public string $action;

    /**
     * @param array<int, array{id:int, name:string}> $services
     * @param array<int, array{id:int, name:string}> $specialists
     * @param array<int, array{id:int, name:string}> $customers
     * @param string[] $errors
     */
    public function __construct(
        int $salonId,
        AppointmentModel $appointment,
        bool $isEdit,
        string $title,
        string $action,
        array $services,
        array $specialists,
        array $customers,
        array $errors = []
    ) {
        $this->salonId = $salonId;
        $this->appointment = $appointment;
        $this->isEdit = $isEdit;

        $this->title = $title;
        $this->action = $action;

        $this->services = $services;
        $this->specialists = $specialists;
        $this->customers = $customers;

        $this->errors = $errors;
    }
}


