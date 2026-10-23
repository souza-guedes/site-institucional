<?php
/**
 * Cabeçalho fixo com navegação principal e gaveta mobile (abaixo de 1100px).
 * Rótulos e ordem: catálogo WDLC 3.3, "Header e navbar". A gaveta é controlada por site.js.
 */
$menuIconOpen = '<svg class="i-open" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><path d="M3 7h18M3 12h18M3 17h18"/></svg>';
$menuIconClose = '<svg class="i-close" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><path d="M5 5l14 14M19 5L5 19"/></svg>';
?>
<header class="hdr">
    <div class="wrap hdr__in">
        <a class="brand" href="/" aria-label="Souza Guedes Advogados — início">
            <svg class="logo-wm" aria-hidden="true"><use href="/assets/img/logos.svg#logo-wordmark"/></svg>
        </a>
        <nav class="nav" aria-label="Principal">
            <a href="/"<?= nav_current('inicio') ?>>Início</a>
            <a href="/sobre"<?= nav_current('sobre') ?>>O Escritório</a>
            <a href="/areas-de-atuacao"<?= nav_current('areas-de-atuacao') ?>>Áreas de Atuação</a>
            <a href="/advogados"<?= nav_current('advogados') ?>>Corpo Jurídico</a>
            <a href="/artigos"<?= nav_current('artigos') ?>>Artigos</a>
            <a href="/contato"<?= nav_current('contato') ?>>Contato</a>
        </nav>
        <a class="btn btn--primary hdr__cta" href="/contato">Canais de atendimento</a>
        <button class="menu-btn" id="menu-btn" type="button" aria-expanded="false" aria-controls="mnav"><?= $menuIconOpen . $menuIconClose ?>Menu</button>
    </div>
    <div class="mnav" id="mnav">
        <div class="wrap">
            <ul>
                <li><a href="/"<?= nav_current('inicio') ?>>Início</a></li>
                <li><a href="/sobre"<?= nav_current('sobre') ?>>O Escritório</a></li>
                <li><a href="/areas-de-atuacao"<?= nav_current('areas-de-atuacao') ?>>Áreas de Atuação</a></li>
                <li><a href="/advogados"<?= nav_current('advogados') ?>>Corpo Jurídico</a></li>
                <li><a href="/artigos"<?= nav_current('artigos') ?>>Artigos</a></li>
                <li><a href="/contato"<?= nav_current('contato') ?>>Contato</a></li>
            </ul>
            <a class="btn btn--primary" href="/contato">Canais de atendimento</a>
        </div>
    </div>
</header>
<div class="scrim" id="scrim" aria-hidden="true"></div>
