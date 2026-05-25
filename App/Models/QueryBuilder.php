<?php

namespace App\Models;

use PDO;

class QueryBuilder
{
    private PDO $db;
    private string $table;
    private array $wheres = [];
    private array $params = [];
    private ?string $orderBy = null;
    private ?string $orderDirection = 'ASC';
    private ?int $limit = null;
    private ?int $offset = null;

    public function __construct(PDO $db, string $table)
    {
        $this->db = $db;
        $this->table = $table;
    }

    /**
     * Добавить условие WHERE
     */
    public function where(string $column, string $operator, $value, string $boolean = 'AND'): self
    {
        $allowedOperators = ['=', '>', '<', '>=', '<=', '!=', 'LIKE', 'NOT LIKE'];

        if (!in_array($operator, $allowedOperators)) {
            throw new \InvalidArgumentException("Недопустимый оператор: {$operator}");
        }

        $paramKey = 'where_' . count($this->params);
        $this->wheres[] = [
            'type' => $boolean,
            'sql' => "`{$column}` {$operator} :{$paramKey}"
        ];
        $this->params[$paramKey] = $value;

        return $this;
    }

    /**
     * Добавить условие OR WHERE
     */
    public function orWhere(string $column, string $operator, $value): self
    {
        return $this->where($column, $operator, $value, 'OR');
    }

    public function whereLeft(string $column, int $length, string $value, string $operator = '=', string $boolean = 'AND'): self
    {
        $paramKey = 'left_' . count($this->params);

        $this->wheres[] = [
            'type' => $boolean,
            'sql' => "LEFT(`{$column}`, {$length}) $operator :{$paramKey}"
        ];
        $this->params[$paramKey] = $value;

        return $this;
    }

    /**
     * Добавить условие OR WHERE для первых N символов колонки
     */
    public function orWhereLeft(string $column, int $length, string $value, string $operator = '=',): self
    {
        return $this->whereLeft($column, $length, $value, $operator, 'OR');
    }

    /**
     * Добавить сортировку
     */
    public function orderBy(string $column, string $direction = 'ASC'): self
    {
        $this->orderBy = $column;
        $this->orderDirection = strtoupper($direction) === 'DESC' ? 'DESC' : 'ASC';
        return $this;
    }

    /**
     * Добавить лимит
     */
    public function limit(int $limit): self
    {
        $this->limit = $limit;
        return $this;
    }

    /**
     * Добавить смещение
     */
    public function offset(int $offset): self
    {
        $this->offset = $offset;
        return $this;
    }

    /**
     * Выполнить запрос и получить результаты
     */
    public function get(): array
    {
        $sql = "SELECT * FROM {$this->table}";

        // Добавляем WHERE
        if (!empty($this->wheres)) {
            $whereParts = [];
            foreach ($this->wheres as $index => $where) {
                if ($index === 0) {
                    $whereParts[] = $where['sql'];
                } else {
                    $whereParts[] = $where['type'] . ' ' . $where['sql'];
                }
            }
            $sql .= " WHERE " . implode(' ', $whereParts);
        }

        // Добавляем ORDER BY
        if ($this->orderBy) {
            $sql .= " ORDER BY `{$this->orderBy}` {$this->orderDirection}";
        }

        // Добавляем LIMIT и OFFSET
        if ($this->limit !== null) {
            $sql .= " LIMIT {$this->limit}";
            if ($this->offset !== null) {
                $sql .= " OFFSET {$this->offset}";
            }
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute($this->params);
        return $stmt->fetchAll();
    }

    /**
     * Получить первую запись
     */
    public function first(): ?array
    {
        $this->limit(1);
        $results = $this->get();
        return $results[0] ?? null;
    }

    /**
     * Удалить записи по текущим условиям WHERE
     */
    public function delete(): bool
    {
        $sql = "DELETE FROM {$this->table}";

        if (!empty($this->wheres)) {
            $whereParts = [];
            foreach ($this->wheres as $index => $where) {
                if ($index === 0) {
                    $whereParts[] = $where['sql'];
                } else {
                    $whereParts[] = $where['type'] . ' ' . $where['sql'];
                }
            }
            $sql .= " WHERE " . implode(' ', $whereParts);
        }

        $stmt = $this->db->prepare($sql);
        return $stmt->execute($this->params);
    }
}