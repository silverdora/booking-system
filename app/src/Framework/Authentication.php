<?php

namespace App\Framework;

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

        $role = strtolower(trim((string)($_SESSION['user']['role'] ?? '')));

        if ($role === null || !in_array($role, $roles, true)) {
            http_response_code(403);
            echo 'Forbidden';
            exit;
        }
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
        unset($_SESSION['user']);
        session_destroy();
    }
}
