<?php

declare(strict_types=1);

namespace Tests\Unit;

final class ViewsStructureTest
{
    private string $viewsPath;

    public function __construct()
    {
        $this->viewsPath = dirname(__DIR__, 2) . '/src/Views';
    }

    public function testPartialsFilesExist(): void
    {
        $partials = [
            'header.php',
            'navbar.php',
            'footer.php',
            'cookie-banner.php'
        ];

        foreach ($partials as $partial) {
            $path = $this->viewsPath . '/partials/' . $partial;
            if (!file_exists($path)) {
                throw new \AssertionError("Partial obrigatório não encontrado: '{$path}'");
            }
        }
    }

    public function testPagesTemplatesExist(): void
    {
        $pages = [
            'home.php',
            'about.php',
            'practice-areas.php',
            'practice-area-detail.php',
            'contact.php',
            '404.php'
        ];

        foreach ($pages as $page) {
            $path = $this->viewsPath . '/pages/' . $page;
            if (!file_exists($path)) {
                throw new \AssertionError("Template de página obrigatório não encontrado: '{$path}'");
            }
        }
    }

    public function testFooterCompliesWithOABRequirements(): void
    {
        $footerPath = $this->viewsPath . '/partials/footer.php';
        if (!file_exists($footerPath)) {
            throw new \AssertionError("Arquivo '{$footerPath}' inexistente.");
        }

        $content = (string) file_get_contents($footerPath);

        // Nomes dos sócios fundadores e inscrições OAB/SP
        if (!str_contains($content, 'Rodrigo Guedes da Silva') || !str_contains($content, '538.416')) {
            throw new \AssertionError("O footer deve conter o nome completo e OAB/SP do sócio Rodrigo Guedes da Silva.");
        }
        if (!str_contains($content, 'Lays Regina de Souza') || !str_contains($content, '511.204')) {
            throw new \AssertionError("O footer deve conter o nome completo e OAB/SP da sócia Lays Regina de Souza.");
        }

        // Disclaimer ético obrigatório
        if (!str_contains($content, '205/2021')) {
            throw new \AssertionError("O footer deve conter o disclaimer ético regulamentar referenciando o Provimento CFOAB nº 205/2021.");
        }
    }

    public function testNavbarUsesCleanFriendlyRoutes(): void
    {
        $navbarPath = $this->viewsPath . '/partials/navbar.php';
        if (!file_exists($navbarPath)) {
            throw new \AssertionError("Arquivo '{$navbarPath}' inexistente.");
        }

        $content = (string) file_get_contents($navbarPath);

        // Não pode conter links com extensão .php
        if (preg_match('/href=["\'][^"\']+\.php["\']/i', $content)) {
            throw new \AssertionError("A barra de navegação (navbar.php) não deve expor links com terminação '.php'. Utilize rotas limpas.");
        }

        $requiredLinks = ['/', '/sobre', '/areas-de-atuacao', '/contato'];
        foreach ($requiredLinks as $link) {
            if (!str_contains($content, "href=\"{$link}\"") && !str_contains($content, "href='{$link}'")) {
                throw new \AssertionError("Link limpo obrigatório '{$link}' não encontrado na navbar.");
            }
        }
    }

    public function testMainCssAssetExists(): void
    {
        $cssPath = dirname(__DIR__, 2) . '/public/assets/css/main.css';
        if (!file_exists($cssPath)) {
            throw new \AssertionError("Arquivo CSS principal não encontrado em '{$cssPath}'. As páginas não podem ser entregues sem estilização.");
        }

        $cssContent = (string) file_get_contents($cssPath);
        if (trim($cssContent) === '') {
            throw new \AssertionError("Arquivo CSS '{$cssPath}' está vazio.");
        }
    }
}
