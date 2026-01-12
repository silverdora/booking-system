<?php

namespace App\Controllers;

use App\Framework\Authentication;
use App\Services\IAuthenticationService;
use App\Services\AuthenticationService;
use App\ViewModels\LoginViewModel;
use App\ViewModels\RegisterViewModel;

class AuthenticationController
{
    private IAuthenticationService $authenticationService;

    public function __construct()
    {
        $this->authenticationService = new AuthenticationService();
    }

    public function showLogin(): void
    {
        $vm = new LoginViewModel();
        require __DIR__ . '/../Views/authentication/login.php';
    }

    public function login(): void
    {
        try {
            $email = (string)($_POST['email'] ?? '');
            $password = (string)($_POST['password'] ?? '');

            $user = $this->authenticationService->login($email, $password);
            Authentication::login($user);

            // Redirect per role (simple default)
            if ($user['role'] === 'customer') {
                header('Location: /salons');
            } else {
                // owner goes to salons (or dashboard)
                header('Location: /salons');
            }
            exit;

        } catch (\InvalidArgumentException $e) {
            $vm = new LoginViewModel($e->getMessage());
            require __DIR__ . '/../Views/authentication/login.php';
        }
    }

    public function showRegister(): void
    {
        $vm = new RegisterViewModel();
        require __DIR__ . '/../Views/authentication/register.php';
    }

    public function register(): void
    {
        try {
            $role      = (string)($_POST['role'] ?? 'customer');
            $firstName = (string)($_POST['firstName'] ?? '');
            $lastName  = (string)($_POST['lastName'] ?? '');
            $email     = (string)($_POST['email'] ?? '');
            $phone     = (string)($_POST['phone'] ?? '');
            $password  = (string)($_POST['password'] ?? '');

            $user = $this->authenticationService->register($role, $firstName, $lastName, $email, $phone, $password);

            Authentication::login($user);

            // After registration:
            // customer -> book appointment
            // owner -> create salon
            if ($user['role'] === 'customer') {
                header('Location: /salons');
            } else {
                header('Location: /salons/create');
            }
            exit;

        } catch (\InvalidArgumentException $e) {
            $vm = new RegisterViewModel($e->getMessage());
            require __DIR__ . '/../Views/authentication/register.php';
        }
    }

    public function logout(): void
    {
        Authentication::logout();
        header('Location: /login');
        exit;
    }
}


