<?php

namespace Src\Api\Interfaces;

use Src\Api\Models\Transaction;

interface TransactionRepositoryInterface
{
    public function create(Transaction $transaction): ?Transaction;

    public function findById(string $id): ?Transaction;

    public function findUserTransactions(string $id): ?array;
}
