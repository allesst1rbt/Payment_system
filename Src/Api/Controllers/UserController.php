<?php

namespace Src\Api\Controllers;

use Exception;
use Src\Api\Models\User;
use Src\Api\Repositories\UserRepository;
use Src\Api\Services\UserService;
use Src\Api\Validators\ValidateCreateUserRequest;
use Src\Request;
use Src\Response;

class UserController
{
    private UserService $userService;

    public function __construct()
    {
        $this->userService = new UserService();
    }

    public function create(Request $request, Response $response)
    {
        try {

            $data = (array) $request->getJSON();

            $createdUser = $this->userService->create($data);

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

    public function login(Request $request, Response $response) {
         try {

            $data = (array) $request->getJSON();

            $createdUser = $this->userService->login($data);

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
