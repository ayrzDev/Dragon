<?php
// core/Router.php

class Router
{
    private $routes;

    public function __construct()
    {
        $this->routes = [];
    }

    public function addRoute($url, $handler)
    {
        $this->routes[$url] = $handler;
    }

    public function handleRequest($requestUrl)
    {
        if (array_key_exists($requestUrl, $this->routes)) {
            $handler = $this->routes[$requestUrl];
            $handlerParts = explode('@', $handler);
            $controllerName = $handlerParts[0];
            $methodName = $handlerParts[1];

            require_once $_SERVER["DOCUMENT_ROOT"] . '/app/controllers/' . $controllerName . '.php';

            $controller = new $controllerName();
            $controller->$methodName();
        } else {
            http_response_code(404);
            require_once  $_SERVER["DOCUMENT_ROOT"] . '/app/views/errors/404.php';
        }
    }
}
