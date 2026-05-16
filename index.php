<?php

use App\Router\Route;
use App\Router\Router;

require __DIR__ . '/vendor/autoload.php';

$router = new Router();
Route::setRouter($router);
global $router;

require __DIR__ . '/config/routes.php';

foreach ($router->getRoutes() as $route) {
    if ($route->getName()) {
        $router->registerNamedRoute($route);
    }
}

$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);