<?php

namespace Src\Infrastructure\Database;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $connection = null;

    public static function connection(): PDO
    {
        if (self::$connection === null) {

            try {

                self::$connection = new PDO(

                    sprintf(

                        'mysql:host=%s;dbname=%s;charset=utf8mb4',

                        $_ENV['DB_HOST'],
                        $_ENV['DB_DATABASE']

                    ),

                    $_ENV['DB_USERNAME'],
                    $_ENV['DB_PASSWORD']

                );

                self::$connection->setAttribute(
                    PDO::ATTR_ERRMODE,
                    PDO::ERRMODE_EXCEPTION
                );

                self::$connection->setAttribute(
                    PDO::ATTR_DEFAULT_FETCH_MODE,
                    PDO::FETCH_ASSOC
                );

            } catch (PDOException $e) {

                throw new PDOException(
                    $e->getMessage()
                );
            }
        }

        return self::$connection;
    }
}