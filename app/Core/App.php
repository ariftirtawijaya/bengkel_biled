<?php
declare(strict_types=1);

class App
{
    private string $controller = 'HomeController';
    private string $method = 'index';
    private array $params = [];

    public function __construct()
    {
        $url = $this->parseUrl();

        if (isset($url[0]) && $url[0] !== '') {
            $candidateController = ucfirst($url[0]) . 'Controller';

            if (class_exists($candidateController)) {
                $this->controller = $candidateController;
                unset($url[0]);
            }
        }

        $controllerObject = new $this->controller();

        if (isset($url[1]) && method_exists($controllerObject, $url[1])) {
            $this->method = $url[1];
            unset($url[1]);
        }

        $this->params = $url ? array_values($url) : [];

        call_user_func_array([$controllerObject, $this->method], $this->params);
    }

    private function parseUrl(): array
    {
        if (isset($_GET['url'])) {
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            return explode('/', $url);
        }

        return [];
    }
}