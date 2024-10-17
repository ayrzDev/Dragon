<?php

require_once $_SERVER["DOCUMENT_ROOT"]. '/src/Config/config.php';

require_once AUTOLOAD_PATH;

use App\Core\Router;
use App\Controllers\HomeController;
use App\Controllers\AdminController;
use App\Config\App;

$router = new Router();
$homeController = new HomeController();

if (App::globalMaintenanceMode) {
    $router->enableMaintenanceMode();
} else {
    $router->disableMaintenanceMode();
}

require_once ROOT_DIR . '/src/Config/routes.php';

$requestUrl = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$requestMethod = $_SERVER['REQUEST_METHOD'];

$router->handleRequest($requestUrl, $requestMethod);
