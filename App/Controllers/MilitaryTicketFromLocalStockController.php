<?php

namespace App\Controllers;

use App\Controllers\Controller;
use App\Models\OperationalCard\MilitaryTicketFromLocalStock;

class MilitaryTicketFromLocalStockController extends Controller
{
    private MilitaryTicketFromLocalStock $fromLocalStockModel;

    public function __construct()
    {
        parent::__construct();
        $this->fromLocalStockModel = new MilitaryTicketFromLocalStock();
    }

    /**
     * Список всех видов
     */
    public
    function index(): void
    {
        $localStocks = $this->fromLocalStockModel->allIsActive();

        $this->view('military_ticket_from_local_stock/index', [
            'title' => 'Склад в/ч',
            'localStocks' => $localStocks
        ]);
    }

    /**
     * Форма создания нового
     */
    public function create(): void
    {
        $this->view('military_ticket_from_local_stock/create', [
            'title' => 'Добавить запись/склад'
        ]);
    }

    /**
     * Сохранение нового
     */
    public function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/military-ticket-from-local-stock');
        }

        $name = trim($_POST['name'] ?? '');
        $nameDocument = trim($_POST['name_document'] ?? '');
        $numberDocument = trim($_POST['number_document'] ?? '');
        $date = trim($_POST['date'] ?? '');

        if (empty($name)) {
            $_SESSION['error'] = 'Откуда обязательно';
            $this->redirect('/military-ticket-from-local-stock/create');
        }

        $data = [
            'name' => $name,
            'name_document' => $nameDocument,
            'number_document' => $numberDocument,
            'date' => $date,
            'date_edit' => date('Y-m-d H:i:s'),
            'is_active' => 1
        ];

        $id = $this->fromLocalStockModel->create($data);

        if ($id) {
            $_SESSION['success'] = 'Запись успешно добавлена';
            $this->redirect('/military-ticket-from-local-stock');
        } else {
            $_SESSION['error'] = 'Ошибка при добавлении';
            $this->redirect('/military-ticket-from-local-stock/create');
        }
    }

    /**
     * Просмотр одного топлива
     */
    public function show(int $id): void
    {
        $localStocks = $this->fromLocalStockModel->find($id);

        if (!$localStocks) {
            $this->redirect('/military-ticket-from-local-stock');
        }

        $this->view('military_ticket_from_local_stock/show', [
            'title' => 'Просмотр записей',
            'localStocks' => $localStocks
        ]);
    }

    /**
     * Форма редактирования
     */
    public function edit(int $id): void
    {
        $localStocks = $this->fromLocalStockModel->find($id);

        if (!$localStocks) {
            $this->redirect('/military-ticket-from-local-stock');
        }

        $this->view('military_ticket_from_local_stock/edit', [
            'title' => 'Редактировать запись',
            'localStocks' => $localStocks
        ]);
    }

    /**
     * Обновление топлива
     */
    public function update(int $id): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/military-fuel-other-places');
        }

        $fuel = $this->fromLocalStockModel->find($id);
        if (!$fuel) {
            $this->redirect('/military-fuel-other-places');
        }
        $name = trim($_POST['name'] ?? '');
        $date = trim($_POST['date'] ?? '');
        $nameDocument = trim($_POST['name_document'] ?? '');
        $numberDocument = trim($_POST['number_document'] ?? '');
        $is_active = $_POST['is_active'] ?? 0;

        if (empty($name)) {
            $_SESSION['error'] = 'Название  обязательно';
            $this->redirect("/military-fuel-other-places/edit/{$id}");
        }

        $data = [
            'name' => $name,
            'name_document' => $nameDocument,
            'number_document' => $numberDocument,
            'date' => $date,
            'date_edit' => date('Y-m-d H:i:s'),
            'is_active' => $is_active // Уже 0 или 1
        ];

        $result = $this->fromLocalStockModel->update($id, $data);

        if ($result) {
            $_SESSION['success'] = 'Запись успешно обновлена';
        } else {
            $_SESSION['error'] = 'Ошибка при обновлении';
        }

        $this->redirect('/military-fuel-other-places');
    }

    /**
     * Удаление топлива (мягкое)
     */
    public function delete(int $id): void
    {
        $localStocks = $this->fromLocalStockModel->find($id);

        if ($localStocks) {
            $this->fromLocalStockModel->delete($id);
            $_SESSION['success'] = 'Запись удалена';
        } else {
            $_SESSION['error'] = 'Запись не найдена';
        }

        $this->redirect('/military-ticket-from-local-stock');
    }
}