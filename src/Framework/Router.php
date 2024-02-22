<?php

declare(strict_types=1);

namespace Framework;

class Router
{
    private array $routes = [];

    public function add(string $method, string $path, array $controller)
    {
        $this->normalizePath($path);

        $this->routes[] = [
            'path' => $path,
            'method' => strtoupper($method),
            'controller' => $controller
        ];
    }

    private function normalizePath(string &$path): string
    {
        $path = trim($path, '/');

        $path = "/{$path}/";

        if ($path == "//")
            $path = "/";

        return $path;
    }

    public function dispatch(string $path, string $method)
    {
        $this->normalizePath($path);
        $method = strtoupper($method);

        foreach ($this->routes as $route) {
            if (
                !preg_match("#^{$route['path']}$#", $path) ||
                $route['method'] !== $method
            ) {
                continue;
            }

            [$class, $function] = $route['controller'];

            $controllerInstance = new $class;
            $controllerInstance->$function();
        }
    }
}
