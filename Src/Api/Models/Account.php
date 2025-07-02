<?php

namespace Src\Api\Models;

class Account
{
    public ?string $id = null;

    public ?float $balance = null;

    public ?string $userId = null;

    public function withDraw(float $amount)
    {
        $this->balance = $this->balance - $amount;
    }

    public function deposit(float $amount)
    {
        $this->balance = $this->balance + $amount;
    }
}
