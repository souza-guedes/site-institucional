<?php

declare(strict_types=1);

/**
 * Roteador do servidor embutido do PHP, só para desenvolvimento (em produção o .htaccess faz esse papel):
 *   php -S localhost:8080 -t public scripts/dev-router.php
 * Arquivos que existem em public/ (CSS, JS, imagens) são servidos direto; o resto vai para o front controller.
 */
$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$file = realpath(dirname(__DIR__) . '/public' . $path);
$public = realpath(dirname(__DIR__) . '/public');

// Texto sai comprimido com gzip, como o mod_deflate do .htaccess faz em produção (WDLC 6.1).
$textTypes = ['css' => 'text/css; charset=UTF-8', 'js' => 'text/javascript; charset=UTF-8', 'svg' => 'image/svg+xml'];

if ($path !== '/' && $file !== false && str_starts_with($file, $public) && is_file($file)) {
    $type = $textTypes[strtolower(pathinfo($file, PATHINFO_EXTENSION))] ?? null;
    if ($type === null) {
        return false;
    }
    header("Content-Type: {$type}");
    ob_start('ob_gzhandler');
    readfile($file);
    return true;
}

ob_start('ob_gzhandler');
require dirname(__DIR__) . '/public/index.php';
