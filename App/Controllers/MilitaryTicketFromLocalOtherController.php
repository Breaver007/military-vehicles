<?php

namespace App\Controllers;

use App\Controllers\Controller;
use App\Models\OperationalCard\MilitaryTicketFromLocalOther;

class MilitaryTicketFromLocalOtherController extends Controller
{
    private MilitaryTicketFromLocalOther $fromLocalOtherModel;

    public function __construct()
    {
        parent::__construct();
        $this->fromLocalOtherModel = new MilitaryTicketFromLocalOther();
    }

    /**
     * Список всех видов
     */
    public
    function index(): void
    {
        $localOthers = $this->fromLocalOtherModel->allIsActive();
        $this->view('military_ticket_from_local_other/index', [
            'title' => 'Заправки',
            'localOthers' => $localOthers
        ]);
    }

    /**
     * Форма создания нового
     */
    public function create(): void
    {
        $this->view('military_ticket_from_local_other/create', [
            'title' => 'Добавить запись'
        ]);
    }

    /**
     * Сохранение нового
     */
    public function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/military-ticket-from-local-other');
        }

        $name = trim($_POST['name'] ?? '');
        $nameDocument = trim($_POST['name_document'] ?? '');
        $numberDocument = trim($_POST['number_document'] ?? '');
        $date = trim($_POST['date'] ?? '');

        if (empty($name)) {
            $_SESSION['error'] = 'Откуда обязательно';
            $this->redirect('/military-ticket-from-local-other/create');
        }

        $data = [
            'name' => $name,
            'name_document' => $nameDocument,
            'number_document' => $numberDocument,
            'date' => $date,
            'date_edit' => date('Y-m-d H:i:s'),
            'is_active' => 1
        ];

        $id = $this->fromLocalOtherModel->create($data);

        if ($id) {
            $_SESSION['success'] = 'Запись успешно добавлена';
            $this->redirect('/military-ticket-from-local-other');
        } else {
            $_SESSION['error'] = 'Ошибка при добавлении';
            $this->redirect('/military-ticket-from-local-other/create');
        }
    }

    /**
     * Просмотр одного топлива
     */
    public function show(int $id): void
    {
        $localOthers = $this->fromLocalOtherModel->find($id);

        if (!$localOthers) {
            $this->redirect('/military-ticket-from-local-other');
        }

        $this->view('military_ticket_from_local_other/show', [
            'title' => 'Просмотр записей',
            'localOthers' => $localOthers
        ]);
    }

    /**
     * Форма редактирования
     */
    public function edit(int $id): void
    {
        $localOthers = $this->fromLocalOtherModel->find($id);

        if (!$localOthers) {
            $this->redirect('/military-ticket-from-local-other');
        }

        $this->view('military_ticket_from_local_other/edit', [
            'title' => 'Редактировать запись',
            'localOthers' => $localOthers
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

        $fuel = $this->fromLocalOtherModel->find($id);
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

        $result = $this->fromLocalOtherModel->update($id, $data);

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
        $localOthers = $this->fromLocalOtherModel->find($id);

        if ($localOthers) {
            $this->fromLocalOtherModel->delete($id);
            $_SESSION['success'] = 'Запись удалена';
        } else {
            $_SESSION['error'] = 'Запись не найдена';
        }

        $this->redirect('/military-ticket-from-local-other');
    }
}