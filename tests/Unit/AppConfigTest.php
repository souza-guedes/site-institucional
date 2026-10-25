<?php

declare(strict_types=1);

namespace Tests\Unit;

if (file_exists(dirname(__DIR__, 2) . '/src/Config/AppConfig.php')) {
    require_once dirname(__DIR__, 2) . '/src/Config/AppConfig.php';
}

use App\Config\AppConfig;

final class AppConfigTest
{
    public function testClassExists(): void
    {
        if (!class_exists(AppConfig::class)) {
            throw new \AssertionError("Classe App\Config\AppConfig não encontrada.");
        }
    }

    public function testDynamicBaseUrlResolutionWithHttpAndPort(): void
    {
        $_SERVER['HTTP_HOST'] = 'localhost:8080';
        unset($_SERVER['HTTPS']);

        $baseUrl = AppConfig::getBaseUrl();
        if ($baseUrl !== 'http://localhost:8080') {
            throw new \AssertionError("Resolução dinâmica de Base URL falhou para localhost com porta. Recebido: '{$baseUrl}'");
        }
    }

    public function testDynamicBaseUrlResolutionWithHttps(): void
    {
        $_SERVER['HTTP_HOST'] = 'souzaguedes.adv.br';
        $_SERVER['HTTPS'] = 'on';

        $baseUrl = AppConfig::getBaseUrl();
        if ($baseUrl !== 'https://souzaguedes.adv.br') {
            throw new \AssertionError("Resolução dinâmica de Base URL falhou para conexão segura HTTPS. Recebido: '{$baseUrl}'");
        }
    }

    public function testCanonicalUrlGeneration(): void
    {
        $_SERVER['HTTP_HOST'] = 'souzaguedes.adv.br';
        $_SERVER['HTTPS'] = 'on';

        $canonical = AppConfig::getCanonicalUrl('/areas-de-atuacao/direito-imobiliario');
        if ($canonical !== 'https://souzaguedes.adv.br/areas-de-atuacao/direito-imobiliario') {
            throw new \AssertionError("Geração de URL canônica incorreta: '{$canonical}'");
        }
    }

    public function testForgedHostFallsBackToTheProductionDomain(): void
    {
        // Issue #31: o Host vem do cliente; fora do ambiente local a URL base é sempre o domínio definitivo
        $_SERVER['HTTPS'] = 'on';
        foreach (['evil.example', 'www.evil.example', 'souzaguedes.adv.br.evil.example', 'localhost.evil.example',
                  'www.souzaguedes.adv.br', 'xyz-123456.hostingersite.com', 'evil.example:443'] as $host) {
            $_SERVER['HTTP_HOST'] = $host;
            if (AppConfig::getCanonicalUrl('/sobre') !== 'https://souzaguedes.adv.br/sobre') {
                throw new \AssertionError("Host '{$host}' não pode mudar o canonical: " . AppConfig::getCanonicalUrl('/sobre'));
            }
        }
        unset($_SERVER['HTTPS']);
        foreach (['localhost:8080' => 'http://localhost:8080', '127.0.0.1:8091' => 'http://127.0.0.1:8091',
                  '[::1]:8080' => 'http://[::1]:8080', 'site.localhost' => 'http://site.localhost'] as $host => $expected) {
            $_SERVER['HTTP_HOST'] = $host;
            if (AppConfig::getBaseUrl() !== $expected) {
                throw new \AssertionError("Host local '{$host}' deve continuar dinâmico: " . AppConfig::getBaseUrl());
            }
        }
        $_SERVER['HTTP_HOST'] = 'localhost:8080';
    }

    public function testTemporaryHostingerHostIsDetected(): void
    {
        $cases = [
            'xyz-123456.hostingersite.com' => true,
            'XYZ-123456.HOSTINGERSITE.COM:443' => true,
            'souzaguedes.adv.br' => false,
            'hostingersite.com.evil.example' => false,
            'localhost:8080' => false,
        ];
        foreach ($cases as $host => $expected) {
            $_SERVER['HTTP_HOST'] = $host;
            if (AppConfig::isTemporaryHost() !== $expected) {
                throw new \AssertionError("isTemporaryHost() errou para '{$host}'.");
            }
        }
        $_SERVER['HTTP_HOST'] = 'localhost:8080';
    }
}
