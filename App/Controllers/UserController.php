<?php
// App/Controllers/UserController.php

namespace App\Controllers;

class UserController extends Controller
{
    private array $users = [
        1 => ['name' => 'Иван', 'email' => 'ivan@example.com', 'role' => 'admin'],
        2 => ['name' => 'Мария', 'email' => 'maria@example.com', 'role' => 'user'],
        3 => ['name' => 'Петр', 'email' => 'petr@example.com', 'role' => 'user'],
    ];

    public function index(): void
    {
        $this->view('users/index', [
            'title' => 'Список пользователей',
            'users' => $this->users
        ]);
    }

    public function show(int $id): void
    {
        if (isset($this->users[$id])) {
            $this->view('users/show', [
                'title' => 'Профиль пользователя',
                'user' => $this->users[$id],
                'userId' => $id
            ]);
        } else {
            $this->view('users/not-found', [
                'title' => 'Пользователь не найден',
                'userId' => $id
            ]);
        }
    }

    public function create(): void
    {
        $this->view('users/create', ['title' => 'Создание пользователя']);
    }

    public function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $email = $_POST['email'] ?? '';

            // Здесь сохранение в БД

            $this->view('users/store-success', [
                'title' => 'Пользователь создан',
                'name' => $name,
                'email' => $email
            ]);
        } else {
            $this->redirect('/users/create');
        }
    }
}