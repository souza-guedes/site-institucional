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
