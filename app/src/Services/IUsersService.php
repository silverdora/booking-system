<?php

namespace App\Services;

use App\Models\UserModel;

interface IUsersService
{
    public function normalizeRole(string $roleFromUrl): string;
    /** @return UserModel[] */
    public function getAllByRole(string $role): array;
    public function getById(int $id): ?UserModel;
    public function create(UserModel $user): void;
    public function update(int $id, UserModel $user): void;
    public function delete(int $id): void;
    public function updateCustomerProfile(int $id, array $data): void;
    /** @return UserModel[] */
    public function getAllByRoleAndSalonId(string $role, int $salonId): array;
}

