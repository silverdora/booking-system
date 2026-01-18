<?php

namespace App\ViewModels;

use App\Models\UserModel;

class UsersViewModel
{
    /** @var UserModel[] */
    public array $users;

    public string $role;
    public string $title;

    public function __construct(array $users, string $role)
    {
        $this->users = $users;
        $this->role = $role;
        $this->title = ucfirst($role) . 's';
    }
}

