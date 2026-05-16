<?php
namespace App\Database;

class Database
{
    private static ?\PDO $connection = null;

    private static array $config = [
        'host' => '127.0.1.14',
        'dbname' => 'military-vehicles',
        'username' => 'root',
        'password' => '',
        'charset' => 'utf8mb4'
    ];

    public static function getConnection(): \PDO
    {
        if (self::$connection === null) {
            try {
                $dsn = sprintf(
                    'mysql:host=%s;dbname=%s;charset=%s',
                    self::$config['host'],
                    self::$config['dbname'],
                    self::$config['charset']
                );

                self::$connection = new \PDO(
                    $dsn,
                    self::$config['username'],
                    self::$config['password'],
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