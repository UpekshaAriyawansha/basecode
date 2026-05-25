<?php

namespace Src\Infrastructure\Database;

use PDO;

class Database
{
    private PDO $pdo;

    public function __construct()
    {
        $dsn =
            "mysql:host=" . env('DB_HOST') .
            ";port=" . env('DB_PORT') .
            ";dbname=" . env('DB_DATABASE');

        $this->pdo = new PDO(
            $dsn,
            env('DB_USERNAME'),
            env('DB_PASSWORD')
        );

        $this->pdo->setAttribute(
            PDO::ATTR_ERRMODE,
            PDO::ERRMODE_EXCEPTION
        );
    }

    public function connection(): PDO
    {
        return $this->pdo;
    }
}