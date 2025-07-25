<?php

namespace Src\Api\Models;

class User
{
    public ?string $id = null;

    public ?string $name = null;

    public ?string $email = null;

    public ?string $password;

    public ?string $document = null;

    public ?string $type = null;

    public ?Account $account = null;
}
