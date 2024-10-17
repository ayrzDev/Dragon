<?php

namespace App\Core;

use App\Controllers\UserController;
use App\Config\App;
use InvalidArgumentException;
use App\Core\Security;

class Router
{
    private $routes;
    private $maintenanceMode;

    public function __construct()
    {
        $this->routes = [];
        $this->maintenanceMode = false; // Initialize maintenance mode
    }

    public function enableMaintenanceMode()
    {
        $this->maintenanceMode = true;
    }

    public function disableMaintenanceMode()
    {
        $this->maintenanceMode = false;
    }

    public function addRoute($urls, $handler, $jsFile = null, $authRequired = false, $maintenance = true, $method = 'GET')
    {
        if (!is_array($urls)) {
            $urls = [$urls];
        }
        if (!is_callable($handler)) {
            throw new InvalidArgumentException('Handler must be a callable.');
        }
        foreach ($urls as $url) {
            $this->routes[$url] = [
                'handler' => $handler,
                'method' => strtoupper($method),
                'jsFile' => $jsFile,
                'authRequired' => $authRequired,
                'maintenance' => $maintenance,
                'params' => [], // Params defined here
            ];
        }
    }

    public function dispatch($url, $method)
    {
        $template = App::template;
        $userController = new UserController();

        if (isset($this->routes[$url])) {
            $route = $this->routes[$url];

            // Check HTTP method
            if ($route['method'] !== $method) {
                header($_SERVER["SERVER_PROTOCOL"] . " 405 Method Not Allowed");
                echo "405 Method Not Allowed";
                return;
            }

            // Check if user is logged in if auth is required
            if ($route['authRequired'] && !$userController->getLogged()) {
                header($_SERVER["SERVER_PROTOCOL"] . " 403 Forbidden");
                require_once $_SERVER["DOCUMENT_ROOT"] . '/themes/errors/403.php';
                return;
            }

            // Check maintenance mode
            if ($this->maintenanceMode && $route['maintenance'] && !$userController->isAdmin()) {
                header($_SERVER["SERVER_PROTOCOL"] . " 503 Service Unavailable");
                require_once $_SERVER["DOCUMENT_ROOT"] . '/themes/errors/maintenance.php';
                return;
            }
            // Check and include JavaScript file

            // Handle parameters and call the route handler
            $handler = $route['handler'];
            if (is_array($handler)) {
                $controller = $handler[0];
                $method = $handler[1];
                $params = array_slice($route['params'], 0); // Get parameters
                call_user_func_array([$controller, $method], $params);
            } else {
                call_user_func($handler);
            }
            $jsFile = $route['jsFile'];
            if ($jsFile) {
                $jsFilePath = $_SERVER["DOCUMENT_ROOT"] . '/public/themes/' . $template . '/' . $jsFile . ".js";
                if (file_exists($jsFilePath)) {
                    echo '<script src="/public/themes/' . $template . '/' . $jsFile . '.js"></script>';
                } else {
                    error_log('JS file not found: ' . $jsFilePath); // Hata kaydı
                }
            }
        } else {
            header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found");
            echo "404 Not Found";
        }
    }

    public function handleRequest($requestUrl, $method)
    {
        global $template;

        // CSRF token generation
        $_SESSION["csrf_token"] = Security::generateCsrfToken();

        $matchedRoute = null;
        $params = [];

        foreach ($this->routes as $route => $routeData) {
            $pattern = str_replace('/', '\/', $route);
            $pattern = preg_replace('/\{(\w+)\}/', '([^\/]+)', $pattern);
            $pattern = '/^' . $pattern . '\/?$/';

            if (preg_match($pattern, $requestUrl, $matches) && $routeData['method'] === $method) {
                $matchedRoute = $route;
                $params = array_slice($matches, 1); // Skip the first element, take the rest as parameters
                $this->routes[$route]['params'] = $params; // Store params for later use
                break;
            }
        }

        if ($matchedRoute !== null) {
            $this->dispatch($matchedRoute, $method);
        } else {
            http_response_code(404);
            require_once $_SERVER["DOCUMENT_ROOT"] . '/public/errors/404.php';
        }

        // Clear CSRF token
        unset($_SESSION['csrf_token']);
    }
}
