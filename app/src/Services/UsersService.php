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

    public function getCustomerOptions(): array
    {
        return $this->usersRepository->getCustomerOptions();
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

        //sanitize + presence
        $firstName = trim((string)($data['firstName'] ?? ''));
        $lastName  = trim((string)($data['lastName'] ?? ''));
        $email     = trim((string)($data['email'] ?? ''));
        $phone     = trim((string)($data['phone'] ?? ''));
        $newPasswordRaw = trim((string)($data['password'] ?? ''));

        if ($firstName === '' || $lastName === '' || $email === '' || $phone === '') {
            throw new \InvalidArgumentException('Missing required fields');
        }

        //type checks
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('Invalid email format');
        }

        // phone
        if (!preg_match('/^[0-9\-\+\(\)\s\.]+$/', $phone)) {
            throw new \InvalidArgumentException('Invalid phone format');
        }

        $digitsOnly = preg_replace('/\D+/', '', $phone) ?? '';
        if (mb_strlen($digitsOnly) < 7) {
            throw new \InvalidArgumentException('Invalid phone format');
        }


        $phone = preg_replace('/\s+/', ' ', trim($phone));

        // email unique check
        if ($this->usersRepository->emailExistsForOtherUser($email, $id)) {
            throw new \InvalidArgumentException('Email is already in use');
        }

        // password rules (optional)
        if ($newPasswordRaw !== '') {
            if (mb_strlen($newPasswordRaw) < 8) {
                throw new \InvalidArgumentException('Password must be at least 8 characters');
            }
            $current->password = password_hash($newPasswordRaw, PASSWORD_DEFAULT);
        }

        // apply updates
        $current->firstName = $firstName;
        $current->lastName  = $lastName;
        $current->email     = $email;
        $current->phone     = $phone;
        $current->salonId   = null;

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

        // sanitize
        $user->firstName = trim((string)($user->firstName ?? ''));
        $user->lastName  = trim((string)($user->lastName ?? ''));
        $user->email     = trim((string)($user->email ?? ''));
        $user->phone     = trim((string)($user->phone ?? ''));

        // presence
        if ($user->firstName === '' || $user->lastName === '' || $user->email === '') {
            throw new \InvalidArgumentException('Missing required fields');
        }

        // type checks
        if (!filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('Invalid email format');
        }

        // phone
        if (!preg_match('/^[0-9\-\+\(\)\s\.]+$/', $user->phone)) {
            throw new \InvalidArgumentException('Invalid phone format');
        }

        $digitsOnly = preg_replace('/\D+/', '', $user->phone) ?? '';
        if (mb_strlen($digitsOnly) < 7) {
            throw new \InvalidArgumentException('Invalid phone format');
        }

        $user->phone = preg_replace('/\s+/', ' ', trim($user->phone));

        // email unique
        if ($this->usersRepository->emailExists($user->email)) {
            throw new \InvalidArgumentException('Email is already in use');
        }

        // password REQUIRED + rules
        $rawPassword = trim((string)($user->password ?? ''));
        if ($rawPassword === '') {
            throw new \InvalidArgumentException('Password is required');
        }
        if (mb_strlen($rawPassword) < 8) {
            throw new \InvalidArgumentException('Password must be at least 8 characters');
        }

        $user->password = password_hash($rawPassword, PASSWORD_DEFAULT);

        // role rules for salonId
        if ($user->role === strtolower(UserRole::Customer->value)) {
            $user->salonId = null;
        } else {
            if (empty($user->salonId) || (int)$user->salonId < 1) {
                throw new \InvalidArgumentException('Salon ID is required for staff');
            }
            $user->salonId = (int)$user->salonId;
        }

        $this->usersRepository->create($user);
    }



    public function update(int $id, UserModel $user): void
    {
        if (!UserRole::isValid($user->role)) {
            throw new \InvalidArgumentException('Invalid role');
        }

        $current = $this->usersRepository->getById($id);
        if (!$current) {
            throw new \InvalidArgumentException('User not found');
        }

        // sanitize
        $user->firstName = trim((string)($user->firstName ?? ''));
        $user->lastName  = trim((string)($user->lastName ?? ''));
        $user->email     = trim((string)($user->email ?? ''));
        $user->phone     = trim((string)($user->phone ?? ''));

        // presence
        if ($user->firstName === '' || $user->lastName === '' || $user->email === '' || $user->phone === '') {
            throw new \InvalidArgumentException('Missing required fields');
        }

        // type checks
        if (!filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('Invalid email format');
        }

        // phone

        if (!preg_match('/^[0-9\-\+\(\)\s\.]+$/', $user->phone)) {
            throw new \InvalidArgumentException('Invalid phone format');
        }

        $digitsOnly = preg_replace('/\D+/', '', $user->phone) ?? '';
        if (mb_strlen($digitsOnly) < 7) {
            throw new \InvalidArgumentException('Invalid phone format');
        }

        $user->phone = preg_replace('/\s+/', ' ', trim($user->phone));

        // email unique for update
        if ($this->usersRepository->emailExistsForOtherUser($user->email, $id)) {
            throw new \InvalidArgumentException('Email is already in use');
        }

        // password: keep current if empty, else validate rules + hash
        $rawPassword = trim((string)($user->password ?? ''));
        if ($rawPassword === '') {
            $user->password = $current->password;
        } else {
            if (mb_strlen($rawPassword) < 8) {
                throw new \InvalidArgumentException('Password must be at least 8 characters');
            }
            $user->password = password_hash($rawPassword, PASSWORD_DEFAULT);
        }

        // role rules for salonId
        if ($user->role === strtolower(UserRole::Customer->value)) {
            $user->salonId = null;
        } else {
            if (empty($user->salonId) || (int)$user->salonId < 1) {
                throw new \InvalidArgumentException('Salon ID is required for staff');
            }
            $user->salonId = (int)$user->salonId;
        }

        $this->usersRepository->update($id, $user);
    }



    public function delete(int $id): void
    {
        $this->usersRepository->delete($id);
    }
}

