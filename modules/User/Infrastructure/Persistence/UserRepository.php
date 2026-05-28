<?php

namespace Modules\User\Infrastructure\Persistence;

use Src\Infrastructure\Database\Database;

use PDO;

use Src\Infrastructure\Database\QueryBuilder;

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

            "SELECT

                users.*,

                roles.slug AS role

            FROM users

            LEFT JOIN roles
                ON roles.id = users.role_id

            WHERE users.email = :email

            LIMIT 1"

        );

        $stmt->execute([
            'email' => $email
        ]);

        $user =
            $stmt->fetch(\PDO::FETCH_ASSOC);

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

public function findById(
    int $id
): ?array {

    $stmt = $this->pdo->prepare(

        "SELECT *
         FROM users
         WHERE id = :id
         LIMIT 1"

    );

    $stmt->execute([
        'id' => $id
    ]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    return $user ?: null;
}

public function update(
    int $id,
    array $data
): bool {

    $stmt = $this->pdo->prepare(

        "UPDATE users
         SET

            first_name = :first_name,
            last_name  = :last_name,
            email      = :email

         WHERE id = :id"

    );

    return $stmt->execute([

        'id' => $id,

        'first_name' => $data['first_name'],

        'last_name' => $data['last_name'],

        'email' => $data['email']

    ]);
}

public function delete(
    int $id
): bool {

    $stmt = $this->pdo->prepare(

        "DELETE FROM users
         WHERE id = :id"

    );

    return $stmt->execute([
        'id' => $id
    ]);
}


public function permissions(
    int $userId
): array {

    $stmt = $this->pdo->prepare(

        "SELECT permissions.slug

         FROM users

         INNER JOIN roles
            ON roles.id = users.role_id

         INNER JOIN role_permissions
            ON role_permissions.role_id = roles.id

         INNER JOIN permissions
            ON permissions.id =
                role_permissions.permission_id

         WHERE users.id = :id"

    );

    $stmt->execute([
        'id' => $userId
    ]);

    return array_column(

        $stmt->fetchAll(
            \PDO::FETCH_ASSOC
        ),

        'slug'

    );
}


public function paginate(
    int $page = 1,
    int $perPage = 10
): array {

    $offset =
        ($page - 1) * $perPage;

    $query =
        new QueryBuilder(
            Database::connection()
        );

    return $query
        ->table('users')
        ->orderBy('id', 'DESC')
        ->limit($perPage)
        ->offset($offset)
        ->get();
}


}