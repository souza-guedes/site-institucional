<?php

declare(strict_types=1);

namespace App\Config;

/**
 * Gestão de configurações globais da aplicação.
 * Desacopla o domínio fixo e resolve URLs canônicas dinamicamente com base no ambiente.
 */
final class AppConfig
{
    /**
     * Retorna a URL base do site (ex: https://souzaguedesadv.com.br ou http://localhost:8080)
     */
    public static function getBaseUrl(): string
    {
        $isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
            || (isset($_SERVER['SERVER_PORT']) && (int) $_SERVER['SERVER_PORT'] === 443)
            || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https');

        $scheme = $isHttps ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';

        return "{$scheme}://{$host}";
    }

    /**
     * Retorna a URL canônica absoluta para uma rota específica.
     */
    public static function getCanonicalUrl(string $path = '/'): string
    {
        $normalizedPath = '/' . ltrim($path, '/');
        if ($normalizedPath === '//') {
            $normalizedPath = '/';
        }

        return self::getBaseUrl() . ($normalizedPath === '/' ? '' : $normalizedPath);
    }

    /**
     * Identifica o ambiente de execução da aplicação.
     */
    public static function getEnvironment(): string
    {
        return $_ENV['APP_ENV'] ?? getenv('APP_ENV') ?: 'production';
    }

    /**
     * Verifica se a aplicação está em produção.
     */
    public static function isProduction(): bool
    {
        return self::getEnvironment() === 'production';
    }
}
