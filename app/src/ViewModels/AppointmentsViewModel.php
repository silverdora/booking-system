<?php

namespace App\ViewModels;

class AppointmentsViewModel
{
    public string $title;
    public ?int $salonId;

    /** @var AppointmentsListItemViewModel[] */
    public array $appointments;

    //UI/permissions
    public bool $isCustomer;
    public bool $canCreate;
    public bool $canManage; // edit/delete

    public ?string $primaryActionText;
    public ?string $primaryActionUrl;

    public bool $showBackToSalonLink;

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
        ?string $primaryActionText,
        ?string $primaryActionUrl,
        bool $showBackToSalonLink
    ) {
        $this->salonId = $salonId;
        $this->appointments = $appointments;
        $this->title = $title;

        $this->isCustomer = $isCustomer;
        $this->canCreate = $canCreate;
        $this->canManage = $canManage;

        $this->primaryActionText = $primaryActionText;
        $this->primaryActionUrl = $primaryActionUrl;

        $this->showBackToSalonLink = $showBackToSalonLink;
    }
}




