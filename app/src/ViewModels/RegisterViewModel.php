<?php

namespace App\ViewModels;

class RegisterViewModel
{
    public string $title = 'Register';
    public string $error;

    public string $role;
    public string $firstName;
    public string $lastName;
    public string $email;
    public string $phone;

    public function __construct(
        string $error = '',
        string $role = 'customer',
        string $firstName = '',
        string $lastName = '',
        string $email = '',
        string $phone = ''
    ) {
        $this->error = $error;
        $this->role = $role;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->phone = $phone;
    }
}
