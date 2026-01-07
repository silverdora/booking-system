<?php

namespace App\ViewModels;

use App\Models\UserModel;

class UserDetailViewModel
{
    public UserModel $user;
    public string $role;
    public string $title;

    public function __construct(UserModel $user, string $role)
    {
        $this->user = $user;
        $this->role = $role;

        $this->title = ucfirst($role) . ' details';
    }
}

