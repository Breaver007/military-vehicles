<?php

namespace App\Models\OperationalCard;

use App\Models\Model;

class MilitaryTicket extends Model
{
    protected string $table = 'military_ticket';
    protected string $primaryKey = 'id';

    public function allWithRelations(): array
    {
        $sql = "SELECT t.*, 
                       m.name as machine_name, 
                       m.registr_plate
                FROM {$this->table} t
                LEFT JOIN military_model_machine m ON t.m_model_machine = m.id
                ORDER BY t.data_ticket DESC";

        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    /**
     * Найти карточку по ID
     */
    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE {$this->primaryKey} = :id");
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch();

        return $result ?: null;
    }

    public function getMaxKilometres($id): int
    {
        $result = $this->query()
            ->where('m_model_machine', '=', $id)
            ->orderBy('kilometres_speedometer_start', 'DESC')
            ->limit(1)
            ->first();
        if ($result != null) {
            $total = $result['kilometres_speedometer_start'] + $result['kilometres_speedometer'];
        }
        return $total ?? 0;
    }

    public function getOpeningBalanceFuel($id): int
    {
        $result = $this->query()
            ->where('m_model_machine', '=', $id)
            ->orderBy('kilometres_speedometer_start', 'DESC')
            ->limit(1)
            ->first();
        if ($result != null) {
            $total = $result['closing_balance_fuel'];
        }
        return $total ?? 0;
    }

    /**
     * Проверка, существует ли номер путевого листа в течение года
     *
     * @param string $numberTicket Номер путевого листа
     * @param string $currentDate Текущая дата (Y-m-d)
     * @param int|null $excludeId ID записи, которую нужно исключить из проверки (для обновления)
     * @return bool Возвращает true, если номер уже существует в течение года
     */
    public function isNumberTicketExistsInYear(string $numberTicket, string $currentDate, ?int $excludeId = null): bool
    {
        // Рассчитываем дату год назад
        $oneYearAgo = date('Y-m-d', strtotime($currentDate . ' -1 year'));
        $oneYearLater = date('Y-m-d', strtotime($currentDate . ' +1 year'));

        $query = $this->query()
            ->where('number_ticket', '=', $numberTicket)
            ->where('data_ticket', '>=', $oneYearAgo)
            ->where('data_ticket', '<=', $oneYearLater);

        // Если это обновление, исключаем текущую запись
        if ($excludeId !== null) {
            $query = $query->where('id', '!=', $excludeId);
        }

        $result = $query->first();

        return $result !== null;
    }

    /**
     * Проверка, существует ли номер путевого листа в течение года
     * с учетом года, указанного в дате
     *
     * @param string $numberTicket Номер путевого листа
     * @param string $date Дата (Y-m-d)
     * @param int|null $excludeId ID записи, которую нужно исключить
     * @return bool
     */
    public function isNumberTicketDuplicateInYear(string $numberTicket, string $date, ?int $excludeId = null): bool
    {
        // Получаем год из даты
        $year = date('Y', strtotime($date));

        // Проверяем за весь год
        $startDate = $year . '-01-01';
        $endDate = $year . '-12-31';

        $query = $this->query()
            ->where('number_ticket', '=', $numberTicket)
            ->where('data_ticket', '>=', $startDate)
            ->where('data_ticket', '<=', $endDate);

        if ($excludeId !== null) {
            $query = $query->where('id', '!=', $excludeId);
        }

        $result = $query->first();

        return $result !== null;
    }

    /**
     * Получить общую сумму заправок для путевого листа
     */
    public function getTotalFuelFromLocal(int $ticketId): float
    {
        return $this->localStockModel->getTotalByTicketId($ticketId);
    }

    /**
     * Найти карточку с заправками
     */
    public function findWithFuels(int $id): ?array
    {
        $ticket = $this->find($id);
        if ($ticket) {
            $ticket['fuels'] = $this->localStockModel->getByTicketId($id);
            $ticket['total_fuel_from_local'] = $this->getTotalFuelFromLocal($id);
        }
        return $ticket;
    }

}