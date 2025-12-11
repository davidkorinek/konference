<?php
namespace Davca\Konference\Core;

class Router {
    private array $routes = [];

    private string $basePath = '/konference/public';

    public function get($path, $action){
        $this->routes['GET'][$path] = $action;
    }

    public function post($path, $action){
        $this->routes['POST'][$path] = $action;
    }

    private function normalizePath(string $path): string
    {
        // odebere "/konference/public"
        if (str_starts_with($path, $this->basePath)) {
            $path = substr($path, strlen($this->basePath));
        }

        // pokud je vysledek prazdny nebo jen lomitko
        if ($path === '' || $path === false || $path === null) {
            return '/';
        }

        // odstraneni lomitka
        $path = rtrim($path, '/');

        // pokud je vysledek prazdny vrati root
        if ($path === '') {
            return '/';
        }

        return $path;
    }


    public function run(){
        $method = $_SERVER['REQUEST_METHOD'];
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // normalizace cesty
        $path = $this->normalizePath($path);

        if (!isset($this->routes[$method][$path])) {
            http_response_code(404);
            echo "404 Not Found (normalized: $path)";
            return;
        }

        [$controller, $method] = explode('@', $this->routes[$method][$path]);
        $controllerClass = "Davca\\Konference\\Controllers\\$controller";

        $controllerObj = new $controllerClass();
        $controllerObj->$method();
    }
}
