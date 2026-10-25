<?php

declare(strict_types=1);

namespace App\Config;

/**
 * Gestão de configurações globais da aplicação.
 * Desacopla o domínio fixo e resolve URLs canônicas dinamicamente com base no ambiente.
 */
final class AppConfig
{
    /** Domínio definitivo: base do canonical, do og:url e do sitemap fora do ambiente local (issue #31). */
    public const PRODUCTION_URL = 'https://souzaguedes.adv.br';

    /**
     * Retorna a URL base do site (ex: https://souzaguedes.adv.br ou http://localhost:8080).
     *
     * O cabeçalho Host vem do cliente: usá-lo nas URLs absolutas deixaria quem o forja controlar o canonical
     * e o og:url (envenenamento de cache e de SEO). Por isso só o desenvolvimento local usa o host da
     * requisição; qualquer outro host, inclusive o temporário *.hostingersite.com, recebe PRODUCTION_URL.
     */
    public static function getBaseUrl(): string
    {
        $host = (string) ($_SERVER['HTTP_HOST'] ?? 'localhost');
        if (!self::isLocalHost($host)) {
            return self::PRODUCTION_URL;
        }

        $isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
            || (isset($_SERVER['SERVER_PORT']) && (int) $_SERVER['SERVER_PORT'] === 443)
            || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https');

        $scheme = $isHttps ? 'https' : 'http';

        return "{$scheme}://{$host}";
    }

    /** localhost, 127.0.0.1, [::1] ou *.localhost, com porta opcional. */
    private static function isLocalHost(string $host): bool
    {
        return (bool) preg_match('/^(localhost|[a-z0-9-]+(\.[a-z0-9-]+)*\.localhost|127\.0\.0\.1|\[::1\])(:\d+)?$/i', $host);
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
     * Endereço temporário de homologação da Hostinger (*.hostingersite.com), que não deve ser indexado
     * pelos buscadores: evita conteúdo duplicado quando o domínio definitivo entrar (WDLC 7.1).
     */
    public static function isTemporaryHost(): bool
    {
        $host = strtolower(explode(':', (string) ($_SERVER['HTTP_HOST'] ?? ''))[0]);
        return str_ends_with($host, '.hostingersite.com');
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
