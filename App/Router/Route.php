<?php
// src/Router/Route.php

namespace App\Router;

class Route
{
    private string $method;
    private string $path;
    private $handler;
    private string $name = '';
    private array $parameters = [];
    private static ?Router $router = null;

    public function __construct(string $method, string $path, $handler)
    {
        $this->method = strtoupper($method);
        $this->path = $path;
        $this->handler = $handler;
    }

    /**
     * Установка статической ссылки на роутер
     */
    public static function setRouter(Router $router): void
    {
        self::$router = $router;
    }

    /**
     * Установка имени маршрута
     */
    public function name(string $name): self
    {
        $this->name = $name;

        // Автоматически регистрируем именованный маршрут в роутере
        if (self::$router) {
            self::$router->registerNamedRoute($this);
        }

        return $this;
    }

    /**
     * Проверка соответствия маршрута
     */
    public function matches(string $method, string $uri): bool
    {
        if ($this->method !== $method) {
            return false;
        }

        $pattern = $this->buildPattern();

        if (preg_match($pattern, $uri, $matches)) {
            $this->parameters = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
            return true;
        }

        return false;
    }

    /**
     * Получение параметров маршрута
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * Получение обработчика
     */
    public function getHandler()
    {
        return $this->handler;
    }

    /**
     * Получение имени маршрута
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Генерация URL по имени маршрута
     */
    public function generateUrl(array $parameters = []): string
    {
        $url = $this->path;

        foreach ($parameters as $key => $value) {
            $url = str_replace('{' . $key . '}', $value, $url);
        }

        // Удаляем оставшиеся параметры (если не все были переданы)
        $url = preg_replace('/\{[^}]+\}/', '', $url);

        return $url;
    }

    /**
     * Построение регулярного выражения для маршрута
     */
    private function buildPattern(): string
    {
        $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<$1>[^/]+)', $this->path);
        return '#^' . $pattern . '$#';
    }
}