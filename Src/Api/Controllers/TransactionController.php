<?php

namespace Src\Api\Controllers;

use Exception;
use Src\Api\Services\TransactionService;
use Src\Request;
use Src\Response;

class TransactionController
{
    private TransactionService $transactionService;

    public function __construct()
    {
        $this->transactionService = new TransactionService;
    }

    public function create(Request $request, Response $response)
    {
        try {

            $data = (array) $request->getJSON();
            $data['payer'] = $_SESSION['user']['id'];

            $this->transactionService->transfer($data);

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
