<?php

require_once 'config/routes.php';
require_once 'config/app.php';
require_once 'config/database.php';
require_once 'core/Router.php';

$router = new Router();

// Örnek rota tanımları
$router->addRoute('/', 'HomeController@index');
$router->addRoute('/about-us', 'HomeController@about');

$requestUrl = $_SERVER['REQUEST_URI'];
$router->handleRequest($requestUrl);
