<?php

declare(strict_types=1);

namespace App\Http;

/**
 * Motor de roteamento nativo em PHP 8.2+.
 * Suporta rotas estáticas e parametrizadas ({slug}).
 */
final class Router
{
    /** @var array<string, array<string, array{handler: callable|array, pattern: string, is_param: bool}>> */
    private array $routes = [
        'GET' => [],
        'POST' => []
    ];

    private mixed $notFoundHandler = null;

    public function get(string $path, callable|array $handler): self
    {
        $normalizedPath = $this->normalizePath($path);
        $isParam = str_contains($path, '{');

        $pattern = $isParam
            ? '#^' . preg_replace('#\{([a-zA-Z0-9_]+)\}#', '(?P<$1>[a-zA-Z0-9_\-]+)', $normalizedPath) . '$#u'
            : $normalizedPath;

        $this->routes['GET'][$normalizedPath] = [
            'handler' => $handler,
            'pattern' => $pattern,
            'is_param' => $isParam
        ];

        return $this;
    }

    public function setNotFoundHandler(callable|array $handler): self
    {
        $this->notFoundHandler = $handler;
        return $this;
    }

    public function redirect(string $from, string $to, int $status = 301): self
    {
        return $this->get($from, function () use ($to, $status): Response {
            return new Response('', $status, ['Location' => $to]);
        });
    }

    public function match(string $method, string $path): ?array
    {
        $method = strtoupper($method);
        if (!isset($this->routes[$method])) {
            return null;
        }

        $normalizedPath = $this->normalizePath($path);

        // 1. Tenta match estático exato
        if (isset($this->routes[$method][$normalizedPath]) && !$this->routes[$method][$normalizedPath]['is_param']) {
            return [
                'handler' => $this->routes[$method][$normalizedPath]['handler'],
                'params' => []
            ];
        }

        // 2. Tenta match parametrizado via regex
        foreach ($this->routes[$method] as $routeData) {
            if ($routeData['is_param']) {
                if (preg_match($routeData['pattern'], $normalizedPath, $matches)) {
                    $params = [];
                    foreach ($matches as $key => $value) {
                        if (is_string($key)) {
                            $params[$key] = $value;
                        }
                    }
                    return [
                        'handler' => $routeData['handler'],
                        'params' => $params
                    ];
                }
            }
        }

        return null;
    }

    public function dispatch(Request $request): Response
    {
        $path = $request->getPath();
        $matched = $this->match($request->method, $path);

        if ($matched !== null) {
            $handler = $matched['handler'];
            $params = $matched['params'];

            return $this->executeHandler($handler, $request, $params);
        }

        if ($this->notFoundHandler !== null) {
            $handler = $this->notFoundHandler;
            return $this->executeHandler($handler, $request, []);
        }

        return new Response('404 Not Found', 404);
    }

    private function executeHandler(callable|array $handler, Request $request, array $params): Response
    {
        if (is_array($handler) && count($handler) === 2) {
            [$class, $method] = $handler;
            $controller = is_object($class) ? $class : new $class();
            $res = $controller->$method($request, $params);
            return $res instanceof Response ? $res : new Response((string) $res, 200);
        }

        $res = $handler($request, $params);
        return $res instanceof Response ? $res : new Response((string) $res, 200);
    }

    private function normalizePath(string $path): string
    {
        $path = trim($path);
        if ($path === '' || $path === '/') {
            return '/';
        }

        $path = '/' . ltrim($path, '/');
        return rtrim($path, '/');
    }
}
