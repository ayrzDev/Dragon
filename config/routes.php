<?php

require_once 'core/Router.php';

$routes = [
    '/' => 'HomeController@index',
    '/about-us' => 'HomeController@about',
    '/user' => 'UserController@index',
    '/user/create' => 'UserController@create',
    '/user/edit/{id}' => 'UserController@edit'
];

$router = new Router();

$router->addRoute('/', 'HomeController@index');
$router->addRoute('/about-us', 'HomeController@about');
$router->addRoute('/user/{username}', 'UserController@profile');

// Admin
$router->addRoute('/admin', 'AdminController@index');
