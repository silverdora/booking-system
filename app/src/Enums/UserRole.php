<?php

namespace App\Enums;

enum UserRole: string
{
    case Customer = 'customer';
    case Owner = 'owner';
    case Receptionist = 'receptionist';
    case Specialist = 'specialist';

    public static function isValid(string $role): bool
    {
        foreach (self::cases() as $case) {
            if ($case->value === $role) return true;
        }
        return false;
    }
}

