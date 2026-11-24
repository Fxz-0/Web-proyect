<?php

namespace Core;

class Router
{
    private array $routes = [];

    public function get(string $path, callable $callback): void
    {
        $this->add('GET', $path, $callback);
    }

    public function post(string $path, callable $callback): void
    {
        $this->add('POST', $path, $callback);
    }

    public function put(string $path, callable $callback): void
    {
        $this->add('PUT', $path, $callback);
    }

    public function delete(string $path, callable $callback): void
    {
        $this->add('DELETE', $path, $callback);
    }

    private function add(string $method, string $path, callable $callback): void
    {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'callback' => $callback
        ];
    }

    public function dispatch(string $method, string $uri): void
    {
        $path = parse_url($uri, PHP_URL_PATH) ?: '/';

        foreach ($this->routes as $route) {
            if ($route['method'] !== strtoupper($method)) {
                continue;
            }

            $routeParts = explode('/', trim($route['path'], '/'));
            $pathParts = explode('/', trim($path, '/'));

            if (count($routeParts) !== count($pathParts)) {
                continue;
            }

            $params = [];
            $matched = true;

            foreach ($routeParts as $index => $part) {
                if (preg_match('/^\{([A-Za-z][A-Za-z0-9_]*)\}$/', $part, $matches)) {
                    $params[$matches[1]] = $pathParts[$index];
                } elseif ($part !== $pathParts[$index]) {
                    $matched = false;
                    break;
                }
            }

            if ($matched) {
                call_user_func_array($route['callback'], array_values($params));
                return;
            }
        }

        Response::error('Endpoint no encontrado.', 404);
    }
}
