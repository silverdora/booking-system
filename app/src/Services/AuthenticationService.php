<?php

namespace App\Services;

use App\Repositories\IUsersRepository;
use App\Repositories\UsersRepository;

class AuthenticationService implements IAuthenticationService
{
    private IUsersRepository $usersRepository;

    public function __construct()
    {
        $this->usersRepository = new UsersRepository();
    }

    public function login(string $email, string $password): array
    {
        $email = strtolower(trim($email));

        $user = $this->usersRepository->getByEmail($email);
        if (!$user) {
            throw new \InvalidArgumentException('Invalid email or password.');
        }

        if (!password_verify($password, (string)$user['password'])) {
            throw new \InvalidArgumentException('Invalid email or password.');
        }

        return $user;
    }

    public function register(string $role, string $firstName, string $lastName, string $email, string $phone, string $password): array
    {
        $role = strtolower(trim($role));
        $firstName = trim($firstName);
        $lastName  = trim($lastName);
        $email     = strtolower(trim($email));
        $phone     = trim($phone);

        // Only allow customer/owner self-registration
        if (!in_array($role, ['customer', 'owner'], true)) {
            throw new \InvalidArgumentException('Invalid role for registration.');
        }

        if ($firstName === '' || $lastName === '' || $email === '' || $password === '') {
            throw new \InvalidArgumentException('Please fill all required fields.');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('Invalid email address.');
        }

        if (strlen($password) < 8) {
            throw new \InvalidArgumentException('Password must be at least 8 characters.');
        }

        if ($this->usersRepository->getByEmail($email)) {
            throw new \InvalidArgumentException('Email is already registered.');
        }

        $hash = password_hash($password, PASSWORD_DEFAULT);

        $id = $this->usersRepository->createUser([
            'role' => $role,
            'firstName' => $firstName,
            'lastName' => $lastName,
            'email' => $email,
            'phone' => $phone,
            'salonId' => null,// owner can create salon later; customer stays null
            'password' => $hash,
        ]);

        return [
            'id' => $id,
            'role' => $role,
            'firstName' => $firstName,
            'lastName' => $lastName,
            'email' => $email,
            'phone' => $phone,
            'salonId' => null,
            'password' => $hash,
        ];
    }
}


