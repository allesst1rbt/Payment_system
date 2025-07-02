<?php

namespace Src\Api\Models;

class Transaction
{
    public ?string $id = null;

    public ?float $amount = null;

    public ?string $payeeId = null;

    public ?string $payerId = null;
}
