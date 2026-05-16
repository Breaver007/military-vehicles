<?php

namespace App\Controllers;

use App\Controllers\Controller;
use App\Models\OperationalCard\MilitaryFuel;
use App\Models\OperationalCard\MilitaryFuelOtherPlaces;
use App\Models\OperationalCard\MilitaryModelMachine;
use App\Models\OperationalCard\MilitaryNorm;
use App\Models\OperationalCard\MilitaryTicket;
use App\Models\OperationalCard\MilitaryTicketFromLocalOther;
use App\Models\OperationalCard\MilitaryTicketFromLocalStock;
use App\Models\OperationalCard\MilitaryTicketLocal;
use App\Models\OperationalCard\MilitaryTicketOther;
use App\Models\OperationalCard\MilitaryTicketPlaces;
use App\Models\OperationalCard\MilitaryUnit;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class MilitaryTicketController extends Controller
{
    private MilitaryTicket $ticketModel;
    private MilitaryModelMachine $machineModel;
    private MilitaryFuel $fuelModel;
    private MilitaryUnit $unitModel;
    private MilitaryNorm $normModel;
    private MilitaryTicketFromLocalStock $localStockModel;
    private MilitaryTicketFromLocalOther $OtherStockModel;
    private MilitaryFuelOtherPlaces $fuelOtherPlacesModel;
    private MilitaryTicketLocal $ticketLocalModel;
    private MilitaryTicketOther $ticketOtherModel;
    private MilitaryTicketPlaces $ticketPlacesModel;

    public function __construct()
    {
        parent::__construct();
        $this->ticketModel = new MilitaryTicket();
        $this->machineModel = new MilitaryModelMachine();
        $this->fuelModel = new MilitaryFuel();
        $this->unitModel = new MilitaryUnit();
        $this->normModel = new MilitaryNorm();
        $this->localStockModel = new MilitaryTicketFromLocalStock();
        $this->OtherStockModel = new MilitaryTicketFromLocalOther();
        $this->fuelOtherPlacesModel = new MilitaryFuelOtherPlaces();
        $this->ticketLocalModel = new MilitaryTicketLocal();
        $this->ticketOtherModel = new MilitaryTicketOther();
        $this->ticketPlacesModel = new MilitaryTicketPlaces();
    }

    /**
     * Список всех карточек
     */
    public function index(): void
    {
        $tickets = $this->ticketModel->allWithRelations();

        $this->view('military_ticket/index', [
            'title' => 'Эксплуатационные карточки',
            'MilitaryModelMachine' => $this->machineModel->where('is_active', '=', 1),
            'MilitaryUnit' => $this->unitModel->where('is_active', '=', 1),
        ]);
    }

    /**
     * Форма создания новой карточки
     */
    public function create(int $id, int $month, int $year): void
    {
        $formattedMonth = sprintf("%02d", $month);
        $MaxKilometres = $this->ticketModel->getMaxKilometres($id);
        $OpeningBalanceFuel = $this->ticketModel->getOpeningBalanceFuel($id);

        // Генерируем временный ID для сессии
        $tempId = uniqid('temp_', true);
        $_SESSION['temp_ticket_id'] = $tempId;
        $_SESSION['temp_fuels'][$tempId] = [];
        $_SESSION['temp_fuels_other'][$tempId] = [];
        $_SESSION['temp_fuels_places'][$tempId] = [];
        $_SESSION['temp_ticket_data'] = [
            'machine_id' => $id,
            'month' => $month,
            'year' => $year
        ];

        $this->view('military_ticket/create', [
            'title' => 'Создать эксплуатационную карточку',
            'temp_id' => $tempId,
            'idMachines' => $id,
            'month' => $formattedMonth,
            'year' => $year,
            'maxKilometres' => $MaxKilometres,
            'maxOpeningBalanceFuel' => $OpeningBalanceFuel,
            'MilitaryNorm' => $this->normModel->where('is_active', '=', 1),
            'MilitaryLocalStock' => $this->localStockModel->where('is_active', '=', 1),
            'MilitaryFuelOtherPlaces' => $this->fuelOtherPlacesModel->where('is_active', '=', 1),
            'MilitaryOtherStock' => $this->OtherStockModel->where('is_active', '=', 1),
        ]);
    }

       /**
     * Сохранение новой карточки
     */
    public function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/military-ticket');
        }
        $month = $_POST['month'];
        $year = $_POST['year'];
        $modelMachine = $_POST['m_model_machine'];
        $tempId = $_POST['temp_id'] ?? null;
