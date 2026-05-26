<?php

namespace Src\Presentation\Routing;

class Router
{
    private array $routes = [];

    public function get(
        string $path,
        callable $handler,
        array $middlewares = []
    ): void {

        $this->routes['GET'][$path] = [
            'handler' => $handler,
            'middlewares' => $middlewares
        ];
    }

    public function post(
        string $path,
        callable $handler,
        array $middlewares = []
    ): void {

        $this->routes['POST'][$path] = [
            'handler' => $handler,
            'middlewares' => $middlewares
        ];
    }

    public function dispatch(
        string $method,
        string $uri
    ): void {

        $route =
            $this->routes[$method][$uri]
            ?? null;

        if (!$route) {

            http_response_code(404);

            echo "404 Not Found";

            return;
        }

        foreach (
            $route['middlewares']
            as $middleware
        ) {

            $instance =
                new $middleware();

            $allowed =
                $instance->handle();

            if (!$allowed) {
                return;
            }
        }

        call_user_func(
            $route['handler']
        );
    }
}