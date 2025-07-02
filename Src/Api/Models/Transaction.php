<?php

namespace Src\Api\Models;

class Transaction
{
    public ?int $id = null;

    public ?float $amount = null;

    public ?int $payeeId = null;

    public ?int $payerId = null;
}
