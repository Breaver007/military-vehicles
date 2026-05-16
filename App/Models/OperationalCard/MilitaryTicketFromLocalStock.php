<?php

namespace App\Models\OperationalCard;

use App\Models\Model;

class MilitaryTicketFromLocalStock extends Model
{
    protected string $table = 'military_ticket_from_local_stock';
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
    public function getByTicketId(int $ticketId): array
    {
        return $this->query()
            ->where('ticket_id', '=', $ticketId)
            ->orderBy('date', 'DESC')
            ->get();
    }
    public function getTotalByTicketId(int $ticketId): float
    {
        $records = $this->getByTicketId($ticketId);
        return array_sum(array_column($records, 'value'));
    }
}