<?php

namespace App\Framework;

use App\Enums\UserRole;

final class Authentication
{
    public static function isLoggedIn(): bool
    {
        return isset($_SESSION['user']);
    }

    public static function user(): ?array
    {
        return $_SESSION['user'] ?? null;
    }

    public static function requireLogin(): void
    {
        if (!self::isLoggedIn()) {
            header('Location: /login');
            exit;
        }
    }

    /** @param string[] $roles */
    public static function requireRole(array $roles): void
    {
        self::requireLogin();

        $userRoleRaw = $_SESSION['user']['role'] ?? '';
        $userRole = strtolower(trim(self::roleToString($userRoleRaw)));

        $allowed = array_map(function ($r) {
            return strtolower(trim(self::roleToString($r)));
        }, $roles);

        if (!in_array($userRole, $allowed, true)) {
            http_response_code(403);
            echo 'Forbidden';
            exit;
        }
    }

    /**
     * @param mixed $role
     */
    private static function roleToString($role): string
    {
        if ($role instanceof UserRole) {
            return $role->value; //enum to string
        }
        return (string)$role; //string/int/etc
    }


    public static function login(array $userRow): void
    {
        $_SESSION['user'] = [
            'id' => (int)$userRow['id'],
            'role' => strtolower(trim((string)($userRow['role'] ?? ''))),
            'firstName' => (string)($userRow['firstName'] ?? ''),
            'lastName' => (string)($userRow['lastName'] ?? ''),
            'salonId' => isset($userRow['salonId']) && $userRow['salonId'] !== null
                ? (int)$userRow['salonId']
                : null,
        ];
    }


    public static function logout(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        // clear session
        $_SESSION = [];

        // remove session cookie
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }

        session_destroy();
    }

}
