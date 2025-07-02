<?php

namespace Src\Api\Controllers;

use Exception;
use Src\Api\Services\TransactionNotifierService;
use Src\Api\Services\TransactionService;
use Src\Request;
use Src\Response;

class TransactionController
{
    private TransactionService $transactionService;

    private TransactionNotifierService $transactionNotifierService;

    public function __construct()
    {
        $this->transactionService = new TransactionService;
        $this->transactionNotifierService = new TransactionNotifierService;

    }

    public function create(Request $request, Response $response)
    {

        try {

            $data = (array) $request->getJSON();
            $data['payer'] = $_SESSION['user']['id'];

            $this->transactionService->transfer($data);
            $this->transactionNotifierService->sendNotification();
            // isso era pra ser um job mas como to fazendo tudo isso na mão e o php e single thread
            // e ainda vou jogar num docker acabei tendo complicações

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
