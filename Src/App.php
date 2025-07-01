<?php 
namespace Src;

use Src\Logger;

class App
{
    public static function run()
    {
        Logger::enableSystemLogs();
    }
}