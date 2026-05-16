<?php
// App/Models/Model.php

namespace App\Models;

use App\Database\Database;
use PDO;

abstract class Model
{
    protected PDO $db;
    protected string $table;
    protected string $primaryKey = 'id';

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    /**
     * Получить все записи
     */
    public function all(): array
    {
        $stmt = $this->db->query("SELECT * FROM {$this->table} ORDER BY {$this->primaryKey} DESC");
        return $stmt->fetchAll();
    }

    /**
     * Найти запись по ID
     */
    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE {$this->primaryKey} = :id");
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch();

        return $result ?: null;
    }

    /**
     * Создать новую запись
     */
    public function create(array $data): ?array
    {
        $escapedColumns = array_map(function($column) {
            return "`{$column}`";
        }, array_keys($data));

        $columns = implode(', ', $escapedColumns);
        $placeholders = ':' . implode(', :', array_keys($data));

        $sql = "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})";
        $stmt = $this->db->prepare($sql);

        if ($stmt->execute($data)) {
            $id = (int) $this->db->lastInsertId();
            return $this->find($id);
        }

        return null;
    }

    /**
     * Обновить запись
     */
    public function update(int $id, array $data): ?array
    {
        $fields = '';
        foreach (array_keys($data) as $key) {
            $fields .= "`{$key}` = :{$key}, ";
        }
        $fields = rtrim($fields, ', ');

        $sql = "UPDATE {$this->table} SET {$fields} WHERE {$this->primaryKey} = :id";
        $data['id'] = $id;

        $stmt = $this->db->prepare($sql);

        if ($stmt->execute($data)) {
            return $this->find($id);
        }

        return null;
    }

    /**
     * Удалить запись
     */
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE `{$this->primaryKey}` = :id");
        return $stmt->execute(['id' => $id]);
    }

    /**
     * Поиск по условию
     */
    public function where(string $column, string $operator, $value): array
    {
        $allowedOperators = ['=', '>', '<', '>=', '<=', '!=', 'LIKE'];

        if (!in_array($operator, $allowedOperators)) {
            throw new \InvalidArgumentException("Недопустимый оператор: {$operator}");
        }
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE `{$column}` {$operator} :value");

        $stmt->execute(['value' => $value]);

        return $stmt->fetchAll();
    }

    /**
     * Найти первую запись по условию
     */
    public function firstWhere(string $column, string $operator, $value): ?array
    {
        $results = $this->where($column, $operator, $value);
        return $results[0] ?? null;
    }

    /**
     * Подсчитать количество записей
     */
    public function count(): int
    {
        $stmt = $this->db->query("SELECT COUNT(*) as count FROM {$this->table}");
        return (int) $stmt->fetch()['count'];
    }

    public function query(): QueryBuilder
    {
        return new QueryBuilder($this->db, $this->table);
    }

    /**
     * Пагинация
     */
    public function paginate(int $page = 1, int $perPage = 10): array
    {
        $offset = ($page - 1) * $perPage;

        $stmt = $this->db->prepare("
            SELECT * FROM {$this->table} 
            ORDER BY {$this->primaryKey} DESC 
            LIMIT :limit OFFSET :offset
        ");

        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        $total = $this->count();
        $lastPage = ceil($total / $perPage);

        return [
            'data' => $stmt->fetchAll(),
            'current_page' => $page,
            'per_page' => $perPage,
            'total' => $total,
            'last_page' => $lastPage,
            'has_next' => $page < $lastPage,
            'has_prev' => $page > 1
        ];
    }

    /**
     * Начать транзакцию
     */
    public function beginTransaction(): void
    {
        $this->db->beginTransaction();
    }

    /**
     * Подтвердить транзакцию
     */
    public function commit(): void
    {
        $this->db->commit();
    }

    /**
     * Откатить транзакцию
     */
    public function rollback(): void
    {
        $this->db->rollBack();
    }
}