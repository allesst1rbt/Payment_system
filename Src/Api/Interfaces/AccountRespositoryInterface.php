<?php

namespace Src\Api\Interfaces;

use Src\Api\Models\Account;

interface AccountRespositoryInterface
{
    public function create(Account $account): ?Account;

    public function updateBalance(string $id, Account $account): ?Account;

    public function findById(string $id): ?Account;

    public function findByUserId(string $id): ?Account;
}
