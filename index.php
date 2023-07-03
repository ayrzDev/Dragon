<?php

require_once 'config/routes.php';
require_once 'config/app.php';
require_once 'config/database.php';

$requestUrl = $_SERVER['REQUEST_URI'];
$router->handleRequest($requestUrl);
