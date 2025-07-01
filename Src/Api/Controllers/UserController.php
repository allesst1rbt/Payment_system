<?php

namespace Src\Api\Controllers;

use Exception;
use Src\Api\Models\User;
use Src\Api\Repositories\UserRepository;
use Src\Api\Validators\ValidateCreateUserRequest;
use Src\Request;
use Src\Response;

class UserController
{
    private UserRepository $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository;
    }

    public function create(Request $request, Response $response)
    {
        try {
           
            $data =(array) $request->getJSON();
        
            $validated = ValidateCreateUserRequest::validate($data);
            
            if (is_array($validated)) {
                throw new Exception('Invalid data received for user creation.', 502);
            }

            $user = new User();
            $user->name = $data['name'];
            $user->email = $data['email'];
            $user->document = $data['document'];
            $user->password = $data['password'];

            $createdUser = $this->userRepository->create($user);

            $response->toJSON([
                'success' => true,
                'user' => [
                    'id' => $createdUser->id,
                    'name' => $createdUser->name,
                    'email' => $createdUser->email,
                    'document' => $createdUser->document,
                ],
            ]);

        } catch (Exception $e) {
            $response->toJSON([
                'success' => false,
                'error' => 'Unexpected error: '.$e->getMessage(),
            ]);
        }
    }
}
