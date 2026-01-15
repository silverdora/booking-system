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
    public function staffIndex($salonId): void
    {
        $salonId = (int)$salonId;

        $auth = $this->requireUsersManagementAccess(strtolower(\App\Enums\UserRole::Specialist->value));

        if (($auth['role'] ?? '') === strtolower(\App\Enums\UserRole::Owner->value)) {
            if ((int)$auth['salonId'] !== $salonId) {
                http_response_code(404);
                echo 'Not found';
                return;
            }
        }

        $specialists = $this->usersService->getAllByRoleAndSalonId(
            strtolower(\App\Enums\UserRole::Specialist->value),
            $salonId
        );

        $receptionists = $this->usersService->getAllByRoleAndSalonId(
            strtolower(\App\Enums\UserRole::Receptionist->value),
            $salonId
        );

        require __DIR__ . '/../Views/salons/staff/index.php';
    }

    private function requireUsersManagementAccess(string $role): array
    {
        Authentication::requireLogin();
        $auth = Authentication::user();

        $authRole = $auth['role'] ?? '';

        // owner can manage only specialist/receptionist in own salon
        if ($authRole === strtolower(UserRole::Owner->value)) {
            if (!in_array($role, [strtolower(UserRole::Specialist->value), strtolower(UserRole::Receptionist->value)], true)) {
                http_response_code(403);
                echo 'Forbidden';
                exit;
            }

            if (empty($auth['salonId'])) {
                http_response_code(403);
                echo 'Forbidden';
                exit;
            }

            return $auth;
        }

        http_response_code(403);
        echo 'Forbidden';
        exit;
    }


    public function profileShow(): void
    {
        Authentication::requireRole([strtolower(UserRole::Customer->value)]);
        $auth = Authentication::user();
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
        $auth = Authentication::user();
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
            $auth = Authentication::user();
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
        try {
            $role = $this->usersService->normalizeRole((string)$role);
            $auth = $this->requireUsersManagementAccess($role);

            if (($auth['role'] ?? '') === strtolower(UserRole::Owner->value)) {
                $users = $this->usersService->getAllByRoleAndSalonId($role, (int)$auth['salonId']);
            } else {
                $users = $this->usersService->getAllByRole($role);
            }

            $vm = new UsersViewModel($users, $role);

            require __DIR__ . '/../Views/users/index.php';
        } catch (\InvalidArgumentException $e) {
            http_response_code(404);
            echo 'Not found';
        }
    }

    public function create($role): void
    {
        try {
            $role = $this->usersService->normalizeRole((string)$role);
            $auth = $this->requireUsersManagementAccess($role);

            $isOwner = (($auth['role'] ?? '') === strtolower(UserRole::Owner->value));

            $user = new UserModel(['role' => $role]);

            if ($isOwner) {
                $user->salonId = (int)$auth['salonId'];
            }

            $isEdit = false;
            $vm = new UserFormViewModel($user, $role, $isEdit, null, $isOwner);

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
            $auth = $this->requireUsersManagementAccess($role);

            $user = new UserModel($_POST);
            $user->role = $role;

            if (($auth['role'] ?? '') === strtolower(UserRole::Owner->value)) {
                $user->salonId = (int)$auth['salonId'];
            }

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

        try {
            $role = $this->usersService->normalizeRole((string)$role);
            $id = (int)$id;

            $user = $this->usersService->getById($id);
            $auth = $this->requireUsersManagementAccess($role);

            if (($auth['role'] ?? '') === strtolower(UserRole::Owner->value)) {
                if (!$user || (int)$user->salonId !== (int)$auth['salonId']) {
                    http_response_code(404);
                    echo 'Not found';
                    return;
                }
            }

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
        try {
            $role = $this->usersService->normalizeRole((string)$role);
            $id = (int)$id;

            $auth = $this->requireUsersManagementAccess($role);
            $isOwner = (($auth['role'] ?? '') === strtolower(UserRole::Owner->value));

            $user = $this->usersService->getById($id);
            if (!$user || $user->role !== $role) {
                http_response_code(404);
                echo 'Not found';
                return;
            }

            // owner: only own salon staff
            if ($isOwner && (int)$user->salonId !== (int)$auth['salonId']) {
                http_response_code(404);
                echo 'Not found';
                return;
            }

            $vm = new UserFormViewModel($user, $role, true, null, $isOwner);

            require __DIR__ . '/../Views/users/edit.php';
        } catch (\InvalidArgumentException $e) {
            http_response_code(404);
            echo 'Not found';
        }
    }


    public function update($role, $id): void
    {
        try {
            $role = $this->usersService->normalizeRole((string)$role);
            $id = (int)$id;

            $auth = $this->requireUsersManagementAccess($role);

            $current = $this->usersService->getById($id);
            if (!$current || $current->role !== $role) {
                http_response_code(404);
                echo 'Not found';
                return;
            }

            // owner: only own salon staff
            if (($auth['role'] ?? '') === strtolower(UserRole::Owner->value)) {
                if ((int)$current->salonId !== (int)$auth['salonId']) {
                    http_response_code(404);
                    echo 'Not found';
                    return;
                }
            }

            $user = new UserModel($_POST);
            $user->role = $role;

            // owner: force salonId from session
            if (($auth['role'] ?? '') === strtolower(UserRole::Owner->value)) {
                $user->salonId = (int)$auth['salonId'];
            }

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

        try {
            $role = $this->usersService->normalizeRole((string)$role);
            $id = (int)$id;

            $user = $this->usersService->getById($id);
            $auth = $this->requireUsersManagementAccess($role);

            if (($auth['role'] ?? '') === strtolower(UserRole::Owner->value)) {
                if (!$user || (int)$user->salonId !== (int)$auth['salonId']) {
                    http_response_code(404);
                    echo 'Not found';
                    return;
                }
            }

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
