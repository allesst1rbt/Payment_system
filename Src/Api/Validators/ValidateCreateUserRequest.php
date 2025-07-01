<?php

namespace Src\Api\Validators;

use BlakvGhost\PHPValidator\Validator;
use BlakvGhost\PHPValidator\ValidatorException;
use Exception;
use Src\Api\Interfaces\ValidatorInterface;

class ValidateCreateUserRequest implements ValidatorInterface
{
    public static function validate(array $params): array|bool
    {
        $validator = new Validator($params, [
            'name' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|string',
            'document' => 'required|string',
        ]);

        try {
            if (! $validator->isValid()) {
                return $validator->getErrors();
            }

            return true;
        } catch (ValidatorException $validatorException) {
            throw new Exception('Validation error: '.$validatorException->getMessage(), 402);
        }
    }
}
