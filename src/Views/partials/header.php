<?php
use App\Config\AppConfig;
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'Souza Guedes Advogados') ?></title>
    <meta name="description" content="<?= htmlspecialchars($metaDescription ?? 'Portal Institucional de Souza Guedes Advogados') ?>">
    <link rel="canonical" href="<?= htmlspecialchars(AppConfig::getCanonicalUrl($_SERVER['REQUEST_URI'] ?? '/')) ?>">
    
    <!-- Meta tags institucionais e Open Graph -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="<?= htmlspecialchars($pageTitle ?? 'Souza Guedes Advogados') ?>">
    <meta property="og:description" content="<?= htmlspecialchars($metaDescription ?? 'Portal Institucional') ?>">
    <meta property="og:locale" content="pt_BR">
    
    <!-- CSS Institucional -->
    <link rel="stylesheet" href="/assets/css/main.css">
</head>
<body class="bg-gray-50 text-gray-900 font-sans antialiased">
