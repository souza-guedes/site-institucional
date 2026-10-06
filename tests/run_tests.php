<?php

declare(strict_types=1);

require_once __DIR__ . '/Unit/FirmProfileSchemaTest.php';
require_once __DIR__ . '/Unit/FirmProfileEntityTest.php';

use Tests\Unit\FirmProfileSchemaTest;
use Tests\Unit\FirmProfileEntityTest;

echo "====================================================\n";
echo " Executando Suíte de Testes: Souza Guedes Advogados \n";
echo " Validação de Contrato Canônico & DTOs - WDLC 1.1   \n";
echo "====================================================\n\n";

$passed = 0;
$failed = 0;
$errors = [];

$testClasses = [
    FirmProfileSchemaTest::class,
    FirmProfileEntityTest::class,
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
