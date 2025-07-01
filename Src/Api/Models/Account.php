<?php

namespace Src\Api\Models;

class Account {
    public ?int $id = null;
    
    public ?float $balance = null;

    public ?int $userId = null;

    public function withDraw(float $amount) {
        $this->balance = $this->balance - $amount;
    }

    public function deposit(float $amount) {
        $this->balance = $this->balance + $amount;
    }
}