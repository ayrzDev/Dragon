<?php
require_once AUTOLOAD_PATH;

use App\Controllers\HomeController;
use App\Core\Router;
use App\Config\App;

$router = new Router();
$homeController = new HomeController();

if (App::globalMaintenanceMode) {
    $router->enableMaintenanceMode();
} else {
    $router->disableMaintenanceMode();
}

$router->addRoute(['/', "/anasayfa"], [$homeController, 'index'], null, false, false);