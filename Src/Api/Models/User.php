<?php

namespace Src\Api\Models;

class User
{
    public ?int $id = null;

    public ?string $name = null;

    public ?string $email = null;

    public ?string $password;

    public ?string $document = null;

    public ?string $type = null;

    public function isShopKeeper() {
        return $this->type == 'shopkeeper';
    }

}
