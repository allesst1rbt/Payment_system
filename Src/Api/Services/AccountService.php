<?php

namespace Src\Api\Services;

use Exception;
use Src\Api\Models\Account;
use Src\Api\Repositories\AccountRepositoryPdo;

class AccountService
{
    private AccountRepositoryPdo $accountRepositoryPdo;

    public function __construct()
    {
        $this->accountRepositoryPdo = new AccountRepositoryPdo;
    }

    public function deposit($data): ?Account
    {
        $account = $this->accountRepositoryPdo->findByUserId($data['userId']);

        $account->deposit($data['amount']);

        return $this->accountRepositoryPdo->updateBalance($account->id, $account);
    }

    public function withDraw($data): ?Account
    {
        $account = $this->accountRepositoryPdo->findByUserId($data['userId']);

        if ($this->checkWithDraw($account, $data['amount'])) {
            throw new Exception('not enough money', 500);
        }

        $account->withDraw($data['amount']);

        return $this->accountRepositoryPdo->updateBalance($account->id, $account);
    }

    private function checkWithDraw(Account $wallet, float $amount)
    {
        return $wallet->balance < $amount;
    }
}
