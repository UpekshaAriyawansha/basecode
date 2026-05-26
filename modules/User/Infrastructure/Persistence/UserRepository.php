<?php

namespace Modules\User\Infrastructure\Persistence;

use Src\Infrastructure\Database\Database;
use PDO;

class UserRepository
{
    private PDO $pdo;

    public function __construct()
    {
        $database = new Database();

        $this->pdo = $database->connection();
    }

    public function findByEmail(
        string $email
    ): ?array {

        $stmt = $this->pdo->prepare(

            "SELECT * FROM users
             WHERE email = :email
             LIMIT 1"

        );

        $stmt->execute([
            'email' => $email
        ]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return $user ?: null;
    }

    public function all(): array
{
    $stmt = $this->pdo->query(

        "SELECT
            id,
            first_name,
            last_name,
            email,
            status,
            created_at
         FROM users"

    );

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}





public function create(array $data): string
{
    $stmt = $this->pdo->prepare(

        "INSERT INTO users
        (first_name, last_name, email, password)
        VALUES
        (:first_name, :last_name, :email, :password)"

    );

    $stmt->execute([
        'first_name' => $data['first_name'],
        'last_name'  => $data['last_name'],
        'email'      => $data['email'],
        'password'   => $data['password']
    ]);

    return $this->pdo->lastInsertId();
}
}