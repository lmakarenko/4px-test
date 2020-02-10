<?php

namespace App\Services\Loggers\ErrorLogger;

/**
 * Interface ErrorLoggerContract
 * Интерфейс для реализаций логгера ошибок
 *
 * @package App\Services\Loggers
 */
interface ErrorLoggerContract
{
    public function errorByString($title, $msg);

    public function errorByException($title, \Throwable $ex);
}
