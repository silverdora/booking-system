<?php

namespace App\Controllers;

use App\Enums\UserRole;
use App\Framework\Authentication;
use App\Models\UserModel;
use App\Services\IUsersService;
use App\Services\UsersService;
use App\ViewModels\UsersViewModel;
use App\ViewModels\UserDetailViewModel;
use App\ViewModels\UserFormViewModel;

class UsersController
{
    private IUsersService $usersService;

    public function __construct()
    {
        $this->usersService = new UsersService();
    }

    public function profileShow(): void
    {
        Authentication::requireRole([strtolower(UserRole::Customer->value)]);
        $auth = $this->requireCustomer();
        $id = (int)$auth['id'];

        $user = $this->usersService->getById($id);
        if (!$user || $user->role !== strtolower(UserRole::Customer->value)) {
            http_response_code(404);
            echo 'Not found';
            return;
        }

        $vm = new \App\ViewModels\UserDetailViewModel($user, strtolower(UserRole::Customer->value));

        require __DIR__ . '/../Views/users/profile/show.php';
    }

    public function profileEdit(): void
    {
        Authentication::requireRole([strtolower(UserRole::Customer->value)]);
        $auth = $this->requireCustomer();
        $id = (int)$auth['id'];

        $user = $this->usersService->getById($id);
        if (!$user || $user->role !== strtolower(UserRole::Customer->value)) {
            http_response_code(404);
            echo 'Not found';
            return;
        }

        $vm = new \App\ViewModels\UserFormViewModel($user, strtolower(UserRole::Customer->value), true, '/profile/edit');

        require __DIR__ . '/../Views/users/profile/edit.php';
    }

    public function profileUpdate(): void
    {
        Authentication::requireRole([strtolower(UserRole::Customer->value)]);
        try {
            $auth = $this->requireCustomer();
            $id = (int)$auth['id'];

            $this->usersService->updateCustomerProfile($id, $_POST);
            $updated = $this->usersService->getById($id);
            if ($updated) {
                $_SESSION['user']['firstName'] = (string)$updated->firstName;
                $_SESSION['user']['lastName']  = (string)$updated->lastName;
            }

            header('Location: /profile');
            exit;
        } catch (\InvalidArgumentException $e) {
            http_response_code(400);
            echo $e->getMessage();
        }
    }

    private function requireCustomer(): array
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $user = $_SESSION['user'] ?? null;

        if (!$user || ($user['role'] ?? null) !== strtolower(UserRole::Customer->value)) {
            header('Location: /login');
            exit;
        }

        return $user;
    }
    public function index($role): void
    {
        Authentication::requireRole([strtolower(UserRole::Owner->value)]);
        try {
            $role = $this->usersService->normalizeRole((string)$role);

            $users = $this->usersService->getAllByRole($role);
            $vm = new UsersViewModel($users, $role);

            require __DIR__ . '/../Views/users/index.php';
        } catch (\InvalidArgumentException $e) {
            http_response_code(404);
            echo 'Not found';
        }
    }

    public function create($role): void
    {
        Authentication::requireRole([strtolower(UserRole::Owner->value)]);

        try {
            $role = $this->usersService->normalizeRole((string)$role);

            $user = new UserModel(['role' => $role]);
            $isEdit = false;
            $vm = new UserFormViewModel($user, $role, $isEdit);
            require __DIR__ . '/../Views/users/create.php';
        } catch (\InvalidArgumentException $e) {
            http_response_code(404);
            echo 'Not found';
        }
    }

    public function store($role): void
    {
        try {
            $role = $this->usersService->normalizeRole((string)$role);

            $user = new UserModel($_POST);
            $user->role = $role;

            $this->usersService->create($user);

            header('Location: /users/' . $this->pluralRole($role));
            exit;
        } catch (\InvalidArgumentException $e) {
            http_response_code(400);
            echo $e->getMessage();
        }
    }

    public function show($role, $id): void
    {
        Authentication::requireRole([strtolower(UserRole::Owner->value)]);
        try {
            $role = $this->usersService->normalizeRole((string)$role);
            $id = (int)$id;

            $user = $this->usersService->getById($id);

            // Business check: user must exist and match URL role
            if (!$user || $user->role !== $role) {
                http_response_code(404);
                echo 'Not found: user must exist and match URL role';
                return;
            }

            $vm = new UserDetailViewModel($user, $role);
            require __DIR__ . '/../Views/users/show.php';
        } catch (\InvalidArgumentException $e) {
            http_response_code(404);
            echo 'Not found: InvalidArgumentException';
        }
    }

    public function edit($role, $id): void
    {
        Authentication::requireRole([strtolower(UserRole::Owner->value)]);
        try {
            $role = $this->usersService->normalizeRole((string)$role);
            $id = (int)$id;

            $user = $this->usersService->getById($id);
            if (!$user || $user->role !== $role) {
                http_response_code(404);
                echo 'Not found';
                return;
            }

            $vm = new UserFormViewModel($user, $role, true);

            require __DIR__ . '/../Views/users/edit.php';
        } catch (\InvalidArgumentException $e) {
            http_response_code(404);
            echo 'Not found';
        }
    }

    public function update($role, $id): void
    {
        Authentication::requireRole([strtolower(UserRole::Owner->value)]);
        try {
            $role = $this->usersService->normalizeRole((string)$role);
            $id = (int)$id;

            $user = new UserModel($_POST);
            $user->role = $role;

            $this->usersService->update($id, $user);

            header('Location: /users/' . $this->pluralRole($role) . '/' . $id);
            exit;
        } catch (\InvalidArgumentException $e) {
            http_response_code(400);
            echo $e->getMessage();
        }
    }

    public function delete($role, $id): void
    {
        Authentication::requireRole([strtolower(UserRole::Owner->value)]);
        try {
            $role = $this->usersService->normalizeRole((string)$role);
            $id = (int)$id;

            $user = $this->usersService->getById($id);
            if (!$user || $user->role !== $role) {
                http_response_code(404);
                echo 'Not found';
                return;
            }

            $this->usersService->delete($id);

            header('Location: /users/' . $this->pluralRole($role));
            exit;
        } catch (\InvalidArgumentException $e) {
            http_response_code(404);
            echo 'Not found';
        }
    }

    //presentation/URL helper
    private function pluralRole(string $role): string
    {
        return $role . 's';
    }
}
