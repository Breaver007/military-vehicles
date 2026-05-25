<?php

namespace App\Models\OperationalCard;

use App\Models\Model;

class MilitaryButter extends Model
{
    protected string $table = 'military_butter';
    protected string $primaryKey = 'id';

    public function all(): array
    {
        $stmt = $this->db->query("SELECT * FROM {$this->table} WHERE is_active = 1 ORDER BY name");
        return $stmt->fetchAll();
    }
    public function allIsActive(): array
    {
        $stmt = $this->db->query("SELECT * FROM {$this->table} ORDER BY name");
        return $stmt->fetchAll();
    }
}
