<?php
// App/Controllers/HomeController.php

namespace App\Controllers;

class HomeController extends Controller
{
    public function index(): void
    {
        $data = [
            'title' => 'Главная страница - Инструкция',
            'message' => 'Добро пожаловать!',
            'currentYear' => date('Y')
        ];

        $this->view('home/index', $data);
    }
}