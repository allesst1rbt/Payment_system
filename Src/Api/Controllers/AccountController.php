<?php

namespace Src\Api\Controllers;

use Exception;
use Src\Api\Services\AccountService;
use Src\Request;
use Src\Response;

class AccountController
{
    private AccountService $accountService;

    public function __construct()
    {
        $this->accountService = new AccountService;
    }

    public function deposit(Request $request, Response $response)
    {
        try {

            $data = (array) $request->getJSON();
            $data['userId'] = $_SESSION['user']['id'];

            $this->accountService->deposit($data);

            $response->toJSON([
                'success' => true,
            ]);

        } catch (Exception $e) {
            $response->toJSON([
                'success' => false,
                'error' => 'Unexpected error: '.$e->getMessage(),
            ]);
        }
    }

    public function withDraw(Request $request, Response $response)
    {
        try {

            $data = (array) $request->getJSON();
            $data['userId'] = $_SESSION['user']['id'];

            $this->accountService->withDraw($data);

            $response->toJSON([
                'success' => true,
            ]);

        } catch (Exception $e) {
            $response->toJSON([
                'success' => false,
                'error' => 'Unexpected error: '.$e->getMessage(),
            ]);
        }
    }
}
