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

    /**
     * Получить предыдущую путевку техники — последнюю по дате путевки.
     *
     * Если передана дата ($beforeDate), учитываются только путевки,
     * дата которых строго раньше неё — то есть предыдущая путевка
     * относительно даты создаваемой путевки.
     * Это источник остатков на начало дня и спидометра на начало дня.
     *
     * $excludeId — не брать эту путёвку: нужно при редактировании,
     * чтобы не подставить остаток самой себе.
     */
    public function getPreviousTicket($id, ?string $beforeDate = null, $excludeId = null): ?array
    {
        $sql = "SELECT * FROM {$this->table}
                WHERE m_model_machine = :machine";
        $params = ['machine' => $id];

        if ($beforeDate !== null && $beforeDate !== '') {
            $sql .= " AND data_ticket < :before";
            $params['before'] = $beforeDate;
        }

        if ($excludeId !== null && $excludeId !== '') {
            $sql .= " AND id <> :exclude";
            $params['exclude'] = $excludeId;
        }

        $sql .= " ORDER BY data_ticket DESC, id DESC LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch();

        return $result ?: null;
    }

    /**
     * Остатки и спидометр из предыдущей путёвки — для предзаполнения формы.
     *
     * $beforeDate — дата новой путёвки: предыдущей считается последняя,
     * у которой data_ticket строго раньше (остаток на конец дня предыдущей
     * путёвки переходит в остаток на начало дня новой).
     *
     * $excludeId — не брать эту путёвку (нужно при редактировании,
     * чтобы не подставить остаток самой себе).
     *
     * @return array{opening_balance_fuel: float, opening_balance_butter: float, max_kilometres: int}|null
     */
    public function getOpeningData($id, string $beforeDate, $excludeId = null): ?array
    {
        $row = $this->getPreviousTicket($id, $beforeDate, $excludeId);

        if (!$row) {
            return null;
        }

        return [
            'opening_balance_fuel' => (float)$row['closing_balance_fuel'],
            'opening_balance_butter' => (float)$row['closing_balance_butter'],
            'max_kilometres' => (int)((float)$row['kilometres_speedometer_start'] + (float)$row['kilometres_speedometer']),
        ];
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