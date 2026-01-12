<?php

namespace App\ViewModels;

class RegisterViewModel
{
    public string $title = 'Register';
    public string $error;

    public function __construct(string $error = '')
    {
        $this->error = $error;
    }
}


