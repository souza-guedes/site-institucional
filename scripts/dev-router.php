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

if ($path !== '/' && $file !== false && str_starts_with($file, $public) && is_file($file)) {
    return false;
}

require dirname(__DIR__) . '/public/index.php';
