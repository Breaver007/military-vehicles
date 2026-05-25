<?php

namespace App\Controllers;

use App\Controllers\Controller;
use App\Models\OperationalCard\MilitaryButter;

class MilitaryButterController extends Controller
{
    private MilitaryButter $butterModel;

    public function __construct()
    {
        parent::__construct();
        $this->butterModel = new MilitaryButter();
    }

    public function index(): void
    {
        $butters = $this->butterModel->allIsActive();

        $this->view('military_butter/index', [
            'title' => 'Виды масел',
            'butters' => $butters
        ]);
    }

    public function create(): void
    {
        $this->view('military_butter/create', [
            'title' => 'Добавить вид масла'
        ]);
    }

    public function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/military-butter');
        }

        $name = trim($_POST['name'] ?? '');

        if (empty($name)) {
            $_SESSION['error'] = 'Название масла обязательно';
            $this->redirect('/military-butter/create');
        }

        $data = [
            'name' => $name,
            'date_edit' => date('Y-m-d H:i:s'),
            'is_active' => 1
        ];

        $id = $this->butterModel->create($data);

        if ($id) {
            $_SESSION['success'] = 'Вид масла успешно добавлен';
            $this->redirect('/military-butter');
        } else {
            $_SESSION['error'] = 'Ошибка при добавлении';
            $this->redirect('/military-butter/create');
        }
    }

    public function show(int $id): void
    {
        $butter = $this->butterModel->find($id);

        if (!$butter) {
            $this->redirect('/military-butter');
        }

        $this->view('military_butter/show', [
            'title' => 'Просмотр масла',
            'butter' => $butter
        ]);
    }

    public function edit(int $id): void
    {
        $butter = $this->butterModel->find($id);

        if (!$butter) {
            $this->redirect('/military-butter');
        }

        $this->view('military_butter/edit', [
            'title' => 'Редактировать масло',
            'butter' => $butter
        ]);
    }

    public function update(int $id): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/military-butter');
        }

        $butter = $this->butterModel->find($id);
        if (!$butter) {
            $this->redirect('/military-butter');
        }
        $name = trim($_POST['name'] ?? '');
        $is_active = $_POST['is_active'] ?? 0;

        if (empty($name)) {
            $_SESSION['error'] = 'Название масла обязательно';
            $this->redirect("/military-butter/edit/{$id}");
        }

        $data = [
            'name' => $name,
            'date_edit' => date('Y-m-d H:i:s'),
            'is_active' => $is_active
        ];

        $result = $this->butterModel->update($id, $data);

        if ($result) {
            $_SESSION['success'] = 'Вид масла успешно обновлен';
        } else {
            $_SESSION['error'] = 'Ошибка при обновлении';
        }

        $this->redirect('/military-butter');
    }

    public function delete(int $id): void
    {
        $butter = $this->butterModel->find($id);

        if ($butter) {
            $this->butterModel->delete($id);
            $_SESSION['success'] = 'Вид масла удален';
        } else {
            $_SESSION['error'] = 'Запись не найдена';
        }

        $this->redirect('/military-butter');
    }
}
