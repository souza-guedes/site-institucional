<?php
use App\Config\AppConfig;

$canonical = AppConfig::getCanonicalUrl($path);
$siteName = $firm['firm']['trade_name'] ?? 'Souza Guedes Advogados';
// Imagem de compartilhamento (WhatsApp, LinkedIn): URL absoluta, gerada por npm run images (WDLC 7.2)
$ogImage = AppConfig::getBaseUrl() . '/assets/img/og-default.jpg';
$ogImageAlt = "Sócios de {$siteName} em frente a prédio histórico";
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($pageTitle) ?></title>
    <meta name="description" content="<?= e($metaDescription) ?>">
    <link rel="canonical" href="<?= e($canonical) ?>">

    <meta property="og:type" content="<?= e($ogType) ?>">
    <meta property="og:site_name" content="<?= e($siteName) ?>">
    <meta property="og:title" content="<?= e($pageTitle) ?>">
    <meta property="og:description" content="<?= e($metaDescription) ?>">
    <meta property="og:url" content="<?= e($canonical) ?>">
    <meta property="og:locale" content="pt_BR">
    <meta property="og:image" content="<?= e($ogImage) ?>">
    <meta property="og:image:type" content="image/jpeg">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:alt" content="<?= e($ogImageAlt) ?>">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= e($pageTitle) ?>">
    <meta name="twitter:description" content="<?= e($metaDescription) ?>">
    <meta name="twitter:image" content="<?= e($ogImage) ?>">
    <meta name="twitter:image:alt" content="<?= e($ogImageAlt) ?>">

    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">

    <!-- Fontes servidas pelo próprio site (resources/css/fonts.css); preload só da fonte do texto, que costuma ser o elemento de LCP. -->
    <link rel="preload" href="/assets/fonts/source-sans-3-latin-400-normal.woff2" as="font" type="font/woff2" crossorigin>
    <link rel="stylesheet" href="<?= e(asset('/assets/css/main.css')) ?>">
</head>
<body class="site m-0">
