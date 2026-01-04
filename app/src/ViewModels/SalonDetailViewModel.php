<?php

namespace App\ViewModels;

use App\Models\SalonModel;

class SalonDetailViewModel
{
    public SalonModel $salon;

    public function __construct(SalonModel $salon)
    {
        $this->salon = $salon;
    }
}