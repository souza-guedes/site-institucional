<?php
use App\Config\AppConfig;

$canonical = AppConfig::getCanonicalUrl($path);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($pageTitle) ?></title>
    <meta name="description" content="<?= e($metaDescription) ?>">
    <link rel="canonical" href="<?= e($canonical) ?>">

    <meta property="og:type" content="website">
    <meta property="og:site_name" content="<?= e($firm['firm']['trade_name'] ?? 'Souza Guedes Advogados') ?>">
    <meta property="og:title" content="<?= e($pageTitle) ?>">
    <meta property="og:description" content="<?= e($metaDescription) ?>">
    <meta property="og:url" content="<?= e($canonical) ?>">
    <meta property="og:locale" content="pt_BR">

    <!-- Fontes pelo Google Fonts: a Política de Privacidade (seção 5) informa esse compartilhamento. -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;500&family=Plus+Jakarta+Sans:wght@500;600;700&family=Source+Sans+3:wght@400;600&display=swap">
    <link rel="stylesheet" href="/assets/css/main.css">
</head>
<body class="site m-0">
