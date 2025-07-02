<?php

namespace Src\Api\Interfaces;

use Src\Api\Models\Transaction;

interface TransactionRepositoryInterface
{
    public function create(Transaction $transaction): ?Transaction;
}
