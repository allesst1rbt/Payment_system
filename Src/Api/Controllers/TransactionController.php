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

    public function show(Request $request, Response $response)
    {
        try {

            $transactions = $this->transactionService->userTransactions($_SESSION['user']['id']);

            $response->toJSON([
                'success' => true,
                'transactions' => $transactions,
            ]);

        } catch (Exception $e) {
            $response->toJSON([
                'success' => false,
                'error' => 'Unexpected error: '.$e->getMessage(),
            ]);
        }
    }

    public function reversal(Request $request, Response $response)
    {
        try {

            $data = (array) $request->getJSON();

            $this->transactionService->reversal($data['transactionId']);

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
