<?php

declare(strict_types=1);

namespace Tests\Unit;

final class HtaccessConfigTest
{
    private string $rootHtaccessPath;
    private string $htaccessPath;
    private string $robotsPath;

    public function __construct()
    {
        $this->rootHtaccessPath = dirname(__DIR__, 2) . '/.htaccess';
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

    public function testRootHtaccessFileProtectsInternalDirectories(): void
    {
        if (!file_exists($this->rootHtaccessPath)) {
            throw new \AssertionError("O arquivo .htaccess na raiz do repositório '{$this->rootHtaccessPath}' não existe.");
        }

        $content = (string) file_get_contents($this->rootHtaccessPath);
        if (!str_contains($content, 'RewriteEngine On')) {
            throw new \AssertionError("O .htaccess da raiz deve ativar o RewriteEngine On.");
        }

        // Deve proteger diretórios sensíveis e despachar para public/
        if (!str_contains($content, 'public/') && !str_contains($content, 'public/$1')) {
            throw new \AssertionError("O .htaccess da raiz deve reescrever requisições para o diretório public/.");
        }

        if (!str_contains($content, 'src') || !str_contains($content, 'data') || !str_contains($content, 'schemas')) {
            throw new \AssertionError("O .htaccess da raiz deve bloquear expressamente o acesso direto a src/, data/ e schemas/.");
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

        $hasHttpsRule = (bool) preg_match('/HTTPS|%{SERVER_PORT}/i', $content);
        $hasTrailingSlashRule = (bool) preg_match('/RewriteRule.*\[.*R=301.*\]/i', $content);

        if (!$hasHttpsRule) {
            throw new \AssertionError("O .htaccess deve prever regra de redirecionamento canônico para HTTPS.");
        }
        if (!$hasTrailingSlashRule) {
            throw new \AssertionError("O .htaccess deve prever regra de redirecionamento canônico 301 para trailing slash.");
        }
    }

    public function testPublicHtaccessSupportsLocalhostPortInHttpsRule(): void
    {
        $content = (string) file_get_contents($this->htaccessPath);
        // Exceção de HTTPS deve contemplar localhost com porta (ex: :[0-9]+)
        if (!preg_match('/localhost.*:\[0-9\]\+/i', $content) && !preg_match('/localhost\(:\[0-9\]\+\)\?/i', $content)) {
            throw new \AssertionError("A regra de HTTPS do public/.htaccess deve ignorar localhost com portas locais (ex: localhost:8080).");
        }
    }

    public function testPublicHtaccessTrailingSlashHasQsaFlag(): void
    {
        $content = (string) file_get_contents($this->htaccessPath);
        // Regra de trailing slash deve conter QSA para não perder query strings
        if (!preg_match('/RewriteRule\s+\^\(\[\^\/\]\.\*\)\/\$\s+\/\$1\s+\[.*QSA.*\]/i', $content)) {
            throw new \AssertionError("A regra de normalização de trailing slash deve conter a flag QSA para preservar query parameters.");
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
        if (!str_contains($content, 'Sitemap: https://souzaguedes.adv.br/sitemap.xml')) {
            throw new \AssertionError("O robots.txt deve apontar para https://souzaguedes.adv.br/sitemap.xml.");
        }
    }

    public function testWwwRedirectsToApexOverHttps(): void
    {
        // WDLC 7.2: www.souzaguedes.adv.br responde 301 para o domínio sem www, evitando conteúdo duplicado
        $content = (string) file_get_contents($this->htaccessPath);
        // Destino fixo (issue #31): www.<outro-site> não pode ser redirecionado para <outro-site>
        if (!str_contains($content, 'RewriteCond %{HTTP_HOST} ^www\.souzaguedes\.adv\.br$ [NC]')
            || !str_contains($content, 'RewriteRule ^(.*)$ https://souzaguedes.adv.br/$1 [L,R=301]')) {
            throw new \AssertionError("O .htaccess deve redirecionar só www.souzaguedes.adv.br para https://souzaguedes.adv.br.");
        }
        if (str_contains($content, 'https://%1')) {
            throw new \AssertionError("Nenhum redirect do .htaccess pode montar o destino com o Host da requisição.");
        }
    }

    public function testTrailingSlashRuleIsNotAnOpenRedirect(): void
    {
        // Issue #31: com //evil.example/ o destino não pode sair //evil.example (relativo ao protocolo)
        $content = (string) file_get_contents($this->htaccessPath);
        if (!preg_match('#^\s*RewriteRule\s+(\S+)\s+/\$1\s+\[[^\]]*R=301#m', $content, $rule)) {
            throw new \AssertionError("Regra de barra final não encontrada no .htaccess.");
        }
        $pattern = '#' . $rule[1] . '#';
        foreach (['/evil.example/' => false, '//evil.example/' => false, 'sobre/' => true, 'artigos/x/' => true] as $path => $expected) {
            if ((bool) preg_match($pattern, $path) !== $expected) {
                throw new \AssertionError("A regra de barra final " . ($expected ? 'deveria' : 'não pode') . " casar com '{$path}'.");
            }
        }
    }

    public function testPhpVersionIsNotExposed(): void
    {
        $content = (string) file_get_contents($this->htaccessPath);
        if (!str_contains($content, 'Header always unset X-Powered-By')) {
            throw new \AssertionError("O .htaccess deve remover o cabeçalho X-Powered-By.");
        }
        $index = (string) file_get_contents(dirname($this->htaccessPath) . '/index.php');
        if (!str_contains($index, "header_remove('X-Powered-By');")) {
            throw new \AssertionError("public/index.php deve remover o cabeçalho X-Powered-By.");
        }
    }

    public function testHstsHeaderOnlyOverHttps(): void
    {
        $content = (string) file_get_contents($this->htaccessPath);
        if (!preg_match('/Header always set Strict-Transport-Security "max-age=\d+" "expr=%\{HTTPS\} == \'on\'"/', $content)) {
            throw new \AssertionError("O .htaccess deve enviar Strict-Transport-Security apenas em respostas HTTPS.");
        }
    }
}
