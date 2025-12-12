<?php
namespace Davca\Konference\Core;

// smerovaci system pouzivany v MVC aplikaci
class Router {
    // registr vsech definovanych cest
    private array $routes = [];

    // zakladni url prefix
    private string $basePath = '/konference/public';

    // registruje GET routu
    public function get($path, $action){
        $this->routes['GET'][$path] = $action;
    }

    // registruje POST routu
    public function post($path, $action){
        $this->routes['POST'][$path] = $action;
    }

    // normailzuje cestu tak aby odpovidala definovanym routam
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


    // spusti router
    public function run(){
        // HTTP metoda (GET/POST)
        $method = $_SERVER['REQUEST_METHOD'];
        //cesta z URL bez parametru
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // normalizace cesty
        $path = $this->normalizePath($path);

        // pokud neexistuje 404
        if (!isset($this->routes[$method][$path])) {
            http_response_code(404);
            echo "404 Not Found (normalized: $path)";
            return;
        }

        //Controller@method
        [$controller, $method] = explode('@', $this->routes[$method][$path]);

        // full namespace controlleru
        $controllerClass = "Davca\\Konference\\Controllers\\$controller";

        // instance controlleru
        $controllerObj = new $controllerClass();

        // zavola metodu controlleru
        $controllerObj->$method();
    }
}
