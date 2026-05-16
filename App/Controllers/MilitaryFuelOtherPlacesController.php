<?php

namespace App\Controllers;

use App\Controllers\Controller;
use App\Models\OperationalCard\MilitaryFuelOtherPlaces;

class MilitaryFuelOtherPlacesController extends Controller
{
    private MilitaryFuelOtherPlaces $fuelOtherPlacesModel;

    public function __construct()
    {
        parent::__construct();
        $this->fuelOtherPlacesModel = new MilitaryFuelOtherPlaces();
    }

    /**
     * Список всех видов топлива
     */
    public
    function index(): void
    {
        $fuels = $this->fuelOtherPlacesModel->allIsActive();

        $this->view('military_fuel_other_places/index', [
            'title' => 'Заправки',
            'fuels' => $fuels
        ]);
    }

    /**
     * Форма создания нового топлива
     */
    public function create(): void
    {
        $this->view('military_fuel_other_places/create', [
            'title' => 'Добавить заправку'
        ]);
    }

    /**
     * Сохранение нового топлива
     */
    public function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/military-fuel-other-places');
        }

        $name = trim($_POST['name'] ?? '');

        if (empty($name)) {
            $_SESSION['error'] = 'Название обязательно';
            $this->redirect('/military-fuel-other-places/create');
        }

        $data = [
            'name' => $name,
            'date_edit' => date('Y-m-d H:i:s'),
            'is_active' => 1
        ];

        $id = $this->fuelOtherPlacesModel->create($data);

        if ($id) {
            $_SESSION['success'] = 'Заправка успешно добавлена';
            $this->redirect('/military-fuel-other-places');
        } else {
            $_SESSION['error'] = 'Ошибка при добавлении';
            $this->redirect('/military-fuel-other-places/create');
        }
    }

    /**
     * Просмотр одного топлива
     */
    public function show(int $id): void
    {
        $fuel = $this->fuelOtherPlacesModel->find($id);

        if (!$fuel) {
            $this->redirect('/military-fuel-other-places');
        }

        $this->view('military_fuel_other_places/show', [
            'title' => 'Просмотр заправок',
            'fuel' => $fuel
        ]);
    }

    /**
     * Форма редактирования топлива
     */
    public function edit(int $id): void
    {
        $fuel = $this->fuelOtherPlacesModel->find($id);

        if (!$fuel) {
            $this->redirect('/military-fuel-other-places');
        }

        $this->view('military_fuel_other_places/edit', [
            'title' => 'Редактировать заправку',
            'fuel' => $fuel
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

        $fuel = $this->fuelOtherPlacesModel->find($id);
        if (!$fuel) {
            $this->redirect('/military-fuel-other-places');
        }
        $name = trim($_POST['name'] ?? '');
        $is_active = $_POST['is_active'] ?? 0;

        if (empty($name)) {
            $_SESSION['error'] = 'Название  обязательно';
            $this->redirect("/military-fuel-other-places/edit/{$id}");
        }

        $data = [
            'name' => $name,
            'date_edit' => date('Y-m-d H:i:s'),
            'is_active' => $is_active // Уже 0 или 1
        ];

        $result = $this->fuelOtherPlacesModel->update($id, $data);

        if ($result) {
            $_SESSION['success'] = 'Заправка успешно обновлена';
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
        $fuel = $this->fuelOtherPlacesModel->find($id);

        if ($fuel) {
            $this->fuelOtherPlacesModel->delete($id);
            $_SESSION['success'] = 'Заправка удалена';
        } else {
            $_SESSION['error'] = 'Запись не найдена';
        }

        $this->redirect('/military-fuel-other-places');
    }
}