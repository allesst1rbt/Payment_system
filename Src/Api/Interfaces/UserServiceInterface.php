<?php

namespace Src\Api\Interfaces;

use Src\Api\Models\User;

interface UserServiceInterface
{
    public function create(array $request): ?User;

    public function findUserById(string $id): ?User;

    public function login(object $auth): string;
}
