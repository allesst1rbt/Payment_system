<?php

namespace Src;

use Dotenv\Dotenv;

class App
{
    public static function run()
    {
        Logger::enableSystemLogs();
    }
}
