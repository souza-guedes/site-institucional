<?php

declare(strict_types=1);

require_once __DIR__ . '/Unit/FirmProfileSchemaTest.php';
require_once __DIR__ . '/Unit/FirmProfileEntityTest.php';
require_once __DIR__ . '/Unit/EthicalComplianceSchemaTest.php';
require_once __DIR__ . '/Unit/EthicalContentValidatorTest.php';
require_once __DIR__ . '/Unit/RouterTest.php';
require_once __DIR__ . '/Unit/FrontControllerExecutionTest.php';
require_once __DIR__ . '/Unit/AppConfigTest.php';
require_once __DIR__ . '/Unit/HtaccessConfigTest.php';
require_once __DIR__ . '/Unit/ViewsStructureTest.php';
require_once __DIR__ . '/Unit/SitemapTaxonomySchemaTest.php';
require_once __DIR__ . '/Unit/TaxonomyEntityTest.php';
require_once __DIR__ . '/Unit/TaxonomyComplianceTest.php';
require_once __DIR__ . '/Unit/RouterExpandedTest.php';

use Tests\Unit\FirmProfileSchemaTest;
use Tests\Unit\FirmProfileEntityTest;
use Tests\Unit\EthicalComplianceSchemaTest;
use Tests\Unit\EthicalContentValidatorTest;
use Tests\Unit\RouterTest;
use Tests\Unit\FrontControllerExecutionTest;
use Tests\Unit\AppConfigTest;
use Tests\Unit\HtaccessConfigTest;
use Tests\Unit\ViewsStructureTest;
use Tests\Unit\SitemapTaxonomySchemaTest;
use Tests\Unit\TaxonomyEntityTest;
use Tests\Unit\TaxonomyComplianceTest;
use Tests\Unit\RouterExpandedTest;

echo "====================================================\n";
echo " Executando Suíte de Testes: Souza Guedes Advogados \n";
echo " Validação de Contrato, Taxonomia & WDLC 2.2        \n";
echo "====================================================\n\n";

$passed = 0;
$failed = 0;
$errors = [];

$testClasses = [
    FirmProfileSchemaTest::class,
    FirmProfileEntityTest::class,
    EthicalComplianceSchemaTest::class,
    EthicalContentValidatorTest::class,
    RouterTest::class,
    FrontControllerExecutionTest::class,
    AppConfigTest::class,
    HtaccessConfigTest::class,
    ViewsStructureTest::class,
    SitemapTaxonomySchemaTest::class,
    TaxonomyEntityTest::class,
    TaxonomyComplianceTest::class,
    RouterExpandedTest::class,
];

foreach ($testClasses as $className) {
    echo "--- Test Suite: {$className} ---\n";
    try {
        $test = new $className();
        $methods = get_class_methods($test);

        foreach ($methods as $method) {
            if (!str_starts_with($method, 'test')) {
                continue;
            }

            try {
                $test->$method();
                echo " [PASS] {$method}\n";
                $passed++;
            } catch (\Throwable $e) {
                echo " [FAIL] {$method}\n";
                $failed++;
                $errors[] = "[{$className}::{$method}] " . $e->getMessage();
            }
        }
    } catch (\Throwable $e) {
        echo " [ERROR NA INICIALIZAÇÃO: {$className}] " . $e->getMessage() . "\n";
        $failed++;
        $errors[] = "[{$className}] " . $e->getMessage();
    }
    echo "\n";
}

echo "----------------------------------------------------\n";
echo "Total de Testes: " . ($passed + $failed) . " | Passou: {$passed} | Falhou: {$failed}\n";
echo "----------------------------------------------------\n";

if ($failed > 0) {
    echo "\nDetalhes das Falhas:\n";
    foreach ($errors as $err) {
        echo " - " . $err . "\n";
    }
    exit(1);
}

echo "\nTodos os contratos de dados e modelos foram validados com 100% de sucesso!\n";
exit(0);
