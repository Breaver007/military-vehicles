<?php

namespace App\Controllers;

use App\Controllers\Controller;
use App\Models\OperationalCard\MilitaryAntifreeze;
use App\Models\OperationalCard\MilitaryModelMachine;
use App\Models\OperationalCard\MilitaryTicketAntifreeze;

class MilitaryAntifreezeController extends Controller
{
    private MilitaryAntifreeze $antifreezeModel;
    private MilitaryTicketAntifreeze $recordModel;
    private MilitaryModelMachine $machineModel;

    public function __construct()
    {
        parent::__construct();
        $this->antifreezeModel = new MilitaryAntifreeze();
        $this->recordModel = new MilitaryTicketAntifreeze();
        $this->machineModel = new MilitaryModelMachine();
    }

    // ===== CRUD для видов антифриза =====

    public function index(): void
    {
        $antifreezes = $this->antifreezeModel->allIsActive();

        $this->view('military_antifreeze/index', [
            'title' => 'Виды антифризов',
            'antifreezes' => $antifreezes
        ]);
    }

    public function create(): void
    {
        $this->view('military_antifreeze/create', [
            'title' => 'Добавить вид антифриза'
        ]);
    }

    public function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/military-antifreeze');
        }

        $name = trim($_POST['name'] ?? '');

        if (empty($name)) {
            $_SESSION['error'] = 'Название антифриза обязательно';
            $this->redirect('/military-antifreeze/create');
        }

        $data = [
            'name' => $name,
            'date_edit' => date('Y-m-d H:i:s'),
            'is_active' => 1
        ];

        $id = $this->antifreezeModel->create($data);

        if ($id) {
            $_SESSION['success'] = 'Вид антифриза успешно добавлен';
            $this->redirect('/military-antifreeze');
        } else {
            $_SESSION['error'] = 'Ошибка при добавлении';
            $this->redirect('/military-antifreeze/create');
        }
    }

    public function show(int $id): void
    {
        $antifreeze = $this->antifreezeModel->find($id);

        if (!$antifreeze) {
            $this->redirect('/military-antifreeze');
        }

        $this->view('military_antifreeze/show', [
            'title' => 'Просмотр антифриза',
            'antifreeze' => $antifreeze
        ]);
    }

    public function edit(int $id): void
    {
        $antifreeze = $this->antifreezeModel->find($id);

        if (!$antifreeze) {
            $this->redirect('/military-antifreeze');
        }

        $this->view('military_antifreeze/edit', [
            'title' => 'Редактировать антифриз',
            'antifreeze' => $antifreeze
        ]);
    }

    public function update(int $id): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/military-antifreeze');
        }

        $antifreeze = $this->antifreezeModel->find($id);
        if (!$antifreeze) {
            $this->redirect('/military-antifreeze');
        }
        $name = trim($_POST['name'] ?? '');
        $is_active = $_POST['is_active'] ?? 0;

        if (empty($name)) {
            $_SESSION['error'] = 'Название антифриза обязательно';
            $this->redirect("/military-antifreeze/edit/{$id}");
        }

        $data = [
            'name' => $name,
            'date_edit' => date('Y-m-d H:i:s'),
            'is_active' => $is_active
        ];

        $result = $this->antifreezeModel->update($id, $data);

        if ($result) {
            $_SESSION['success'] = 'Вид антифриза успешно обновлен';
        } else {
            $_SESSION['error'] = 'Ошибка при обновлении';
        }

        $this->redirect('/military-antifreeze');
    }

    public function delete(int $id): void
    {
        $antifreeze = $this->antifreezeModel->find($id);

        if ($antifreeze) {
            $this->antifreezeModel->delete($id);
            $_SESSION['success'] = 'Вид антифриза удален';
        } else {
            $_SESSION['error'] = 'Запись не найдена';
        }

        $this->redirect('/military-antifreeze');
    }

    // ===== Управление записями антифриза =====

    public function recordIndex(): void
    {
        $records = $this->recordModel->query()
            ->orderBy('date', 'DESC')
            ->get();

        $antifreezes = $this->antifreezeModel->allIsActive();
        $machines = $this->machineModel->all();

        $antifreezeMap = [];
        foreach ($antifreezes as $a) $antifreezeMap[$a['id']] = $a;
        $machineMap = [];
        foreach ($machines as $m) $machineMap[$m['id']] = $m;

        $this->view('military_antifreeze/record_index', [
            'title' => 'Записи антифриза',
            'records' => $records,
            'antifreezeMap' => $antifreezeMap,
            'machineMap' => $machineMap,
            'MilitaryAntifreeze' => $antifreezes,
            'MilitaryModelMachine' => $machines,
        ]);
    }

    public function recordCreate(): void
    {
        $this->view('military_antifreeze/record_create', [
            'title' => 'Добавить запись антифриза',
            'MilitaryAntifreeze' => $this->antifreezeModel->where('is_active', '=', 1),
            'MilitaryModelMachine' => $this->machineModel->where('is_active', '=', 1),
        ]);
    }

    public function recordStore(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/military-antifreeze/records');
        }

        $date = $_POST['date'] ?? '';
        $mt_antifreeze_id = (int)($_POST['mt_antifreeze_id'] ?? 0);
        $machine_id = (int)($_POST['machine_id'] ?? 0);
        $value = (int)($_POST['value'] ?? 0);

        $errors = [];
        if (empty($date)) $errors['date'] = 'Укажите дату';
        if (empty($mt_antifreeze_id)) $errors['mt_antifreeze_id'] = 'Выберите вид антифриза';
        if (empty($machine_id)) $errors['machine_id'] = 'Выберите технику';
        if ($value <= 0) $errors['value'] = 'Укажите количество';

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $_POST;
            $this->redirect('/military-antifreeze/records/create');
        }

        $data = [
            'date' => $date,
            'mt_antifreeze_id' => $mt_antifreeze_id,
            'machine_id' => $machine_id,
            'value' => $value,
        ];

        $record = $this->recordModel->create($data);

        if ($record) {
            $_SESSION['success'] = 'Запись антифриза успешно добавлена';
            $this->redirect('/military-antifreeze/records');
        } else {
            $_SESSION['error'] = 'Ошибка при добавлении записи';
            $this->redirect('/military-antifreeze/records/create');
        }
    }

    public function recordEdit(int $id): void
    {
        $record = $this->recordModel->find($id);

        if (!$record) {
            $this->redirect('/military-antifreeze/records');
        }

        $this->view('military_antifreeze/record_edit', [
            'title' => 'Редактировать запись антифриза',
            'record' => $record,
            'MilitaryAntifreeze' => $this->antifreezeModel->where('is_active', '=', 1),
            'MilitaryModelMachine' => $this->machineModel->where('is_active', '=', 1),
        ]);
    }

    public function recordUpdate(int $id): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/military-antifreeze/records');
        }

        $record = $this->recordModel->find($id);
        if (!$record) {
            $this->redirect('/military-antifreeze/records');
        }

        $data = [
            'date' => $_POST['date'] ?? $record['date'],
            'mt_antifreeze_id' => (int)($_POST['mt_antifreeze_id'] ?? $record['mt_antifreeze_id']),
            'machine_id' => (int)($_POST['machine_id'] ?? $record['machine_id']),
            'value' => (int)($_POST['value'] ?? $record['value']),
        ];

        $result = $this->recordModel->update($id, $data);

        if ($result) {
            $_SESSION['success'] = 'Запись антифриза обновлена';
        } else {
            $_SESSION['error'] = 'Ошибка при обновлении';
        }

        $this->redirect('/military-antifreeze/records');
    }

    public function recordDelete(int $id): void
    {
        $record = $this->recordModel->find($id);

        if ($record) {
            $this->recordModel->delete($id);
            $_SESSION['success'] = 'Запись антифриза удалена';
        } else {
            $_SESSION['error'] = 'Запись не найдена';
        }

        $this->redirect('/military-antifreeze/records');
    }
}
