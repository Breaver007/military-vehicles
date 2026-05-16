<?php

namespace App\Models\OperationalCard;

use App\Models\Model;

class MilitaryModelMachine extends Model
{
    protected string $table = 'military_model_machine';
    protected string $primaryKey = 'id';

    public function allWithRelations(): array
    {
        $sql = "SELECT m.*, 
                       u.name as unit_name, 
                       f.name as fuel_name 
                FROM {$this->table} m
                LEFT JOIN military_unit u ON m.m_unit = u.id
                LEFT JOIN military_fuel f ON m.m_fuel = f.id
                WHERE m.is_active = 1
                ORDER BY m.`order` ASC";

        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
    public function allWithRelationsIsActive(): array
    {
        $sql = "SELECT m.*, 
                       u.name as unit_name, 
                       f.name as fuel_name 
                FROM {$this->table} m
                LEFT JOIN military_unit u ON m.m_unit = u.id
                LEFT JOIN military_fuel f ON m.m_fuel = f.id
                ORDER BY m.`order` ASC";

        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    /**
     * Получить запись с отношениями по ID
     */
    public function findWithRelations(int $id)
    {
        $sql = "SELECT m.*, 
                       u.name as unit_name, 
                       f.name as fuel_name 
                FROM {$this->table} m
                LEFT JOIN military_unit u ON m.m_unit = u.id
                LEFT JOIN military_fuel f ON m.m_fuel = f.id
                WHERE m.{$this->primaryKey} = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Получить максимальное значение order
     */
    public function getMaxOrder(): int
    {
        $stmt = $this->db->query("SELECT MAX(`order`) as max_order FROM {$this->table}");
        $result = $stmt->fetch();
        return $result['max_order'] ?? 0;
    }

}