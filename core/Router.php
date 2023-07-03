<?php
// core/Router.php

require_once $_SERVER["DOCUMENT_ROOT"] . '/core/Security.php'; // CSRF koruması için Security sınıfını dahil edin

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
        // CSRF koruması için tokenı oluştur
        Security::generateCsrfToken();

        $matchedRoute = null;
        $params = [];

        foreach ($this->routes as $route => $handler) {
            // Rota için desen oluştur
            $pattern = str_replace('/', '\/', $route);
            $pattern = preg_replace('/\{(\w+)\}/', '([^\/]+)', $pattern);
            $pattern = '/^' . $pattern . '\/?$/'; // Ek olarak / kontrolü yap

            if (preg_match($pattern, $requestUrl, $matches)) {
                $matchedRoute = $route;
                $params = array_slice($matches, 1); // İlk elemanı atla, geri kalanı parametre olarak al
                break;
            }
        }

        if ($matchedRoute !== null) {
            $handler = $this->routes[$matchedRoute];
            $handlerParts = explode('@', $handler);
            $controllerName = $handlerParts[0];
            $methodName = $handlerParts[1];

            require_once $_SERVER["DOCUMENT_ROOT"] . '/app/controllers/' . $controllerName . '.php';

            $controller = new $controllerName();

            if (method_exists($controller, $methodName)) {
                $controller->$methodName(...$params); // Parametreleri değişken olarak geçir
            } else {
                http_response_code(404);
                require_once $_SERVER["DOCUMENT_ROOT"] . '/app/views/errors/404.php';
            }
        } else {
            http_response_code(404);
            require_once $_SERVER["DOCUMENT_ROOT"] . '/app/views/errors/404.php';
        }

        // CSRF tokenını temizle
        unset($_SESSION['csrf_token']);
    }
}
