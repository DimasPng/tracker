<?php

namespace App\Util;

class Logger
{
    public static function error(string $message): void
    {
        $logFile = __DIR__ . '/../../logs/app.log';
        $logDir = dirname($logFile);

        if (!is_dir($logDir)) {
            mkdir($logDir, 0o755, true);
        }

        $formatted = '[' . date('Y-m-d H:i:s') . '] ERROR: ' . $message . PHP_EOL;
        file_put_contents($logFile, $formatted, FILE_APPEND | LOCK_EX);
    }
}
