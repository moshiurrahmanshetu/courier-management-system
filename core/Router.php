<?php

namespace Core;

class Router {
    protected $routes = [];

    public function add($method, $route, $controller) {
        $this->routes[] = [
            'method' => $method,
            'route' => $route,
            'controller' => $controller
        ];
    }

    public function dispatch($url, $method) {
        $url = parse_url($url, PHP_URL_PATH);
        
        // Remove base path if project is in a subdirectory
        $basePath = parse_url($_ENV['APP_URL'] ?? '', PHP_URL_PATH);
        if ($basePath && strpos($url, $basePath) === 0) {
            $url = substr($url, strlen($basePath));
        }
        
        $url = $url === '' ? '/' : $url;

        foreach ($this->routes as $route) {
            if ($route['method'] === $method && $route['route'] === $url) {
                list($controllerName, $methodName) = explode('@', $route['controller']);
                $controllerClass = "App\\Controllers\\" . $controllerName;
                
                if (class_exists($controllerClass)) {
                    $controller = new $controllerClass();
                    if (method_exists($controller, $methodName)) {
                        return $controller->$methodName();
                    }
                }
            }
        }

        http_response_code(404);
        echo "404 Not Found";
    }
}
