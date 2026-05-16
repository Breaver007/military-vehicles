<?php

namespace App\Models\OperationalCard;

use App\Models\Model;

class MilitaryTicketLocal extends Model
{
    protected string $table = 'military_ticket_local';
    protected string $primaryKey = 'id';

    public function getAllToParentId($id)
    {
        $stmt = $this->db->query("SELECT * FROM {$this->table} WHERE ticket_id = {$id} ORDER BY id");
        return $stmt->fetchAll();
    }
}