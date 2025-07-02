<?php

namespace Src\Api\Interfaces;

use Src\Api\Models\Account;

interface AccountRespositoryInterface
{
    public function create(Account $account): ?Account;

    public function updateBalance(int $id, Account $account): ?Account;

    public function findById(int $id): ?Account;

    public function findByUserId(int $id): ?Account;

    public function delete(int $id): void;
}
