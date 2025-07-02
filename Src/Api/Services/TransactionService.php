<?php

namespace Src\Api\Services;

use PDO;
use Src\Api\Db\PdoConnection;
use Src\Api\Models\Transaction;
use Src\Api\Repositories\AccountRepositoryPdo;
use Src\Api\Repositories\TransactionRepositoryPdo;

class TransactionService
{
    const BASE_URL = 'https://run.mocky.io/';

    protected AuthorizationService $authorizationService;

    protected TransactionRepositoryPdo $transactionRepositoryPdo;

    protected PDO $pdoConnection;

    protected AccountRepositoryPdo $accountRepositoryPdo;

    public function __construct()
    {
        $this->authorizationService = new AuthorizationService;
        $this->transactionRepositoryPdo = new TransactionRepositoryPdo;
        $this->accountRepositoryPdo = new AccountRepositoryPdo;
        $this->pdoConnection = PdoConnection::getInstance();
    }

    public function transfer(array $data)
    {

        if (! $this->authorizationService->isServiceAvailable()) {
            throw new \Exception('Service is not available. Try again later.');
        }

        if (! $this->checkUserBalance($data['payer'], $data['amount'])) {
            throw new \Exception('The requested amount is not available.', 422);
        }

        $this->makeTransaction($data);
    }

    public function makeTransaction($data)
    {
        $transaction = new Transaction;
        $transaction->payerId = $data['payer'];
        $transaction->payeeId = $data['payee'];
        $transaction->amount = $data['amount'];

        try {

            $this->pdoConnection->beginTransaction();
            $transaction = $this->transactionRepositoryPdo->create($transaction);

            $accountPayer = $this->accountRepositoryPdo->findByUserId($transaction->payerId);
            $accountPayer->withDraw($transaction->amount);
            $this->accountRepositoryPdo->updateBalance($accountPayer->id, $accountPayer);

            $accountPayee = $this->accountRepositoryPdo->findByUserId($transaction->payeeId);
            $accountPayee->deposit($transaction->amount);
            $this->accountRepositoryPdo->updateBalance($accountPayee->id, $accountPayee);

            $this->pdoConnection->commit();

        } catch (\Exception $e) {
            $this->pdoConnection->rollback();
            throw new \Exception('Error while making transaction!');
        }
    }

    public function reversal($transactionId)
    {
        $transaction = $this->transactionRepositoryPdo->findById($transactionId);

        if (! $transaction) {
            throw new \Exception('Transaction not found', 404);
        }
        if (! $this->authorizationService->isServiceAvailable()) {
            throw new \Exception('Service is not available. Try again later.', 500);
        }

        if (! $this->checkUserBalance($transaction->payeeId, $transaction->amount)) {
            throw new \Exception('The requested amount is not available.', 422);
        }

        $arrayTransaction = [
            'payee' => $transaction->payerId,
            'payer' => $transaction->payeeId,
            'amount' => $transaction->amount,
        ];
        $this->makeTransaction($arrayTransaction);

    }

    public function userTransactions(string $id): ?array
    {
        return $this->transactionRepositoryPdo->findUserTransactions($id);
    }

    private function checkUserBalance($payer, $amount)
    {
        $wallet = $this->accountRepositoryPdo->findByUserId($payer);

        return $wallet->balance >= $amount;
    }
}
