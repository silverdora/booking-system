<?php

namespace App\ViewModels;

use App\Models\UserModel;

class UserFormViewModel
{
    public UserModel $user;
    public string $role;
    public bool $isEdit;
    public string $action;
    public string $title;

    public function __construct(UserModel $user, string $role, bool $isEdit, ?string $actionOverride = null)
    {
        $this->user = $user;
        $this->role = $role;
        $this->isEdit = $isEdit;

        $this->title = $isEdit
            ? 'Edit ' . ucfirst($role)
            : 'Create ' . ucfirst($role);

        if ($actionOverride !== null) {
            $this->action = $actionOverride;
            return;
        }

        $this->action = $isEdit
            ? "/users/{$role}s/{$user->id}/edit"
            : "/users/{$role}s/create";
    }
}

