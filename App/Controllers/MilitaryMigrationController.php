<?php

namespace App\Controllers;

use App\Database\Database;
use App\Models\OperationalCard\MilitaryMigration;

class MilitaryMigrationController extends Controller
{
    private MilitaryMigration $migrationModel;

    public function __construct()
    {
        parent::__construct();
        $this->migrationModel = new MilitaryMigration();
    }

    public function index(): void
    {
        $migrations = $this->migrationModel->query()->orderBy('id', 'DESC')->get();

        $this->view('military_migration/index', [
            'title' => 'Миграции SQL',
            'migrations' => $migrations
        ]);
    }

    public function create(): void
    {
        $this->view('military_migration/create', [
            'title' => 'Новая миграция'
        ]);
    }

    public function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/military-migration');
        }

        $name = trim($_POST['name'] ?? '');
        $sql = trim($_POST['sql'] ?? '');

        if (empty($name)) {
            $_SESSION['error'] = 'Название обязательно';
            $_SESSION['old'] = $_POST;
            $this->redirect('/military-migration/create');
        }

        if (empty($sql)) {
            $_SESSION['error'] = 'SQL-запрос обязателен';
            $_SESSION['old'] = $_POST;
            $this->redirect('/military-migration/create');
        }

        $data = [
            'name' => $name,
            'sql' => $sql,
            'is_executed' => 0,
            'created_at' => date('Y-m-d H:i:s')
        ];

        $result = $this->migrationModel->create($data);

        if ($result) {
            $_SESSION['success'] = 'Миграция создана';
            $this->redirect('/military-migration');
        } else {
            $_SESSION['error'] = 'Ошибка при создании';
            $this->redirect('/military-migration/create');
        }
    }

    public function show(int $id): void
    {
        $migration = $this->migrationModel->find($id);

        if (!$migration) {
            $this->redirect('/military-migration');
        }

        $this->view('military_migration/show', [
            'title' => 'Просмотр миграции',
            'migration' => $migration
        ]);
    }

    public function execute(int $id): void
    {
        $migration = $this->migrationModel->find($id);

        if (!$migration) {
            $this->redirect('/military-migration');
        }

        if ($migration['is_executed']) {
            $_SESSION['error'] = 'Миграция уже выполнена';
            $this->redirect('/military-migration');
        }

        try {
            $pdo = Database::getConnection();
            $pdo->exec($migration['sql']);

            $this->migrationModel->update($id, [
                'is_executed' => 1,
                'executed_at' => date('Y-m-d H:i:s')
            ]);

            $_SESSION['success'] = 'Миграция успешно выполнена';
        } catch (\PDOException $e) {
            $_SESSION['error'] = 'Ошибка SQL: ' . $e->getMessage();
        }

        $this->redirect('/military-migration');
    }

    public function delete(int $id): void
    {
        $migration = $this->migrationModel->find($id);

        if ($migration) {
            $this->migrationModel->delete($id);
            $_SESSION['success'] = 'Миграция удалена';
        } else {
            $_SESSION['error'] = 'Запись не найдена';
        }

        $this->redirect('/military-migration');
    }
}
