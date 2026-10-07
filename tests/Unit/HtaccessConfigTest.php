<?php

declare(strict_types=1);

namespace Tests\Unit;

final class HtaccessConfigTest
{
    private string $htaccessPath;
    private string $robotsPath;

    public function __construct()
    {
        $this->htaccessPath = dirname(__DIR__, 2) . '/public/.htaccess';
        $this->robotsPath = dirname(__DIR__, 2) . '/public/robots.txt';
    }

    public function testPublicFilesExist(): void
    {
        if (!file_exists($this->htaccessPath)) {
            throw new \AssertionError("O arquivo '{$this->htaccessPath}' não existe.");
        }

        if (!file_exists($this->robotsPath)) {
            throw new \AssertionError("O arquivo '{$this->robotsPath}' não existe.");
        }
    }

    public function testRewriteEngineEnabled(): void
    {
        $content = (string) file_get_contents($this->htaccessPath);
        if (!preg_match('/RewriteEngine\s+On/i', $content)) {
            throw new \AssertionError("O arquivo .htaccess deve conter a diretiva 'RewriteEngine On'.");
        }
    }

    public function testFrontControllerPattern(): void
    {
        $content = (string) file_get_contents($this->htaccessPath);
        if (!str_contains($content, '%{REQUEST_FILENAME} !-f') || !str_contains($content, '%{REQUEST_FILENAME} !-d')) {
            throw new \AssertionError("O .htaccess deve preservar arquivos estáticos e diretórios físicos existentes (!-f e !-d).");
        }

        if (!preg_match('/RewriteRule.*index\.php/i', $content)) {
            throw new \AssertionError("O .htaccess deve redirecionar requisições dinâmicas para o Front Controller 'index.php'.");
        }
    }

    public function testCanonicalRedirectRules(): void
    {
        $content = (string) file_get_contents($this->htaccessPath);

        // Deve conter regra para HTTPS e/ou normalização de trailing slash
        $hasHttpsRule = (bool) preg_match('/HTTPS|%{SERVER_PORT}/i', $content);
        $hasTrailingSlashRule = (bool) preg_match('/RewriteRule.*\[.*R=301.*\]/i', $content);

        if (!$hasHttpsRule) {
            throw new \AssertionError("O .htaccess deve prever regra de redirecionamento canônico para HTTPS.");
        }
        if (!$hasTrailingSlashRule) {
            throw new \AssertionError("O .htaccess deve prever regra de redirecionamento canônico 301 para trailing slash.");
        }
    }

    public function testSecurityProtectionRules(): void
    {
        $content = (string) file_get_contents($this->htaccessPath);

        // Bloqueio de arquivos ocultos e sensíveis
        $blocksHiddenFiles = (bool) preg_match('/\^\.\*/i', $content) || str_contains($content, '<FilesMatch') || str_contains($content, 'RewriteRule ^\.');
        if (!$blocksHiddenFiles) {
            throw new \AssertionError("O .htaccess deve conter regras de bloqueio a arquivos ocultos (.git, .env).");
        }
    }

    public function testRobotsTxtFormat(): void
    {
        $content = (string) file_get_contents($this->robotsPath);
        if (!str_contains($content, 'User-agent:')) {
            throw new \AssertionError("O robots.txt deve conter diretiva 'User-agent:'.");
        }
        if (!str_contains($content, 'sitemap.xml')) {
            throw new \AssertionError("O robots.txt deve apontar para o mapa 'sitemap.xml'.");
        }
    }
}
