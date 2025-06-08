<?php

namespace App\Core\Database;

use App\Contract\EnvironmentServiceInterface;
use PDO;
use PDOException;

class Connection
{
    private static ?PDO $connection = null;

    private static ?EnvironmentServiceInterface $environment = null;

    public static function setEnvironmentService(EnvironmentServiceInterface $environment): void
    {
        self::$environment = $environment;
    }

    public static function getInstance(): PDO
    {
        if (self::$connection === null) {
            self::$connection = self::createConnection();
        }

        return self::$connection;
    }

    private static function createConnection(): PDO
    {
        $config = self::getConfig();

        try {
            if ($config['driver'] === 'sqlite') {
                $dsn = 'sqlite:' . $config['database'];
                $pdo = new PDO($dsn, null, null, $config['options']);
            } else {
                $dsn = sprintf(
                    'mysql:host=%s;port=%s;dbname=%s;charset=%s',
                    $config['host'],
                    $config['port'],
                    $config['database'],
                    $config['charset']
                );

                $pdo = new PDO(
                    $dsn,
                    $config['username'],
                    $config['password'],
                    $config['options']
                );

                $pdo->exec("SET time_zone = '+00:00'");
            }

            return $pdo;
        } catch (PDOException $e) {
            throw new PDOException('Database connection failed: ' . $e->getMessage());
        }
    }

    private static function getConfig(): array
    {
        if (self::$environment === null) {
            throw new \RuntimeException('EnvironmentService not set. Call setEnvironmentService() first.');
        }

        $env = self::$environment;

        return [
            'driver' => 'mysql',
            'host' => $env->get('DB_HOST', 'mysql'),
            'port' => $env->get('DB_PORT', '3306'),
            'database' => $env->get('DB_DATABASE', 'app_db'),
            'username' => $env->get('DB_USERNAME', 'app_user'),
            'password' => $env->get('DB_PASSWORD', 'app_password'),
            'charset' => $env->get('DB_CHARSET', 'utf8mb4'),
            'options' => [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ],
        ];
    }
}
