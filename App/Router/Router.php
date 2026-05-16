<?php
// src/Router/Router.php

namespace App\Router;

class Router
{
    private array $routes = [];
    private array $namedRoutes = [];

    /**
     * Добавление маршрута GET
     */
    public function get(string $path, $handler): Route
    {
        return $this->add('GET', $path, $handler);
    }

    /**
     * Добавление маршрута POST
     */
    public function post(string $path, $handler): Route
    {
        return $this->add('POST', $path, $handler);
    }

    /**
     * Добавление маршрута PUT
     */
    public function put(string $path, $handler): Route
    {
        return $this->add('PUT', $path, $handler);
    }

    /**
     * Добавление маршрута DELETE
     */
    public function delete(string $path, $handler): Route
    {
        return $this->add('DELETE', $path, $handler);
    }

    /**
     * Добавление маршрута
     */
    private function add(string $method, string $path, $handler): Route
    {
        $route = new Route($method, $path, $handler);
        $this->routes[] = $route;

        // Сразу регистрируем именованный маршрут, если имя было установлено
        // (это будет сделано через вызов name() после получения маршрута)

        return $route;
    }

    /**
     * Регистрация именованного маршрута
     * Этот метод будет вызываться из Route::name()
     */
    public function registerNamedRoute(Route $route): void
    {
        if ($route->getName()) {
            $this->namedRoutes[$route->getName()] = $route;
        }
    }

    /**
     * Получение всех маршрутов
     */
    public function getRoutes(): array
    {
        return $this->routes;
    }

    /**
     * Получение всех именованных маршрутов
     */
    public function getNamedRoutes(): array
    {
        return $this->namedRoutes;
    }

    /**
     * Получение URL по имени маршрута
     */
    public function route(string $name, array $parameters = []): ?string
    {
        if (isset($this->namedRoutes[$name])) {
            return $this->namedRoutes[$name]->generateUrl($parameters);
        }
        return null;
    }

    /**
     * Запуск роутера
     */
    public function dispatch(string $method, string $uri): void
    {
        $method = strtoupper($method);
        $uri = parse_url($uri, PHP_URL_PATH);
        foreach ($this->routes as $route) {
            if ($route->matches($method, $uri)) {
                $this->executeHandler($route->getHandler(), $route->getParameters());
                return;
            }
        }

        $this->notFound();
    }

    /**
     * Выполнение обработчика
     */
    private function executeHandler($handler, array $parameters): void
    {
        if (is_callable($handler)) {
            call_user_func_array($handler, $parameters);
        } elseif (is_array($handler) && count($handler) === 2) {
            [$controller, $method] = $handler;
            if (is_string($controller) && class_exists($controller)) {
                $instance = new $controller();

                if (method_exists($instance, $method)) {
                    call_user_func_array([$instance, $method], $parameters);
                    return;
                }
            }

            throw new \RuntimeException("Controller method {$method} not found in {$controller}");
        }
        else {
            throw new \RuntimeException('Invalid route handler');
        }
    }

    /**
     * Страница не найдена
     */
    private function notFound(): void
    {
        http_response_code(404);

        $viewPath = __DIR__ . '/../../views/404.php';
        if (file_exists($viewPath)) {
            include $viewPath;
        } else {
            echo "<h1>404 - Страница не найдена</h1>";
            echo "<p>Запрошенный URL не существует</p>";
        }
    }
}