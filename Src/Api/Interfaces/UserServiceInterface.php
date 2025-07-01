<?php

namespace Src\Api\Interfaces;

use Src\Api\Models\User;

interface UserServiceInterface
{
    public function create(User $user): ?User;

    public function findUserById(int $id): ?User;
}
