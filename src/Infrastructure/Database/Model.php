<?php

namespace Src\Infrastructure\Database;

use PDO;

use Src\Infrastructure\Database\QueryBuilder;

abstract class Model
{
    protected static string $table;

    protected static function query():
        QueryBuilder {

        return new QueryBuilder(
            Database::connection()
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Get All
    |--------------------------------------------------------------------------
    */

    public static function all(): array
    {
        $results =
            static::query()
                ->table(static::$table)
                ->get();

        return array_map(

            fn ($item) =>

                new static($item),

            $results

        );
    }

    /*
    |--------------------------------------------------------------------------
    | Find
    |--------------------------------------------------------------------------
    */

    public static function find(
    int $id
        ): ?static {

            $result =
                static::query()

                    ->table(
                        static::$table
                    )

                    ->find($id);

            if (!$result) {

                return null;
            }

            return new static($result);
        }

    /*
    |--------------------------------------------------------------------------
    | Where
    |--------------------------------------------------------------------------
    */

    public static function where(
    string $column,
    mixed $value
): QueryBuilder {

    return static::query()

        ->table(
            static::$table
        )

        ->where(
            $column,
            $value
        );
}

    /*
    |--------------------------------------------------------------------------
    | Create
    |--------------------------------------------------------------------------
    */

    public static function create(
        array $data
    ): bool {

        return static::query()

            ->table(
                static::$table
            )

            ->insert($data);
    }





    protected function hasMany(
    string $related,
    string $foreignKey,
    string $localKey = 'id'
): array {

    $value =
        $this->{$localKey};

    return $related::where(
        $foreignKey,
        $value
    )->get();
}

protected function belongsTo(
    string $related,
    string $foreignKey,
    string $ownerKey = 'id'
): ?array {

    $value =
        $this->{$foreignKey};

    return $related::where(
        $ownerKey,
        $value
    )->first();
}


protected array $attributes = [];

public function __construct(
    array $attributes = []
) {

    $this->attributes =
        $attributes;
}

public function __get(
    string $key
): mixed {

    return $this->attributes[$key]
        ?? null;
}


}