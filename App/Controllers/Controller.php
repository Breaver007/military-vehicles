<?php
// App/Controllers/Controller.php

namespace App\Controllers;

use App\View\View;

abstract class Controller
{
    protected View $view;

    public function __construct()
    {
        $this->view = new View();

        // Устанавливаем глобальные данные для всех шаблонов
        $this->view->share('siteName', 'Мой сайт');
        $this->view->share('currentYear', date('Y'));
    }

    /**
     * Рендеринг шаблона
     */
    protected function view(string $template, array $data = []): void
    {
        $this->view->display($template, $data);
    }

    /**
     * Редирект
     */
    protected function redirect(string $url): void
    {
        header("Location: {$url}");
        exit;
    }

    /**
     * JSON ответ
     */
    protected function json(array $data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }
}