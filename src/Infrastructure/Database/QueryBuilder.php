<?php

namespace Src\Infrastructure\Database;

use PDO;

class QueryBuilder
{
    private PDO $db;

    private string $table;

    private array $where = [];

    private array $bindings = [];

    private ?int $limit = null;

    private ?int $offset = null;

    private ?string $orderBy = null;

    private string $orderDirection = 'ASC';

    public function __construct(
        PDO $db
    ) {

        $this->db = $db;
    }

    public function table(
        string $table
    ): self {

        $this->table = $table;

        return $this;
    }

    public function where(
        string $column,
        mixed $value
    ): self {

        $this->where[] =
            "{$column} = ?";

        $this->bindings[] =
            $value;

        return $this;
    }

    public function orderBy(
        string $column,
        string $direction = 'ASC'
    ): self {

        $this->orderBy =
            $column;

        $this->orderDirection =
            strtoupper($direction);

        return $this;
    }

    public function limit(
        int $limit
    ): self {

        $this->limit = $limit;

        return $this;
    }

    public function offset(
        int $offset
    ): self {

        $this->offset = $offset;

        return $this;
    }

    public function get(): array
    {
        $sql =
            "SELECT * FROM {$this->table}";

        if ($this->where) {

            $sql .=
                " WHERE " .
                implode(
                    ' AND ',
                    $this->where
                );
        }

        if ($this->orderBy) {

            $sql .=
                " ORDER BY {$this->orderBy} {$this->orderDirection}";
        }

        if ($this->limit) {

            $sql .=
                " LIMIT {$this->limit}";
        }

        if ($this->offset) {

            $sql .=
                " OFFSET {$this->offset}";
        }

        $stmt =
            $this->db->prepare($sql);

        $stmt->execute(
            $this->bindings
        );

        return $stmt->fetchAll(
            PDO::FETCH_ASSOC
        );
    }
}