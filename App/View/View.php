<?php
// App/View/View.php

namespace App\View;

class View
{
    private string $viewsPath;
    private array $globalData = [];

    public function __construct(string $viewsPath = null)
    {
        $this->viewsPath = $viewsPath ?? __DIR__ . '/../../views';
    }

    /**
     * Установка глобальных данных для всех шаблонов
     */
    public function share(string $key, $value): void
    {
        $this->globalData[$key] = $value;
    }

    /**
     * Рендеринг шаблона
     */
    public function render(string $template, array $data = []): string
    {
        // Объединяем глобальные данные с локальными
        $data = array_merge($this->globalData, $data);

        // Извлекаем переменные в текущий символ таблицы
        extract($data);

        // Подключаем шаблон
        $templatePath = $this->viewsPath . '/' . $template . '.php';

        if (!file_exists($templatePath)) {
            throw new \RuntimeException("Шаблон не найден: {$templatePath}");
        }

        // Буферизация вывода
        ob_start();
        include $templatePath;
        $content = ob_get_clean();
        include $this->viewsPath . '/layout.php';
        // Возвращаем содержимое буфера
        return ob_get_clean();
    }

    /**
     * Вывод шаблона сразу в браузер
     */
    public function display(string $template, array $data = []): void
    {
        echo $this->render($template, $data);
    }
}