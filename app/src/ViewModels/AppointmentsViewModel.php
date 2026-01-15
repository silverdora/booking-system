<?php

namespace App\ViewModels;

class AppointmentsViewModel
{
    public string $title;
    public ?int $salonId;

    /** @var AppointmentsListItemViewModel[] */
    public array $appointments;

    // UI/permissions
    public bool $isCustomer;
    public bool $canCreate;
    public bool $canManage; // edit/delete
    public bool $canCancel;
    public ?string $primaryActionText;
    public ?string $primaryActionUrl;

    public bool $showBackToSalonLink;


    public string $viewMode; // day|week
    public string $baseDate; // YYYY-MM-DD
    /** @var string[] */
    public array $days; // list of YYYY-MM-DD

    /**
     * schedule[day][time] = AppointmentsListItemViewModel[]
     * @var array<string, array<string, array>>
     */
    public array $schedule;

    /**
     * links: staff/services/editSalon
     * @var array<string,string>
     */
    public array $ownerLinks;
    /** @var string[] */
    public array $times;


    /**
     * @param AppointmentsListItemViewModel[] $appointments
     */
    public function __construct(
        ?int $salonId,
        array $appointments,
        string $title,
        bool $isCustomer,
        bool $canCreate,
        bool $canManage,
        bool $canCancel,
        ?string $primaryActionText,
        ?string $primaryActionUrl,
        bool $showBackToSalonLink,

        string $viewMode = 'week',
        string $baseDate = '',
        array $days = [],
        array $schedule = [],
        array $ownerLinks = [],
        array $times = []

    ) {
        $this->salonId = $salonId;
        $this->appointments = $appointments;
        $this->title = $title;

        $this->isCustomer = $isCustomer;
        $this->canCreate = $canCreate;
        $this->canManage = $canManage;
        $this->canCancel = $canCancel;
        $this->primaryActionText = $primaryActionText;
        $this->primaryActionUrl = $primaryActionUrl;

        $this->showBackToSalonLink = $showBackToSalonLink;


        $this->viewMode = $viewMode;
        $this->baseDate = $baseDate !== '' ? $baseDate : date('Y-m-d');
        $this->days = $days;
        $this->schedule = $schedule;
        $this->ownerLinks = $ownerLinks;
        $this->times = $times;

    }
}





