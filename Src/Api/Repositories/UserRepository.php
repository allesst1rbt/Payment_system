<?php

namespace Src\Api\Repositories;

use Src\Api\Interfaces\UserServiceInterface;
use Src\Api\Models\User;
use Src\Api\Services\UserServicePdo;

class UserRepository implements UserServiceInterface
{
    private UserServicePdo $userServicePdo;

    public function __construct()
    {
        $this->userServicePdo = new UserServicePdo;
    }

    public function create(User $user): ?User
    {
        return $this->userServicePdo->create($user);
    }

    public function findUserById(int $id): ?User
    {
        return $this->userServicePdo->findUserById($id);
    }
}
