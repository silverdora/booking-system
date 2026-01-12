<?php

namespace App\Repositories;

use App\Models\UserModel;

interface IUsersRepository
{
    /** @return UserModel[] */
    public function getAllByRole(string $role): array;
    public function getById(int $id): ?UserModel;
    public function create(UserModel $user): void;
    public function update(int $id, UserModel $user): void;
    public function delete(int $id): void;
    public function getByEmail(string $email): ?array;
    public function createUser(array $data): int;
}
