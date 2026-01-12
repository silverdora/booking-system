<?php
namespace App\Services;
interface IAuthenticationService{
    public function login(string $email, string $password): array;
    public function register(string $role, string $firstName, string $lastName, string $email, string $phone, string $password): array;
}
