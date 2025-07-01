<?php

namespace Src\Api\Services;

use Exception;
use Src\Api\Interfaces\UserServiceInterface;
use Src\Api\Models\User;
use Src\Api\Repositories\UserRepositoryPdo;
use Src\Api\Validators\ValidateCreateUserRequest;

class UserService implements UserServiceInterface
{
    private UserRepositoryPdo $userRespositoryPdo;

    public function __construct()
    {
        $this->userRespositoryPdo = new UserRepositoryPdo();
    }

    public function create(array $request): ?User
    {
        $validated = ValidateCreateUserRequest::validate($request);

        if (is_array($validated)) {
            throw new Exception(message: 'Invalid data received for user creation.', 502);
        }

        $user = new User;
        $user->name = $request['name'];
        $user->email = $request['email'];
        $user->document = $request['document'];
        $user->password = $request['password'];

        return  $this->userRespositoryPdo->create($user);
    }

    public function findUserById(int $id): ?User
    {
       return $this->userRespositoryPdo->findByUserId($id);
    }
    
     public function login(object $auth): object
    {
       $user = $this->userRespositoryPdo->findByUserEmail($auth->email);
       if(!$user){
          throw new Exception('Invalid Credentials', 502);

       }
       if (!password_verify($auth->password, $user->password)) {
           throw new Exception('Invalid Credentials', 502);
       }
       return
    }
}