//        dd($_POST);
        $data = [
            'm_model_machine' => $_POST['m_model_machine'] ?: 0,
            'kilometres_speedometer_start' => $_POST['kilometres_speedometer_start'] ?: 0,
            'kilometres_speedometer_end' => $_POST['kilometres_speedometer_end'] ?: 0,
            'day_count' => $_POST['day_count'] ?: 0,
            'm_norm' => $_POST['m_norm'] ?: 0,
            'kilometres_city' => $_POST['kilometres_city'] ?: 0,
            'kilometres_trail' => $_POST['kilometres_trail'] ?: 0,
            'kilometres_ground' => $_POST['kilometres_ground'] ?: 0,
            'kilometres_linear' => $_POST['kilometres_linear'] ?: 0,
            'kilometres_ticket' => $_POST['kilometres_ticket'] ?: 0,
            'ticket_write_off' => $_POST['ticket_write_off'] ?: 0,
            'calc_normal_city' => $_POST['calc_normal_city'] ?: 0,
            'calc_normal_trail' => $_POST['calc_normal_trail'] ?: 0,
            'calc_normal_ground' => $_POST['calc_normal_ground'] ?: 0,
            'calc_normal_linear' => $_POST['calc_normal_linear'] ?: 0,
            'calc_normal_cargo' => $_POST['calc_normal_cargo'] ?: 0,
            'calc_normal_pump' => $_POST['calc_normal_pump'] ?: 0,
            'cargo_1' => $_POST['cargo_1'] ?: 0,
            'weight_1' => $_POST['weight_1'] ?: 0,
            'cargo_2' => $_POST['cargo_2'] ?: 0,
            'weight_2' => $_POST['weight_2'] ?: 0,
            'cargo_3' => $_POST['cargo_3'] ?: 0,
            'weight_3' => $_POST['weight_3'] ?: 0,
            'cargo_4' => $_POST['cargo_4'] ?: 0,
            'weight_4' => $_POST['weight_4'] ?: 0,
            'cargo_5' => $_POST['cargo_5'] ?: 0,
            'weight_5' => $_POST['weight_5'] ?: 0,
            'data_ticket' => $_POST['data_ticket'] ?? '',
            'number_ticket' => $_POST['number_ticket'] ?: 0,
            'cargo' => $_POST['cargo'] ?: 0,
            'kilometres_speedometer' => $_POST['kilometres_speedometer'] ?: 0,
            'pump' => $_POST['pump'] ?: 0,
            'completed_work' => $_POST['completed_work'] ?: 0.00,
            'opening_balance_fuel' => $_POST['opening_balance_fuel'] ?: 0,
            'opening_balance_butter' => $_POST['opening_balance_butter'] ?: 0,
            'taken_fuel' => 0, // Временно 0, потом обновим
            'taken_butter' => $_POST['taken_butter'] ?: 0,
            'spent_fuel' => $_POST['spent_fuel'] ?: 0,
            'spent_butter' => $_POST['spent_butter'] ?: 0,
            'normal_fuel' => $_POST['normal_fuel'] ?: 0,
            'normal_butter' => $_POST['normal_butter'] ?: 0,
            'closing_balance_fuel' => $_POST['closing_balance_fuel'] ?: 0,
            'closing_balance_butter' => $_POST['closing_balance_butter'] ?: 0,
            'saving_fuel' => $_POST['saving_fuel'] ?: 0,
            'saving_butter' => $_POST['saving_butter'] ?: 0,
            'excessive_fuel' => $_POST['excessive_fuel'] ?: 0,
            'excessive_butter' => $_POST['excessive_butter'] ?: 0,
//            'operation_pump' => $_POST['operation_pump'] ?: 0,
            'taken_load_f' => $_POST['taken_load_f'] ?: 0,
            'taken_load_b' => $_POST['taken_load_b'] ?: 0,
            'taken_load_other_f' => $_POST['taken_load_other_f'] ?: 0,
            'taken_load_other_b' => $_POST['taken_load_other_b'] ?: 0,
            'taken_transferred_f' => $_POST['taken_transferred_f'] ?: 0,
            'taken_transferred_b' => $_POST['taken_transferred_b'] ?: 0,
            'taken_other_f' => $_POST['taken_other_f'] ?: 0,
            'taken_other_b' => $_POST['taken_other_b'] ?: 0,
        ];

        // Валидация
        $errors = [];
        if (empty($data['m_model_machine'])) {
            $errors['m_model_machine'] = 'Выберите технику';
        }
        if (empty($data['m_norm'])) {
            $errors['m_norm'] = 'Выберети норму';
        }
        if (empty($data['data_ticket'])) {
            $errors['data_ticket'] = 'Укажите дату';
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $_POST;
            $this->redirect("/military-ticket/{$modelMachine}/{$month}/{$year}");
        }

        // Получаем сумму из временных заправок (local + other + places)
        $totalLocal = $tempId && isset($_SESSION['temp_fuels'][$tempId]) ? $this->getTempFuelTotal($tempId) : 0;
        $totalOther = $tempId && isset($_SESSION['temp_fuels_other'][$tempId]) ? $this->getTempFuelOtherTotal($tempId) : 0;
        $totalPlaces = $tempId && isset($_SESSION['temp_fuels_places'][$tempId]) ? $this->getTempFuelPlacesTotal($tempId) : 0;
        $data['taken_fuel'] = $totalLocal + $totalOther + $totalPlaces;

        $ticket = $this->ticketModel->create($data);

        if ($ticket) {
            // Сохраняем временные заправки в реальную таблицу (local)
            if ($tempId && isset($_SESSION['temp_fuels'][$tempId]) && !empty($_SESSION['temp_fuels'][$tempId])) {
                foreach ($_SESSION['temp_fuels'][$tempId] as $fuel) {
                    $this->ticketLocalModel->create([
                        'date' => $fuel['date'],
                        'mt_local_id' => $fuel['mt_local_id'],
                        'value' => $fuel['value'],
                        'ticket_id' => $ticket['id']
                    ]);
                }
                // Очищаем временные данные
                unset($_SESSION['temp_fuels'][$tempId]);
            }

            // Сохраняем временные заправки в реальную таблицу (other)
            if ($tempId && isset($_SESSION['temp_fuels_other'][$tempId]) && !empty($_SESSION['temp_fuels_other'][$tempId])) {
                foreach ($_SESSION['temp_fuels_other'][$tempId] as $fuel) {
                    $this->ticketOtherModel->create([
                        'date' => $fuel['date'],
                        'mt_other_id' => $fuel['mt_other_id'],
                        'value' => $fuel['value'],
                        'ticket_id' => $ticket['id']
                    ]);
                }
                // Очищаем временные данные
                unset($_SESSION['temp_fuels_other'][$tempId]);
            }

            // Сохраняем временные заправки в реальную таблицу (places)
            if ($tempId && isset($_SESSION['temp_fuels_places'][$tempId]) && !empty($_SESSION['temp_fuels_places'][$tempId])) {
                foreach ($_SESSION['temp_fuels_places'][$tempId] as $fuel) {
                    $this->ticketPlacesModel->create([
                        'date' => $fuel['date'],
                        'mt_places_id' => $fuel['mt_places_id'],
                        'value' => $fuel['value'],
                        'ticket_id' => $ticket['id']
                    ]);
                }
                // Очищаем временные данные
                unset($_SESSION['temp_fuels_places'][$tempId]);
            }
            unset($_SESSION['temp_ticket_id']);
            unset($_SESSION['temp_ticket_data']);

            $_SESSION['success'] = 'Эксплуатационная карточка успешно создана';
            $this->redirect("/military-ticket/{$modelMachine}/{$month}/{$year}");
        } else {
            $_SESSION['error'] = 'Ошибка при создании карточки';
            $this->redirect("/military-ticket/create/{$modelMachine}/{$month}/{$year}");
        }
    }

    /**
     * Просмотр карточки
     */
    public function show(int $id): void
    {
        $data = [
            'title' => 'Эксплуатационная карточка',
            'id' => $id,
            'MilitaryNorm' => $this->normModel->where('is_active', '=', 1),
            'MilitaryModelMachine' => $this->machineModel->where('is_active', '=', 1),
            'MilitaryFuel' => $this->fuelModel->where('is_active', '=', 1),
            'MilitaryUnit' => $this->unitModel->where('is_active', '=', 1),
        ];

        $this->view('military_ticket/index', $data);
    }
    public function printForm(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/military-ticket');
            return;
        }

        $idModelMachine = (int)$_POST['idModelMachine'];
        $startDate = $_POST['start_date'] ?? '';
        $endDate = $_POST['end_date'] ?? '';

        // Валидация дат
        if (empty($startDate) || empty($endDate)) {
            $_SESSION['error'] = 'Укажите период печати';
            $this->redirect("/military-ticket/print-select/{$idModelMachine}");
            return;
        }

        // Получаем данные за период
        $data = $this->getTicketDataByPeriod($idModelMachine, $startDate, $endDate);

        if (empty($data['getModelMachineTicket'])) {
            $_SESSION['error'] = 'За указанный период нет данных для печати';
            $this->redirect("/military-ticket/print-select/{$idModelMachine}");
            return;
        }
        // Подключаем шаблон для печати
        include 'views/military_ticket/print.php';
    }
    /**
     * Страница выбора периода для печати
     */
    public function printSelect(int $idModelMachine): void
    {
        $this->view('military_ticket/print_select', [
            'title' => 'Выбор периода для печати',
            'idModelMachine' => $idModelMachine,
            'machineName' => $this->machineModel->find($idModelMachine)['name'] ?? 'Техника',
            'currentDate' => date('Y-m-d')
        ]);
    }
    /**
     * Получить данные за период
     */
    private function getTicketDataByPeriod($idModelMachine, $startDate, $endDate)
    {
        return [
            'id' => $idModelMachine,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'MilitaryNorm' => $this->normModel->where('is_active', '=', 1),
            'MilitaryModelMachine' => $this->machineModel->where('is_active', '=', 1),
            'MilitaryFuel' => $this->fuelModel->where('is_active', '=', 1),
            'MilitaryUnit' => $this->unitModel->where('is_active', '=', 1),
            'getModelMachineTicket' => $this->ticketModel->query()
                ->where('m_model_machine', '=', $idModelMachine)
                ->where('data_ticket', '>=', $startDate)
                ->where('data_ticket', '<=', $endDate)
                ->orderBy('data_ticket')
                ->get()
        ];
    }
    public function showMonth(int $id, int $month, int $year): void
    {
        $formattedMonth = sprintf("%02d", $month);
        $data = [
            'title' => 'Эксплуатационная карточка',
            'id' => $id,
            'month' => $month,
            'year' => $year,
            'getModelMachineTicket' => $this->ticketModel->query()
                ->where('m_model_machine', '=', $id)
                ->whereLeft('data_ticket', 7, "$year-$formattedMonth")
                ->orderBy('data_ticket')
                ->get()
            ,
            'MilitaryNorm' => $this->normModel->where('is_active', '=', 1),
            'MilitaryModelMachine' => $this->machineModel->where('is_active', '=', 1),
            'MilitaryFuel' => $this->fuelModel->where('is_active', '=', 1),
            'MilitaryUnit' => $this->unitModel->where('is_active', '=', 1),
        ];
        $this->view('military_ticket/index', $data);
    }

    /**
     * Форма редактирования
     */
    public function edit(int $idModelMachine, int $month, int $year, int $id): void
    {
        $ticket = $this->ticketModel->find($id);

        $machineData = [
            'idMachines' => $idModelMachine,
            'year' => $year,
            'month' => $month
        ];

        if (!$ticket) {
            $this->redirect("/military-ticket/{$idModelMachine}/{$month}/{$year}");
        }

        // Получаем существующие заправки всех типов
        $ticketLocalFuels = $this->ticketLocalModel->query()
            ->where('ticket_id', '=', $id)
            ->get();
        $ticketOtherFuels = $this->ticketOtherModel->query()
            ->where('ticket_id', '=', $id)
            ->get();
        $ticketPlacesFuels = $this->ticketPlacesModel->query()
            ->where('ticket_id', '=', $id)
            ->get();

        // Генерируем временный ID для сессии (для возможности добавления новых заправок при редактировании)
        $tempId = 'edit_' . $id . '_' . uniqid();
        $_SESSION['temp_ticket_id'] = $tempId;
        $_SESSION['temp_fuels'][$tempId] = array_map(fn($f) => [
            'id' => 'existing_' . $f['id'],
            'date' => $f['date'],
            'mt_local_id' => $f['mt_local_id'],
            'value' => $f['value']
        ], $ticketLocalFuels);
        $_SESSION['temp_fuels_other'][$tempId] = array_map(fn($f) => [
            'id' => 'existing_' . $f['id'],
            'date' => $f['date'],
            'mt_other_id' => $f['mt_other_id'],
            'value' => $f['value']
        ], $ticketOtherFuels);
        $_SESSION['temp_fuels_places'][$tempId] = array_map(fn($f) => [
            'id' => 'existing_' . $f['id'],
            'date' => $f['date'],
            'mt_places_id' => $f['mt_places_id'],
            'value' => $f['value']
        ], $ticketPlacesFuels);

        $this->view('military_ticket/edit', [
            'title' => "Редактировать карточку №{$ticket['id']}",
            'data' => $machineData,
            'ticket' => $ticket,
            'temp_id' => $tempId,
            'MilitaryNorm' => $this->normModel->where('is_active', '=', 1),
            'MilitaryLocalStock' => $this->localStockModel->where('is_active', '=', 1),
            'MilitaryFuelOtherPlaces' => $this->fuelOtherPlacesModel->where('is_active', '=', 1),
            'MilitaryOtherStock' => $this->OtherStockModel->where('is_active', '=', 1),
            'ticketLocalFuels' => $ticketLocalFuels,
            'ticketOtherFuels' => $ticketOtherFuels,
            'ticketPlacesFuels' => $ticketPlacesFuels,
        ]);
    }

    /**
     * Обновление карточки
     */
    public function update(int $idModelMachine, int $month, int $year, int $id): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect("/military-ticket/{$idModelMachine}/{$month}/{$year}");
        }

        $ticket = $this->ticketModel->find($id);
        if (!$ticket) {
            $this->redirect("/military-ticket/{$idModelMachine}/{$month}/{$year}");
        }

        // Валидация обязательных полей
        $errors = [];

        if (empty($_POST['number_ticket'])) {
            $errors['number_ticket'] = 'Укажите номер путевого листа';
        }

        if (!empty($_POST['number_ticket']) && !empty($_POST['data_ticket'])) {
            $numberTicket = trim($_POST['number_ticket']);
            $dataTicket = $_POST['data_ticket'];

             if ($this->ticketModel->isNumberTicketExistsInYear($numberTicket, $dataTicket, $id)) {
                 $errors['number_ticket'] = 'Номер путевого листа уже использовался в течение последнего года.';
             }
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $_POST;
            $this->redirect("/military-ticket/edit/{$idModelMachine}/{$month}/{$year}/{$id}");
        }

        $dbData = [
            'm_model_machine' => (int)($_POST['m_model_machine']),
            'kilometres_speedometer_start' => (float)($_POST['kilometres_speedometer_start'] ?? $ticket['kilometres_speedometer_start'] ?? 0),
            'day_count' => (int)($_POST['day_count'] ?? $ticket['day_count'] ?? 1),
            'm_norm' => (int)($_POST['m_norm'] ?? $ticket['m_norm'] ?? 0),
            'data_ticket' => $_POST['data_ticket'] ?? $ticket['data_ticket'] ?? date('Y-m-d'),
            'number_ticket' => $_POST['number_ticket'] ?? $ticket['number_ticket'] ?? '',
            'kilometres_city' => (float)($_POST['kilometres_city'] ?? $ticket['kilometres_city'] ?? 0),
            'kilometres_trail' => (float)($_POST['kilometres_trail'] ?? $ticket['kilometres_trail'] ?? 0),
            'kilometres_ground' => (float)($_POST['kilometres_ground'] ?? $ticket['kilometres_ground'] ?? 0),
            'kilometres_linear' => (float)($_POST['kilometres_linear'] ?? $ticket['kilometres_linear'] ?? 0),
            'kilometres_ticket' => (float)($_POST['kilometres_ticket'] ?? $ticket['kilometres_ticket'] ?? 0),
            'cargo' => (float)($_POST['cargo'] ?? $ticket['cargo'] ?? 0),
            'cargo_no' => (float)($_POST['cargo_no'] ?? $ticket['cargo_no'] ?? 0),
            'kilometres_speedometer' => (float)($_POST['kilometres_speedometer'] ?? $ticket['kilometres_speedometer'] ?? 0),
            'pump' => (float)($_POST['pump'] ?? $ticket['pump'] ?? 0),
            'completed_work' => (float)($_POST['completed_work'] ?? $ticket['completed_work'] ?? 0),
            'completed_work_km' => (float)($_POST['completed_work_km'] ?? $ticket['completed_work_km'] ?? 0),
            'opening_balance_fuel' => (float)($_POST['opening_balance_fuel'] ?? $ticket['opening_balance_fuel'] ?? 0),
            'opening_balance_butter' => (float)($_POST['opening_balance_butter'] ?? $ticket['opening_balance_butter'] ?? 0),
            'taken_fuel' => 0, // будет обновлено после обработки заправок
            'taken_butter' => (float)($_POST['taken_butter'] ?? $ticket['taken_butter'] ?? 0),
            'spent_fuel' => (float)($_POST['spent_fuel'] ?? $ticket['spent_fuel'] ?? 0),
            'spent_butter' => (float)($_POST['spent_butter'] ?? $ticket['spent_butter'] ?? 0),
            'normal_fuel' => (float)($_POST['normal_fuel'] ?? $ticket['normal_fuel'] ?? 0),
            'normal_butter' => (float)($_POST['normal_butter'] ?? $ticket['normal_butter'] ?? 0),
            'closing_balance_fuel' => (float)($_POST['closing_balance_fuel'] ?? $ticket['closing_balance_fuel'] ?? 0),
            'closing_balance_butter' => (float)($_POST['closing_balance_butter'] ?? $ticket['closing_balance_butter'] ?? 0),
            'saving_fuel' => (float)($_POST['saving_fuel'] ?? $ticket['saving_fuel'] ?? 0),
            'saving_butter' => (float)($_POST['saving_butter'] ?? $ticket['saving_butter'] ?? 0),
            'excessive_fuel' => (float)($_POST['excessive_fuel'] ?? $ticket['excessive_fuel'] ?? 0),
            'excessive_butter' => (float)($_POST['excessive_butter'] ?? $ticket['excessive_butter'] ?? 0),
            'cargo_no' => (float)($_POST['cargo_no'] ?? $ticket['cargo_no'] ?? 0),
            'taken_load_f' => (float)($_POST['taken_load_f'] ?? $ticket['taken_load_f'] ?? 0),
            'taken_load_b' => (float)($_POST['taken_load_b'] ?? $ticket['taken_load_b'] ?? 0),
            'taken_load_other_f' => (float)($_POST['taken_load_other_f'] ?? $ticket['taken_load_other_f'] ?? 0),
            'taken_load_other_b' => (float)($_POST['taken_load_other_b'] ?? $ticket['taken_load_other_b'] ?? 0),
            'taken_transferred_f' => (float)($_POST['taken_transferred_f'] ?? $ticket['taken_transferred_f'] ?? 0),
            'taken_transferred_b' => (float)($_POST['taken_transferred_b'] ?? $ticket['taken_transferred_b'] ?? 0),
            'taken_other_f' => (float)($_POST['taken_other_f'] ?? $ticket['taken_other_f'] ?? 0),
            'taken_other_b' => (float)($_POST['taken_other_b'] ?? $ticket['taken_other_b'] ?? 0),
        ];

        // Выполняем обновление
        $result = $this->ticketModel->update($id, $dbData);

        if ($result) {
            // Обновляем заправки
            $tempId = $_POST['temp_id'] ?? null;

            if ($tempId) {
                // Обновляем taken_fuel = сумма всех заправок +手动ные поля
                $totalLocal = $this->getTempFuelTotal($tempId);
                $totalOther = $this->getTempFuelOtherTotal($tempId);
                $totalPlaces = $this->getTempFuelPlacesTotal($tempId);
                $takenLoadF = (float)($_POST['taken_load_f'] ?? 0);
                $takenLoadOtherF = (float)($_POST['taken_load_other_f'] ?? 0);
                $takenTransferredF = (float)($_POST['taken_transferred_f'] ?? 0);
                $takenOtherF = (float)($_POST['taken_other_f'] ?? 0);
                $totalFuel = $totalLocal + $totalOther + $totalPlaces + $takenLoadF + $takenLoadOtherF + $takenTransferredF + $takenOtherF;
                $this->ticketModel->update($id, ['taken_fuel' => $totalFuel]);

                // Удаляем старые заправки
                $this->ticketLocalModel->query()->where('ticket_id', '=', $id)->delete();
                $this->ticketOtherModel->query()->where('ticket_id', '=', $id)->delete();
                $this->ticketPlacesModel->query()->where('ticket_id', '=', $id)->delete();

                // Добавляем новые заправки из сессии
                if (!empty($_SESSION['temp_fuels'][$tempId])) {
                    foreach ($_SESSION['temp_fuels'][$tempId] as $fuel) {
                        if (strpos($fuel['id'], 'existing_') === 0) continue;
                        $this->ticketLocalModel->create([
                            'date' => $fuel['date'],
                            'mt_local_id' => $fuel['mt_local_id'],
                            'value' => $fuel['value'],
                            'ticket_id' => $id
                        ]);
                    }
                }

                if (!empty($_SESSION['temp_fuels_other'][$tempId])) {
                    foreach ($_SESSION['temp_fuels_other'][$tempId] as $fuel) {
                        if (strpos($fuel['id'], 'existing_') === 0) continue;
                        $this->ticketOtherModel->create([
                            'date' => $fuel['date'],
                            'mt_other_id' => $fuel['mt_other_id'],
                            'value' => $fuel['value'],
                            'ticket_id' => $id
                        ]);
                    }
                }

                if (!empty($_SESSION['temp_fuels_places'][$tempId])) {
                    foreach ($_SESSION['temp_fuels_places'][$tempId] as $fuel) {
                        if (strpos($fuel['id'], 'existing_') === 0) continue;
                        $this->ticketPlacesModel->create([
                            'date' => $fuel['date'],
                            'mt_places_id' => $fuel['mt_places_id'],
                            'value' => $fuel['value'],
                            'ticket_id' => $id
                        ]);
                    }
                }

                unset($_SESSION['temp_fuels'][$tempId]);
                unset($_SESSION['temp_fuels_other'][$tempId]);
                unset($_SESSION['temp_fuels_places'][$tempId]);
            }

            $_SESSION['success'] = 'Эксплуатационная карточка успешно обновлена';
        } else {
            $_SESSION['error'] = 'Ошибка при обновлении карточки';
        }

        // Перенаправляем обратно на страницу с карточками
        $this->redirect("/military-ticket/{$dbData['m_model_machine']}/{$_POST['month']}/{$_POST['year']}");
    }

    /**
     * Удаление карточки
     */
    public function delete(int $idModelMachine, int $month, int $year, int $id): void
    {
        $ticket = $this->ticketModel->find($id);

        if ($ticket) {
            $this->ticketModel->delete($id);
            $_SESSION['success'] = 'Карточка удалена';
        } else {
            $_SESSION['error'] = 'Запись не найдена';
        }

        $this->redirect("/military-ticket/{$idModelMachine}/{$month}/{$year}");
    }


    public function tempAddFuel(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['error' => 'Method not allowed'], 405);
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data) {
            $this->json(['error' => 'Invalid data'], 400);
            return;
        }

        $tempId = $data['temp_id'] ?? null;
        if (!$tempId || !isset($_SESSION['temp_fuels'][$tempId])) {
            $this->json(['error' => 'Invalid temporary ID'], 400);
            return;
        }

        // Валидация
        if (empty($data['date']) || empty($data['mt_local_id']) || !isset($data['value'])) {
            $this->json(['error' => 'Missing required fields'], 400);
            return;
        }

        $fuelRecord = [
            'id' => uniqid('fuel_', true),
            'date' => $data['date'],
            'mt_local_id' => (int)$data['mt_local_id'],
            'value' => (float)$data['value']
        ];

        $_SESSION['temp_fuels'][$tempId][] = $fuelRecord;

        $this->json([
            'success' => true,
            'record' => $fuelRecord,
            'total' => $this->getTempFuelTotal($tempId)
        ]);
    }

    /**
     * Получение всех временных заправок
     */
    public function tempGetFuel(string $tempId): void
    {
        if (!isset($_SESSION['temp_fuels'][$tempId])) {
            $this->json(['total' => 0, 'records' => []]);
            return;
        }

        $fuels = $_SESSION['temp_fuels'][$tempId];
        $total = $this->getTempFuelTotal($tempId);

        $this->json(['total' => $total, 'records' => $fuels]);
    }

    /**
     * Удаление временной заправки
     */
    public function tempRemoveFuel(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
            $this->json(['error' => 'Method not allowed'], 405);
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $tempId = $data['temp_id'] ?? null;
        $fuelId = $data['fuel_id'] ?? null;

        if (!$tempId || !$fuelId || !isset($_SESSION['temp_fuels'][$tempId])) {
            $this->json(['error' => 'Invalid data'], 400);
            return;
        }

        // Удаляем конкретную заправку
        foreach ($_SESSION['temp_fuels'][$tempId] as $key => $fuel) {
            if ($fuel['id'] === $fuelId) {
                unset($_SESSION['temp_fuels'][$tempId][$key]);
                break;
            }
        }

        // Переиндексируем массив
        $_SESSION['temp_fuels'][$tempId] = array_values($_SESSION['temp_fuels'][$tempId]);

        $this->json([
            'success' => true,
            'total' => $this->getTempFuelTotal($tempId),
            'records' => $_SESSION['temp_fuels'][$tempId]
        ]);
    }

    /**
     * Вспомогательный метод для подсчета суммы временных заправок
     */
    private function getTempFuelTotal(string $tempId): float
    {
        $fuels = $_SESSION['temp_fuels'][$tempId] ?? [];
        return array_sum(array_column($fuels, 'value'));
    }

    /**
     * Вспомогательный метод для подсчета суммы временных заправок other
     */
    private function getTempFuelOtherTotal(string $tempId): float
    {
        $fuels = $_SESSION['temp_fuels_other'][$tempId] ?? [];
        return array_sum(array_column($fuels, 'value'));
    }

    /**
     * Добавление временной заправки other
     */
    public function tempAddFuelOther(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['error' => 'Method not allowed'], 405);
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data) {
            $this->json(['error' => 'Invalid data'], 400);
            return;
        }

        $tempId = $data['temp_id'] ?? null;
        if (!$tempId || !isset($_SESSION['temp_fuels_other'][$tempId])) {
            $this->json(['error' => 'Invalid temporary ID'], 400);
            return;
        }

        if (empty($data['date']) || empty($data['mt_other_id']) || !isset($data['value'])) {
            $this->json(['error' => 'Missing required fields'], 400);
            return;
        }

        $fuelRecord = [
            'id' => uniqid('fuel_other_', true),
            'date' => $data['date'],
            'mt_other_id' => (int)$data['mt_other_id'],
            'value' => (float)$data['value']
        ];

        $_SESSION['temp_fuels_other'][$tempId][] = $fuelRecord;

        $this->json([
            'success' => true,
            'record' => $fuelRecord,
            'total' => $this->getTempFuelOtherTotal($tempId)
        ]);
    }

    /**
     * Получение всех временных заправок other
     */
    public function tempGetFuelOther(string $tempId): void
    {
        if (!isset($_SESSION['temp_fuels_other'][$tempId])) {
            $this->json(['total' => 0, 'records' => []]);
            return;
        }

        $fuels = $_SESSION['temp_fuels_other'][$tempId];
        $total = $this->getTempFuelOtherTotal($tempId);

        $this->json(['total' => $total, 'records' => $fuels]);
    }

    /**
     * Удаление временной заправки other
     */
    public function tempRemoveFuelOther(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
            $this->json(['error' => 'Method not allowed'], 405);
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $tempId = $data['temp_id'] ?? null;
        $fuelId = $data['fuel_id'] ?? null;

        if (!$tempId || !$fuelId || !isset($_SESSION['temp_fuels_other'][$tempId])) {
            $this->json(['error' => 'Invalid data'], 400);
            return;
        }

        foreach ($_SESSION['temp_fuels_other'][$tempId] as $key => $fuel) {
            if ($fuel['id'] === $fuelId) {
                unset($_SESSION['temp_fuels_other'][$tempId][$key]);
                break;
            }
        }

        $_SESSION['temp_fuels_other'][$tempId] = array_values($_SESSION['temp_fuels_other'][$tempId]);

        $this->json([
            'success' => true,
            'total' => $this->getTempFuelOtherTotal($tempId),
            'records' => $_SESSION['temp_fuels_other'][$tempId]
        ]);
    }

    /**
     * Вспомогательный метод для подсчета суммы временных заправок places
     */
    private function getTempFuelPlacesTotal(string $tempId): float
    {
        $fuels = $_SESSION['temp_fuels_places'][$tempId] ?? [];
        return array_sum(array_column($fuels, 'value'));
    }

    /**
     * Добавление временной заправки places
     */
    public function tempAddFuelPlaces(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['error' => 'Method not allowed'], 405);
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data) {
            $this->json(['error' => 'Invalid data'], 400);
            return;
        }

        $tempId = $data['temp_id'] ?? null;
        if (!$tempId || !isset($_SESSION['temp_fuels_places'][$tempId])) {
            $this->json(['error' => 'Invalid temporary ID'], 400);
            return;
        }

        if (empty($data['date']) || empty($data['mt_places_id']) || !isset($data['value'])) {
            $this->json(['error' => 'Missing required fields'], 400);
            return;
        }

        $fuelRecord = [
            'id' => uniqid('fuel_places_', true),
            'date' => $data['date'],
            'mt_places_id' => (int)$data['mt_places_id'],
            'value' => (float)$data['value']
        ];

        $_SESSION['temp_fuels_places'][$tempId][] = $fuelRecord;

        $this->json([
            'success' => true,
            'record' => $fuelRecord,
            'total' => $this->getTempFuelPlacesTotal($tempId)
        ]);
    }

    /**
     * Получение всех временных заправок places
     */
    public function tempGetFuelPlaces(string $tempId): void
    {
        if (!isset($_SESSION['temp_fuels_places'][$tempId])) {
            $this->json(['total' => 0, 'records' => []]);
            return;
        }

        $fuels = $_SESSION['temp_fuels_places'][$tempId];
        $total = $this->getTempFuelPlacesTotal($tempId);

        $this->json(['total' => $total, 'records' => $fuels]);
    }

    /**
     * Удаление временной заправки places
     */
    public function tempRemoveFuelPlaces(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
            $this->json(['error' => 'Method not allowed'], 405);
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $tempId = $data['temp_id'] ?? null;
        $fuelId = $data['fuel_id'] ?? null;

        if (!$tempId || !$fuelId || !isset($_SESSION['temp_fuels_places'][$tempId])) {
            $this->json(['error' => 'Invalid data'], 400);
            return;
        }

        foreach ($_SESSION['temp_fuels_places'][$tempId] as $key => $fuel) {
            if ($fuel['id'] === $fuelId) {
                unset($_SESSION['temp_fuels_places'][$tempId][$key]);
                break;
            }
        }

        $_SESSION['temp_fuels_places'][$tempId] = array_values($_SESSION['temp_fuels_places'][$tempId]);

        $this->json([
            'success' => true,
            'total' => $this->getTempFuelPlacesTotal($tempId),
            'records' => $_SESSION['temp_fuels_places'][$tempId]
        ]);
    }


    public function exportToExcel(int $idModelMachine, int $month, int $year)
    {
        // Получаем данные как в index.php
        $data = $this->getTicketData($idModelMachine, $month, $year);

        // Создаем новый Spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle($this->getMonthName($month));

        // Устанавливаем стили
        $this->setExcelStyles($sheet);

        // Заполняем шапку документа
        $this->fillHeader($sheet, $data, $month, $year);

        // Заполняем заголовки таблицы
        $this->fillTableHeaders($sheet);

        // Заполняем данные
        $rowIndex = $this->fillTableData($sheet, $data);

        // Заполняем итоги
        $this->fillTableTotals($sheet, $data, $rowIndex);

        // Заполняем эксплуатационные показатели
        $this->fillOperationalIndicators($sheet, $data, $rowIndex);

        // Заполняем подписи
        $this->fillSignatures($sheet, $rowIndex);

        // Устанавливаем ширину колонок
        $this->setColumnWidths($sheet);

        // Сохраняем файл
        $fileName = "expluatacionnaya_kartochka_{$this->getMonthName($month)}_{$year}.xlsx";

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    private function getMonthName($month)
    {
        $months = [
            1 => 'Январь', 2 => 'Февраль', 3 => 'Март',
            4 => 'Апрель', 5 => 'Май', 6 => 'Июнь',
            7 => 'Июль', 8 => 'Август', 9 => 'Сентябрь',
            10 => 'Октябрь', 11 => 'Ноябрь', 12 => 'Декабрь'
        ];
        return $months[$month] ?? 'Месяц';
    }

    private function setExcelStyles($sheet)
    {
        // Стиль для заголовков
        $headerStyle = [
            'font' => ['bold' => true, 'size' => 10],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true
            ],
            'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN]
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'F2F2F2']
            ]
        ];

        $sheet->getParent()->getDefaultStyle()->getFont()->setName('Arial')->setSize(9);
    }

    private function fillHeader($sheet, $data, $month, $year)
    {
        $months = $this->getMonthName($month);

        // Заголовок
        $sheet->setCellValue('A1', 'Эксплуатационная карточка №' . ($data['id'] ?? '____'));
        $sheet->mergeCells('A1:X1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);

        // Месяц и год
        $sheet->setCellValue('A2', strtoupper($months) . ' ' . $year);
        $sheet->mergeCells('A2:X2');
        $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(12);

        // Подразделение
        $sheet->setCellValue('A3', $data['MilitaryUnit'][0]['name'] ?? '');
        $sheet->mergeCells('A3:N3');

        // Наименование горючего
        $fuelName = isset($data['MilitaryFuel'][$data['MilitaryModelMachine'][$data['id']]['m_fuel']])
            ? $data['MilitaryFuel'][$data['MilitaryModelMachine'][$data['id']]['m_fuel']]['name']
            : "Не указан";
        $sheet->setCellValue('O3', $fuelName);
        $sheet->mergeCells('O3:X3');

        // Тип машины и рег.знак
        $sheet->setCellValue('A4', $data['MilitaryModelMachine'][$data['id']]['name'] ?? '');
        $sheet->mergeCells('A4:K4');

        $sheet->setCellValue('L4', $data['MilitaryModelMachine'][$data['id']]['registr_plate'] ?? '');
        $sheet->mergeCells('L4:N4');

        // Показания спидометра
        $startKm = isset($data['getModelMachineTicket'][0]) ? $data['getModelMachineTicket'][0]['kilometres_speedometer_start'] : 0;
        $totalKm = array_sum(array_column($data['getModelMachineTicket'], 'kilometres_speedometer'));
        $endKm = $startKm + $totalKm;

        $sheet->setCellValue('O4', $startKm);
        $sheet->setCellValue('P4', $totalKm);
        $sheet->setCellValue('Q4', $endKm);
        $sheet->mergeCells('O4:O5');
        $sheet->mergeCells('P4:P5');
        $sheet->mergeCells('Q4:Q5');

        // Подписи к показаниям
        $sheet->setCellValue('O5', 'спидометр на начало месяца');
        $sheet->setCellValue('P5', 'пробег за месяц');
        $sheet->setCellValue('Q5', 'спидометр на конец месяца');
    }

    private function fillTableHeaders($sheet)
    {
        // Основные заголовки
        $headers = [
            'A6' => 'Путевой лист',
            'C6' => 'Пройдено километров (отработано моточасов)',
            'H6' => 'Выполненная работа',
            'J6' => 'Материальные средства, л',
            'X6' => 'Примечание'
        ];

        foreach ($headers as $cell => $text) {
            $sheet->setCellValue($cell, $text);
        }

        $sheet->mergeCells('A6:B6');
        $sheet->mergeCells('C6:G6');
        $sheet->mergeCells('H6:I6');
        $sheet->mergeCells('J6:W6');

        // Второй уровень заголовков
        $sheet->setCellValue('A7', 'дата (период)');
        $sheet->setCellValue('B7', 'номер');
        $sheet->setCellValue('C7', 'с грузом');
        $sheet->setCellValue('D7', 'без груза');
        $sheet->setCellValue('E7', 'всего');
        $sheet->setCellValue('F7', 'моточасы');
        $sheet->setCellValue('G7', 'пробег');
        $sheet->setCellValue('H7', 'тонн');
        $sheet->setCellValue('I7', 'тонна-километров');
        $sheet->setCellValue('J7', 'Остаток на начало периода');
        $sheet->setCellValue('L7', 'Получено');
        $sheet->setCellValue('N7', 'Израсходовано');
        $sheet->setCellValue('P7', 'Положено по норме');
        $sheet->setCellValue('R7', 'Остаток на конец периода');
        $sheet->setCellValue('T7', 'Экономия');
        $sheet->setCellValue('V7', 'Перерасход');

        $sheet->mergeCells('F7:G7');
        $sheet->mergeCells('J7:K7');
        $sheet->mergeCells('L7:M7');
        $sheet->mergeCells('N7:O7');
        $sheet->mergeCells('P7:Q7');
        $sheet->mergeCells('R7:S7');
        $sheet->mergeCells('T7:U7');
        $sheet->mergeCells('V7:W7');

        // Третий уровень заголовков (горючее/масло)
        $sheet->setCellValue('J8', 'горючего');
        $sheet->setCellValue('K8', 'масла');
        $sheet->setCellValue('L8', 'горючего');
        $sheet->setCellValue('M8', 'масла');
        $sheet->setCellValue('N8', 'горючего');
        $sheet->setCellValue('O8', 'масла');
        $sheet->setCellValue('P8', 'горючего');
        $sheet->setCellValue('Q8', 'масла');
        $sheet->setCellValue('R8', 'горючего');
        $sheet->setCellValue('S8', 'масла');
        $sheet->setCellValue('T8', 'горючего');
        $sheet->setCellValue('U8', 'масла');
        $sheet->setCellValue('V8', 'горючего');
        $sheet->setCellValue('W8', 'масла');

        // Номера колонок
        $colNumbers = range(1, 24);
        for ($i = 0; $i < 24; $i++) {
            $col = chr(65 + $i);
            $sheet->setCellValue($col . '9', $colNumbers[$i]);
        }
    }

    private function fillTableData($sheet, $data)
    {
        $rowIndex = 10; // Начинаем с 10 строки

        foreach ($data['getModelMachineTicket'] as $ticket) {
            $sheet->setCellValue('A' . $rowIndex, date('d.m.Y', strtotime($ticket['data_ticket'])));
            $sheet->setCellValue('B' . $rowIndex, $ticket['number_ticket']);
            $sheet->setCellValue('C' . $rowIndex, $ticket['cargo']);
            $sheet->setCellValue('D' . $rowIndex, $ticket['kilometres_speedometer'] - $ticket['cargo']);
            $sheet->setCellValue('E' . $rowIndex, $ticket['kilometres_speedometer']);
            $sheet->setCellValue('F' . $rowIndex, $ticket['pump']);
            $sheet->setCellValue('G' . $rowIndex, '');
            $sheet->setCellValue('H' . $rowIndex, $ticket['completed_work']);
            $sheet->setCellValue('I' . $rowIndex, round($ticket['completed_work'] * $ticket['cargo'], 0));
            $sheet->setCellValue('J' . $rowIndex, $ticket['opening_balance_fuel']);
            $sheet->setCellValue('K' . $rowIndex, $ticket['opening_balance_butter']);
            $sheet->setCellValue('L' . $rowIndex, $ticket['taken_fuel']);
            $sheet->setCellValue('M' . $rowIndex, $ticket['taken_butter']);
            $sheet->setCellValue('N' . $rowIndex, $ticket['spent_fuel']);
            $sheet->setCellValue('O' . $rowIndex, $ticket['spent_butter']);
            $sheet->setCellValue('P' . $rowIndex, $ticket['normal_fuel']);
            $sheet->setCellValue('Q' . $rowIndex, $ticket['normal_butter']);
            $sheet->setCellValue('R' . $rowIndex, $ticket['closing_balance_fuel']);
            $sheet->setCellValue('S' . $rowIndex, $ticket['closing_balance_butter']);
            $sheet->setCellValue('T' . $rowIndex, $ticket['saving_fuel']);
            $sheet->setCellValue('U' . $rowIndex, $ticket['saving_butter']);
            $sheet->setCellValue('V' . $rowIndex, $ticket['excessive_fuel']);
            $sheet->setCellValue('W' . $rowIndex, $ticket['excessive_butter']);
            $sheet->setCellValue('X' . $rowIndex, '');

            $rowIndex++;
        }

        return $rowIndex;
    }

    private function fillTableTotals($sheet, $data, $rowIndex)
    {
        $totalRow = $rowIndex + 1;

        $sheet->setCellValue('A' . $totalRow, 'ИТОГО:');
        $sheet->mergeCells('A' . $totalRow . ':B' . $totalRow);

        $sheet->setCellValue('C' . $totalRow, array_sum(array_column($data['getModelMachineTicket'], 'cargo')));
        $sheet->setCellValue('D' . $totalRow, array_sum(array_column($data['getModelMachineTicket'], 'kilometres_speedometer')) - array_sum(array_column($data['getModelMachineTicket'], 'cargo')));
        $sheet->setCellValue('E' . $totalRow, array_sum(array_column($data['getModelMachineTicket'], 'kilometres_speedometer')));
        $sheet->setCellValue('F' . $totalRow, array_sum(array_column($data['getModelMachineTicket'], 'pump')));
        $sheet->setCellValue('H' . $totalRow, array_sum(array_column($data['getModelMachineTicket'], 'completed_work')));
        $sheet->setCellValue('I' . $totalRow, round(array_sum(array_column($data['getModelMachineTicket'], 'completed_work')) * array_sum(array_column($data['getModelMachineTicket'], 'cargo')), 0));
        $sheet->setCellValue('J' . $totalRow, array_sum(array_column($data['getModelMachineTicket'], 'opening_balance_fuel')));
        $sheet->setCellValue('K' . $totalRow, array_sum(array_column($data['getModelMachineTicket'], 'opening_balance_butter')));
        $sheet->setCellValue('L' . $totalRow, array_sum(array_column($data['getModelMachineTicket'], 'taken_fuel')));
        $sheet->setCellValue('M' . $totalRow, array_sum(array_column($data['getModelMachineTicket'], 'taken_butter')));
        $sheet->setCellValue('N' . $totalRow, array_sum(array_column($data['getModelMachineTicket'], 'spent_fuel')));
        $sheet->setCellValue('O' . $totalRow, array_sum(array_column($data['getModelMachineTicket'], 'spent_butter')));
        $sheet->setCellValue('P' . $totalRow, array_sum(array_column($data['getModelMachineTicket'], 'normal_fuel')));
        $sheet->setCellValue('Q' . $totalRow, array_sum(array_column($data['getModelMachineTicket'], 'normal_butter')));
        $sheet->setCellValue('R' . $totalRow, array_sum(array_column($data['getModelMachineTicket'], 'closing_balance_fuel')));
        $sheet->setCellValue('S' . $totalRow, array_sum(array_column($data['getModelMachineTicket'], 'closing_balance_butter')));
        $sheet->setCellValue('T' . $totalRow, array_sum(array_column($data['getModelMachineTicket'], 'saving_fuel')));
        $sheet->setCellValue('U' . $totalRow, array_sum(array_column($data['getModelMachineTicket'], 'saving_butter')));
        $sheet->setCellValue('V' . $totalRow, array_sum(array_column($data['getModelMachineTicket'], 'excessive_fuel')));
        $sheet->setCellValue('W' . $totalRow, array_sum(array_column($data['getModelMachineTicket'], 'excessive_butter')));

        // Количество дней
        $sheet->setCellValue('A' . ($totalRow + 1), 'Кол-во дней: ' . array_sum(array_column($data['getModelMachineTicket'], 'day_count')));
    }

    private function fillOperationalIndicators($sheet, $data, $rowIndex)
    {
        $startRow = $rowIndex + 3;

        $sheet->setCellValue('A' . $startRow, 'Эксплуатационные показатели');
        $sheet->mergeCells('A' . $startRow . ':X' . $startRow);
        $sheet->getStyle('A' . $startRow)->getFont()->setBold(true);

        $sheet->setCellValue('A' . ($startRow + 1), 'Наименование');
        $sheet->setCellValue('E' . ($startRow + 1), 'Показатель');

        $sheet->setCellValue('A' . ($startRow + 2), 'Дней в эксплуатации');
        $sheet->setCellValue('E' . ($startRow + 2), count($data['getModelMachineTicket']));
    }

    private function fillSignatures($sheet, $rowIndex)
    {
        $startRow = $rowIndex + 8;

        $sheet->setCellValue('A' . $startRow, 'Начальник');
        $sheet->setCellValue('C' . $startRow, '1-й группы ЦМТО');
        $sheet->setCellValue('H' . $startRow, '(воинское звание, подпись, фамилия)');

        $sheet->setCellValue('A' . ($startRow + 2), 'Старший техник');
        $sheet->setCellValue('H' . ($startRow + 2), '(воинское звание, подпись, фамилия)');

        $sheet->setCellValue('A' . ($startRow + 4), '(дата)');
    }

    private function setColumnWidths($sheet)
    {
        $colWidths = [
            'A' => 12, 'B' => 8, 'C' => 8, 'D' => 8, 'E' => 8,
            'F' => 8, 'G' => 8, 'H' => 8, 'I' => 10, 'J' => 9,
            'K' => 6, 'L' => 8, 'M' => 6, 'N' => 9, 'O' => 6,
            'P' => 9, 'Q' => 6, 'R' => 9, 'S' => 6, 'T' => 8,
            'U' => 6, 'V' => 8, 'W' => 6, 'X' => 15
        ];

        foreach ($colWidths as $col => $width) {
            $sheet->getColumnDimension($col)->setWidth($width);
        }
    }

// Вспомогательный метод для получения данных
    private function getTicketData($idModelMachine, $month, $year)
    {
        $formattedMonth = sprintf("%02d", $month);
        // Здесь ваш код получения данных как в index.php
        return [
            'id' => $idModelMachine,
            'month' => $month,
            'year' => $year,
            'MilitaryNorm' => $this->normModel->where('is_active', '=', 1),
            'MilitaryModelMachine' => $this->machineModel->where('is_active', '=', 1),
            'MilitaryFuel' => $this->fuelModel->where('is_active', '=', 1),
            'MilitaryUnit' => $this->unitModel->where('is_active', '=', 1),

            'getModelMachineTicket' => $this->ticketModel->query()
                ->where('m_model_machine', '=', $idModelMachine)
                ->whereLeft('data_ticket', 7, "$year-$formattedMonth")
                ->orderBy('data_ticket')
                ->get()
            ,
        ];
    }
}