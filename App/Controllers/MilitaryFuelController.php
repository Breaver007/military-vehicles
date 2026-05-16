<?php

namespace App\Controllers;

use App\Controllers\Controller;
use App\Models\OperationalCard\MilitaryFuel;

class MilitaryFuelController extends Controller
{
    private MilitaryFuel $fuelModel;

    public function __construct()
    {
        parent::__construct();
        $this->fuelModel = new MilitaryFuel();
    }

    /**
     * Список всех видов топлива
     */
    public
    function index(): void
    {
        $fuels = $this->fuelModel->allIsActive();

        $this->view('military_fuel/index', [
            'title' => 'Виды топлива',
            'fuels' => $fuels
        ]);
    }

    /**
     * Форма создания нового топлива
     */
    public function create(): void
    {
        $this->view('military_fuel/create', [
            'title' => 'Добавить вид топлива'
        ]);
    }

    /**
     * Сохранение нового топлива
     */
    public function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/military-fuel');
        }

        $name = trim($_POST['name'] ?? '');

        if (empty($name)) {
            $_SESSION['error'] = 'Название топлива обязательно';
            $this->redirect('/military-fuel/create');
        }

        $data = [
            'name' => $name,
            'date_edit' => date('Y-m-d H:i:s'),
            'is_active' => 1
        ];

        $id = $this->fuelModel->create($data);

        if ($id) {
            $_SESSION['success'] = 'Вид топлива успешно добавлен';
            $this->redirect('/military-fuel');
        } else {
            $_SESSION['error'] = 'Ошибка при добавлении';
            $this->redirect('/military-fuel/create');
        }
    }

    /**
     * Просмотр одного топлива
     */
    public function show(int $id): void
    {
        $fuel = $this->fuelModel->find($id);

        if (!$fuel) {
            $this->redirect('/military-fuel');
        }

        $this->view('military_fuel/show', [
            'title' => 'Просмотр топлива',
            'fuel' => $fuel
        ]);
    }

    /**
     * Форма редактирования топлива
     */
    public function edit(int $id): void
    {
        $fuel = $this->fuelModel->find($id);

        if (!$fuel) {
            $this->redirect('/military-fuel');
        }

        $this->view('military_fuel/edit', [
            'title' => 'Редактировать топливо',
            'fuel' => $fuel
        ]);
    }

    /**
     * Обновление топлива
     */
    public function update(int $id): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/military-fuel');
        }

        $fuel = $this->fuelModel->find($id);
        if (!$fuel) {
            $this->redirect('/military-fuel');
        }
        $name = trim($_POST['name'] ?? '');
        $is_active = $_POST['is_active'] ?? 0;

        if (empty($name)) {
            $_SESSION['error'] = 'Название топлива обязательно';
            $this->redirect("/military-fuel/edit/{$id}");
        }

        $data = [
            'name' => $name,
            'date_edit' => date('Y-m-d H:i:s'),
            'is_active' => $is_active // Уже 0 или 1
        ];

        $result = $this->fuelModel->update($id, $data);

        if ($result) {
            $_SESSION['success'] = 'Вид топлива успешно обновлен';
        } else {
            $_SESSION['error'] = 'Ошибка при обновлении';
        }

        $this->redirect('/military-fuel');
    }

    /**
     * Удаление топлива (мягкое)
     */
    public function delete(int $id): void
    {
        $fuel = $this->fuelModel->find($id);

        if ($fuel) {
            $this->fuelModel->delete($id);
            $_SESSION['success'] = 'Вид топлива удален';
        } else {
            $_SESSION['error'] = 'Запись не найдена';
        }

        $this->redirect('/military-fuel');
    }
}