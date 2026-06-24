<?php
namespace App\Database;

class Database
{
    private static ?\PDO $connection = null;

    private static function getConfig(): array
    {
        return [
            'host' => $_ENV['DB_HOST'] ?? '127.0.0.1',
            'dbname' => $_ENV['DB_NAME'] ?? 'military-vehicles',
            'username' => $_ENV['DB_USERNAME'] ?? 'root',
            'password' => $_ENV['DB_PASSWORD'] ?? '',
            'charset' => $_ENV['DB_CHARSET'] ?? 'utf8mb4',
        ];
    }

    public static function getConnection(): \PDO
    {
        if (self::$connection === null) {
            try {
                $config = self::getConfig();

                $dsn = sprintf(
                    'mysql:host=%s;dbname=%s;charset=%s',
                    $config['host'],
                    $config['dbname'],
                    $config['charset']
                );

                self::$connection = new \PDO(
                    $dsn,
                    $config['username'],
                    $config['password'],
                    [
                        \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                        \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                        \PDO::ATTR_EMULATE_PREPARES => false
                    ]
                );
            } catch (\PDOException $e) {
                die('Ошибка подключения к базе данных: ' . $e->getMessage());
            }
        }

        return self::$connection;
    }
}