<?php

namespace Src\Api\Interfaces;

interface ValidatorInterface
{
    public static function validate(array $params): array|bool;
}
