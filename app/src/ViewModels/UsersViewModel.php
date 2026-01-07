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

        // presentation-only logic belongs here
        $this->title = ucfirst($role) . 's';
    }
}

