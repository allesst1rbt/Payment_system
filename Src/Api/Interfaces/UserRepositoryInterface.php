<?php

namespace Src\Api\Interfaces;

use Src\Api\Models\User;

interface UserRepositoryInterface
{
    public function create(User $user): ?User;

    public function findByUserId(string $id): ?User;

    public function findByUserEmail(string $email): ?User;
}
