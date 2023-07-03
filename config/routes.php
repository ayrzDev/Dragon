<?php

$routes = [
    '/' => 'HomeController@index',
    '/about-us' => 'HomeController@about',
    '/user' => 'UserController@index',
    '/user/create' => 'UserController@create',
    '/user/edit/{id}' => 'UserController@edit'
];
