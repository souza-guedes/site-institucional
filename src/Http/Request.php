<?php

declare(strict_types=1);

namespace App\Http;

/**
 * Abstração imutável de requisição HTTP.
 */
final readonly class Request
{
    /**
     * @param array<string, mixed> $queryParams
     * @param array<string, string> $headers
     */
    public function __construct(
        public string $method,
        public string $uri,
        public array $queryParams = [],
        public array $headers = []
    ) {}

    public static function createFromGlobals(): self
    {
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $uri = $_SERVER['REQUEST_URI'] ?? '/';

        // Se passado via query param no rewrite do .htaccess (?route=...)
        if (isset($_GET['route']) && is_string($_GET['route'])) {
            $uri = '/' . ltrim($_GET['route'], '/');
        }

        $headers = [];
        foreach ($_SERVER as $key => $val) {
            if (str_starts_with($key, 'HTTP_') && is_string($val)) {
                $headerName = str_replace('_', '-', substr($key, 5));
                $headers[strtolower($headerName)] = $val;
            }
        }

        return new self(
            method: strtoupper($method),
            uri: $uri,
            queryParams: $_GET,
            headers: $headers
        );
    }

    /**
     * Retorna o path limpo e normalizado (sem query string e sem trailing slash redundante).
     */
    public function getPath(): string
    {
        $parsed = parse_url($this->uri, PHP_URL_PATH);
        $path = is_string($parsed) ? $parsed : '/';

        // Sanitização básica contra directory traversal
        $path = preg_replace('/(\.\.\/|\.\.\\\)/', '', $path) ?? '/';

        // Normalização: remove trailing slash, exceto se for a raiz '/'
        if ($path !== '/' && str_ends_with($path, '/')) {
            $path = rtrim($path, '/');
        }

        return $path === '' ? '/' : $path;
    }
}
