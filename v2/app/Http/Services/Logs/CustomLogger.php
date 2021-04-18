<?php

namespace App\Http\Services\Logs;

use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class CustomLogger {


    public function __construct()
    {
        //
    }

    /**
     * @param $name
     * @param null $format
     * @param null $date_format
     * @return Logger
     * @throws \Exception
     */
    public function getLogger($name, $format = null, $date_format = null){

        if(empty($format)) $format = "%datetime% | %context% | %level_name% | %message%\n";
        if(empty($date_format)) $date_format = "Y-m-d G:i:s";

        $handler = new StreamHandler(storage_path("logs/$name.log"), Logger::DEBUG);
        $handler->setFormatter(new LineFormatter($format, $date_format));

        $logger = new Logger($name);
        $logger->pushHandler($handler);

        return $logger;
    }

}