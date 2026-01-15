<?php

namespace App\Services;

use App\Enums\UserRole;
use App\Models\UserModel;
use \App\Services\IUsersService;
use App\Repositories\IUsersRepository;
use App\Repositories\UsersRepository;

class UsersService implements IUsersService
{
    private IUsersRepository $usersRepository;

    public function __construct()
    {
        $this->usersRepository = new UsersRepository();
    }

    public function getAllByRoleAndSalonId(string $role, int $salonId): array
    {
        if (!UserRole::isValid($role)) {
            throw new \InvalidArgumentException('Invalid role');
        }

        return $this->usersRepository->getAllByRoleAndSalonId($role, $salonId);
    }

    public function updateCustomerProfile(int $id, array $data): void
    {
        $current = $this->usersRepository->getById($id);
        if (!$current || $current->role !== strtolower(UserRole::Customer->value)) {
            throw new \InvalidArgumentException('Customer not found');
        }

        $firstName = trim((string)($data['firstName'] ?? ''));
        $lastName  = trim((string)($data['lastName'] ?? ''));
        $email     = trim((string)($data['email'] ?? ''));
        $phone     = trim((string)($data['phone'] ?? ''));

        if ($firstName === '' || $lastName === '' || $email === '' || $phone === '') {
            throw new \InvalidArgumentException('Missing required fields');
        }

        // email unique check
        if ($this->usersRepository->emailExistsForOtherUser($email, $id)) {
            throw new \InvalidArgumentException('Email is already in use');
        }

        // password optional: keep old if empty
        $passwordToStore = $current->password;
        $newPasswordRaw = trim((string)($data['password'] ?? ''));

        if ($newPasswordRaw !== '') {
            if (mb_strlen($newPasswordRaw) < 8) {
                throw new \InvalidArgumentException('Password must be at least 8 characters');
            }
            $passwordToStore = password_hash($newPasswordRaw, PASSWORD_DEFAULT);
        }

        $current->firstName = $firstName;
        $current->lastName  = $lastName;
        $current->email     = $email;
        $current->phone     = $phone;
        $current->salonId   = null;
        $current->password  = $passwordToStore;

        $this->usersRepository->update($id, $current);
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
        if (!UserRole::isValid($user->role)) {
            throw new \InvalidArgumentException('Invalid role');
        }

        if (trim($user->firstName) === '' || trim($user->lastName) === '' || trim($user->email) === '') {
            throw new \InvalidArgumentException('Missing required fields');
        }

        // password REQUIRED
        if (trim((string)$user->password) === '') {
            throw new \InvalidArgumentException('Password is required');
        }

        // hash password
        $user->password = password_hash((string)$user->password, PASSWORD_DEFAULT);

        // customer must not have salonId
        if ($user->role === strtolower(UserRole::Customer->value)) {
            $user->salonId = null;
        } else {
            // staff must have salonId
            if (empty($user->salonId)) {
                throw new \InvalidArgumentException('Salon ID is required for staff');
            }
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

        $current = $this->usersRepository->getById($id);
        if (!$current) {
            throw new \InvalidArgumentException('User not found');
        }

        // password: if empty -> keep current, else hash new
        if (trim((string)$user->password) === '') {
            $user->password = $current->password;
        } else {
            $user->password = password_hash((string)$user->password, PASSWORD_DEFAULT);
        }

        if ($user->role === strtolower(UserRole::Customer->value)) {
            $user->salonId = null;
        } else {
            if (empty($user->salonId)) {
                throw new \InvalidArgumentException('Salon ID is required for staff');
            }
        }

        $this->usersRepository->update($id, $user);
    }


    public function delete(int $id): void
    {
        $this->usersRepository->delete($id);
    }
}

