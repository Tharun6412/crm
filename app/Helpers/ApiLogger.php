<?php

namespace App\Helpers;

use Illuminate\Log\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Logger as MonologLogger;

class ApiLogger
{
    public static function getLogger(string $folder, string $apiName): Logger|MonologLogger
    {
        $date = now()->format('d m Y');          // 16-05-2026
        $fileName = $apiName . '-' . $date . '.log';       // acquisition-16-05-2026

        $logPath = storage_path("logs/{$folder}/{$fileName}");

        // Create folder if it doesn't exist
        if (!file_exists(dirname($logPath))) {
            mkdir(dirname($logPath), 0755, true);
        }

        $monolog = new MonologLogger($apiName);
        $monolog->pushHandler(new StreamHandler($logPath, MonologLogger::DEBUG));

        return $monolog;
    }

    public static function info(string $folder, string $apiName, string $message, array $context = []): void
    {
        self::getLogger($folder, $apiName)->info($message, $context);
    }

    public static function error(string $folder, string $apiName, string $message, array $context = []): void
    {
        self::getLogger($folder, $apiName)->error($message, $context);
    }

    public static function warning(string $folder, string $apiName, string $message, array $context = []): void
    {
        self::getLogger($folder, $apiName)->warning($message, $context);
    }

    public static function debug(string $folder, string $apiName, string $message, array $context = []): void
    {
        self::getLogger($folder, $apiName)->debug($message, $context);
    }
}