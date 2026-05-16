<?php

namespace App\Controllers;

use App\Controllers\Controller;
use App\Models\OperationalCard\MilitaryFuel;
use App\Models\OperationalCard\MilitaryModelMachine;
use App\Models\OperationalCard\MilitaryUnit;

class MilitaryModelMachineController extends Controller
{
    private MilitaryModelMachine $machineModel;
    private MilitaryUnit $unitModel;
    private MilitaryFuel $fuelModel;

    public function __construct()
    {
        parent::__construct();
        $this->machineModel = new MilitaryModelMachine();
        $this->unitModel = new MilitaryUnit();
        $this->fuelModel = new MilitaryFuel();
    }

    /**
     * Список всех машин
     */
    public function index(): void
    {
        $machines = $this->machineModel->allWithRelationsIsActive();

        $this->view('military_machine/index', [
            'title' => 'Техника',
            'machines' => $machines
        ]);
    }

    /**
     * Форма создания новой машины
     */
    public function create(): void
    {
        $units = $this->unitModel->all();
        $fuels = $this->fuelModel->all();
        $maxOrder = $this->machineModel->getMaxOrder();

        $this->view('military_machine/create', [
            'title' => 'Добавить технику',
            'units' => $units,
            'fuels' => $fuels,
            'nextOrder' => $maxOrder + 10
        ]);
    }

    /**
     * Сохранение новой машины
     */
    public function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/military-machine');
        }

        $errors = $this->validateMachine($_POST);

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $_POST;
            $this->redirect('/military-machine/create');
        }

        $data = [
            'm_unit' => (int)$_POST['m_unit'],
            'm_fuel' => (int)$_POST['m_fuel'],
            'name' => trim($_POST['name']),
            'registr_plate' => trim($_POST['registr_plate']),
            'order' => (int)$_POST['order'],
            'data_edit' => date('Y-m-d H:i:s'),
            'is_active' => 1
        ];

        $id = $this->machineModel->create($data);

        if ($id) {
            $_SESSION['success'] = 'Техника успешно добавлена';
            $this->redirect('/military-machine');
        } else {
            $_SESSION['error'] = 'Ошибка при добавлении';
            $this->redirect('/military-machine/create');
        }
    }

    /**
     * Просмотр одной машины
     */
    public function show(int $id): void
    {
        $machine = $this->machineModel->findWithRelations($id);

        if (!$machine) {
            $this->redirect('/military-machine');
        }

        $this->view('military_machine/show', [
            'title' => 'Просмотр техники',
            'machine' => $machine
        ]);
    }

    /**
     * Форма редактирования машины
     */
    public function edit(int $id): void
    {

        $machine = $this->machineModel->find($id);

        if (!$machine) {
            $this->redirect('/military-machine');
        }

        $units = $this->unitModel->all();
        $fuels = $this->fuelModel->all();
        $maxOrder = $this->machineModel->getMaxOrder();

        $this->view('military_machine/edit', [
            'title' => 'Редактировать технику',
            'machine' => $machine,
            'units' => $units,
            'fuels' => $fuels,
            'maxOrder' => $maxOrder
        ]);
    }

    /**
     * Обновление машины
     */
    public function update(int $id): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/military-machine');
        }

        $machine = $this->machineModel->find($id);
        if (!$machine) {
            $this->redirect('/military-machine');
        }

        $errors = $this->validateMachine($_POST);

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $_POST;
            $this->redirect("/military-machine/edit/{$id}");
        }

        $data = [
            'm_unit' => (int)$_POST['m_unit'],
            'm_fuel' => (int)$_POST['m_fuel'],
            'name' => trim($_POST['name']),
            'registr_plate' => trim($_POST['registr_plate']),
            'order' => (int)$_POST['order'],
            'is_active' => $_POST['is_active'] ?? 0,
            'data_edit' => date('Y-m-d H:i:s')
        ];

        $result = $this->machineModel->update($id, $data);

        if ($result) {
            $_SESSION['success'] = 'Техника успешно обновлена';
        } else {
            $_SESSION['error'] = 'Ошибка при обновлении';
        }

        $this->redirect('/military-machine');
    }

    /**
     * Удаление машины (мягкое)
     */
    public function delete(int $id): void
    {
        $machine = $this->machineModel->find($id);

        if ($machine) {
            $this->machineModel->delete($id);
            $_SESSION['success'] = 'Техника удалена';
        } else {
            $_SESSION['error'] = 'Запись не найдена';
        }

        $this->redirect('/military-machine');
    }

    /**
     * Валидация данных машины
     */
    private function validateMachine(array $data): array
    {
        $errors = [];

        if (empty($data['m_unit'])) {
            $errors['m_unit'] = 'Выберите воинскую часть';
        }

        if (empty($data['m_fuel'])) {
            $errors['m_fuel'] = 'Выберите вид топлива';
        }

        if (empty(trim($data['name'] ?? ''))) {
            $errors['name'] = 'Название модели обязательно';
        }

        if (empty(trim($data['registr_plate'] ?? ''))) {
            $errors['registr_plate'] = 'Регистрационный номер обязателен';
        }

        if (empty($data['order'])) {
            $errors['order'] = 'Порядок сортировки обязателен';
        } elseif (!is_numeric($data['order'])) {
            $errors['order'] = 'Порядок сортировки должен быть числом';
        }

        return $errors;
    }
}