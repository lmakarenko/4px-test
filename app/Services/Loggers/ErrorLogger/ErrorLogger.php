<?php

namespace App\Services\Loggers\ErrorLogger;

use Illuminate\Support\Facades\Log;

/**
 * Class ErrorLogger
 *  Простая реализация логгера ошибок, используя встроенный логгер фреймворка
 *
 * @package App\Services\Loggers\ErrorLogger
 */
class ErrorLogger implements ErrorLoggerContract
{
    /**
     * Имя канала для логирования (config/logging.php)
     *
     * @var string
     */
    protected $channelName;

    /**
     * Канал для логирования
     *
     * @var \Psr\Log\LoggerInterface
     */
    protected $channel;

    public function __construct()
    {
        $this->channelName = 'app-errors';
        $this->channel = Log::channel($this->channelName);
    }

    /**
     * Логирование ошибки по строке
     *
     * @param $title
     * @param $msg
     */
    public function errorByString($title, $msg)
    {
        $this->channel->error(__($title) . ' : ' . __($msg));
    }

    /**
     * Логирование ошибки по обьекту исключения
     *
     * @param \Throwable $ex
     */
    public function errorByException($title, \Throwable $ex)
    {
        $this->channel->error(__($title) . ' : ' . $ex->__toString());
    }
}
