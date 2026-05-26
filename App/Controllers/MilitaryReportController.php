<?php

namespace App\Controllers;
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
use App\Models\OperationalCard\MilitaryButter;
use App\Models\OperationalCard\MilitaryTicketButter;
use App\Models\OperationalCard\MilitaryAntifreeze;
use App\Models\OperationalCard\MilitaryTicketAntifreeze;

class MilitaryReportController extends Controller
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
    private MilitaryButter $butterModel;
    private MilitaryTicketButter $ticketButterModel;
    private MilitaryAntifreeze $antifreezeModel;
    private MilitaryTicketAntifreeze $ticketAntifreezeModel;
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
        $this->butterModel = new MilitaryButter();
        $this->ticketButterModel = new MilitaryTicketButter();
        $this->antifreezeModel = new MilitaryAntifreeze();
        $this->ticketAntifreezeModel = new MilitaryTicketAntifreeze();
    }

    public function printSelect(): void
    {
        $this->view('military_report/print_select', [
            'title' => 'Выбор периода для печати',
            'currentDate' => date('Y-m-d')
        ]);
    }
    public function printForm(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/military-report/print-select');
            return;
        }

        $startDate = $_POST['start_date'] ?? '';
        $endDate = $_POST['end_date'] ?? '';

        // Валидация дат
        if (empty($startDate) || empty($endDate)) {
            $_SESSION['error'] = 'Укажите период печати';
            $this->redirect("/military-report/print-select");
            return;
        }
        $modelMachineTickets  = $this->ticketModel->query()
            ->where('data_ticket', '>=', $startDate)
            ->where('data_ticket', '<=', $endDate)
            ->orderBy('data_ticket')
            ->get();
        foreach ($modelMachineTickets as $mmt) {
            $data['ModelMachineTicket'][$mmt['id']] = $mmt;
        }
        // Загружаем технику с индексацией по ID
        $machines = $this->machineModel->query()->get();
        $data['ModelMachine'] = [];
        foreach ($machines as $m) {
            $data['ModelMachine'][$m['id']] = $m;
        }
        // Загружаем топливо с индексацией по ID
        $fuels = $this->fuelModel->query()->get();
        $data['FuelModel'] = [];
        foreach ($fuels as $f) {
            $data['FuelModel'][$f['id']] = $f;
        }

        // Список заправок части
        $localStock = $this->localStockModel->query()->get();
        $data['localStock'] = [];
        foreach ($localStock as $f) {
            $data['localStock'][$f['id']] = $f;
        }

        // Список заправок других частей
        $otherStock = $this->OtherStockModel->query()->get();
        $data['otherStock'] = [];
        foreach ($otherStock as $f) {
            $data['otherStock'][$f['id']] = $f;
        }

        // Список заправок АЗС
        $fuelOtherPlace = $this->fuelOtherPlacesModel->query()->get();
        $data['fuelOtherPlace'] = [];
        foreach ($fuelOtherPlace as $f) {
            $data['fuelOtherPlace'][$f['id']] = $f;
        }

        // Загружаем справочник масел
        $butters = $this->butterModel->all();
        $data['ButterModel'] = [];
        foreach ($butters as $b) {
            $data['ButterModel'][$b['id']] = $b;
        }

        // Загружаем справочник антифризов
        $antifreezes = $this->antifreezeModel->all();
        $data['AntifreezeModel'] = [];
        foreach ($antifreezes as $a) {
            $data['AntifreezeModel'][$a['id']] = $a;
        }

        // Загружаем записи антифризов за период
        $antifreezeRecords = $this->ticketAntifreezeModel->query()
            ->where('date', '>=', $startDate)
            ->where('date', '<=', $endDate)
            ->orderBy('date')
            ->get();
        $data['ticketAntifreezeModel'] = $antifreezeRecords ?? [];

        if (empty($data['ModelMachineTicket'])) {
            $_SESSION['error'] = 'За указанный период нет данных для печати';
            $this->redirect("/military-report/print-select");
            return;
        }

        foreach ( $data['ModelMachineTicket'] as $index => $datum) {
            $data['ticketLocalModel'][$datum['id']] = $this->ticketLocalModel->getAllToParentId($datum['id']);
            $data['ticketOtherModel'][$datum['id']] = $this->ticketOtherModel->getAllToParentId($datum['id']);
            $data['ticketPlacesModel'][$datum['id']] = $this->ticketPlacesModel->getAllToParentId($datum['id']);
            $data['ticketButterModel'][$datum['id']] = $this->ticketButterModel->getAllToParentId($datum['id']);
        }
        // Подключаем шаблон для печати
        $data['startDate'] = $startDate;
        $data['endDate'] = $endDate;
        include 'views/military_report/print.php';
    }
}