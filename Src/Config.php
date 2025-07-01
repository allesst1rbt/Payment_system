<?php

namespace Src;

class Config
{
    public static function get($key, $default = null)
    {
    
        return ! empty($_ENV[$key]) ? $_ENV[$key] : $default;
    }
}
