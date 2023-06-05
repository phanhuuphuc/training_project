<?php

namespace Mi\L5Core\Exceptions;

use Mas\L5Core\Exceptions\BaseException;

class RepositoryException extends BaseException
{
    public static function invalidMethod()
    {
        return self::code('repository.invalid_method');
    }

    public static function invalidModel()
    {
        return self::code('repository.invalid_model');
    }
}
