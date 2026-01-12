<?php

namespace App\ViewModels;

class LoginViewModel
{
    public string $title = 'Login';
    public string $error;

    public function __construct(string $error = '')
    {
        $this->error = $error;
    }
}

