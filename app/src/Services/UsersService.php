<?php

namespace App\Services;

use App\Enums\UserRole;
use App\Models\UserModel;
use App\Repositories\IUsersRepository;
use App\Repositories\UsersRepository;

class UsersService implements IUsersService
{
    private IUsersRepository $usersRepository;

    public function __construct()
    {
        $this->usersRepository = new UsersRepository();
    }

    // Normalizes + validates role (domain rule)
    public function normalizeRole(string $roleFromUrl): string
    {
        $role = strtolower(trim($roleFromUrl));

        // accept plural URLs: customers -> customer
        if (str_ends_with($role, 's')) {
            $role = rtrim($role, 's');
        }

        if (!UserRole::isValid($role)) {
            throw new \InvalidArgumentException('Invalid role');
        }

        return $role;
    }

    public function getAllByRole(string $role): array
    {
        // Ensure role is valid even if called from somewhere else later
        if (!UserRole::isValid($role)) {
            throw new \InvalidArgumentException('Invalid role');
        }

        return $this->usersRepository->getAllByRole($role);
    }

    public function getById(int $id): ?UserModel
    {
        return $this->usersRepository->getById($id);
    }

    public function create(UserModel $user): void
    {
        // Business rule: role must be valid
        if (!UserRole::isValid($user->role)) {
            throw new \InvalidArgumentException('Invalid role');
        }

        // Minimal business validation (optional)
        if (trim($user->firstName) === '' || trim($user->lastName) === '' || trim($user->email) === '') {
            throw new \InvalidArgumentException('Missing required fields');
        }

        $this->usersRepository->create($user);
    }

    public function update(int $id, UserModel $user): void
    {
        if (!UserRole::isValid($user->role)) {
            throw new \InvalidArgumentException('Invalid role');
        }

        if (trim($user->firstName) === '' || trim($user->lastName) === '' || trim($user->email) === '') {
            throw new \InvalidArgumentException('Missing required fields');
        }

        $this->usersRepository->update($id, $user);
    }

    public function delete(int $id): void
    {
        $this->usersRepository->delete($id);
    }
}

