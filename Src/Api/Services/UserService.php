<?php

namespace Src\Api\Services;

use Exception;
use Src\Api\Interfaces\UserServiceInterface;
use Src\Api\Models\Account;
use Src\Api\Models\User;
use Src\Api\Repositories\AccountRepositoryPdo;
use Src\Api\Repositories\UserRepositoryPdo;
use Src\Api\Validators\ValidateCreateUserRequest;
use Src\Jwt;

class UserService implements UserServiceInterface
{
    private UserRepositoryPdo $userRespositoryPdo;

    private AccountRepositoryPdo $accountRepositoryPdo;

    public function __construct()
    {
        $this->userRespositoryPdo = new UserRepositoryPdo;
        $this->accountRepositoryPdo = new AccountRepositoryPdo;
    }

    public function create(array $request): ?User
    {
        $validated = ValidateCreateUserRequest::validate($request);

        if (is_array($validated)) {
            throw new Exception('Invalid data received for user creation.', 502);
        }

        $user = new User;
        $user->name = $request['name'];
        $user->email = $request['email'];
        $user->document = $request['document'];
        $user->password = $request['password'];
        $user->type = $request['type'];

        $user = $this->userRespositoryPdo->create($user);

        $account = new Account;
        $account->balance = 100.00;
        $account->userId = $user->id;

        $user->account = $this->accountRepositoryPdo->create($account);

        return $user;

    }

    public function findUserById(string $id): ?User
    {
        $user = $this->userRespositoryPdo->findByUserId($id);
        $user->account = $this->accountRepositoryPdo->findByUserId($user->id);

        return $user;
    }

    public function login(object $auth): string
    {
        $user = $this->userRespositoryPdo->findByUserEmail($auth->email);
        if (! $user) {
            throw new Exception('Invalid Credentials', 502);
        }
        if (! password_verify($auth->password, $user->password)) {
            throw new Exception('Invalid Credentials', 502);
        }

        return (new Jwt)->encode((array) $user);
    }
}
