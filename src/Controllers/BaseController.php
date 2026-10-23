<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Config\AppConfig;
use App\Http\Response;

require_once dirname(__DIR__) . '/Views/helpers.php';

/**
 * Controlador base: carrega os dados canônicos (data/*.json) e renderiza a página dentro do layout.
 */
abstract class BaseController
{
    protected string $viewsPath;
    protected array $firmProfile;
    protected array $articles;
    protected array $privacyPolicy;
    protected array $taxonomy;

    public function __construct()
    {
        $this->viewsPath = dirname(__DIR__) . '/Views';
        $dataPath = dirname(__DIR__, 2) . '/data';
        $this->firmProfile = $this->loadJson($dataPath . '/firm-profile.json');
        $this->articles = $this->loadJson($dataPath . '/articles.json');
        $this->privacyPolicy = $this->loadJson($dataPath . '/privacy-policy.json');
        $this->taxonomy = $this->loadJson($dataPath . '/sitemap-taxonomy.json');
    }

    private function loadJson(string $path): array
    {
        if (!file_exists($path)) {
            return [];
        }
        $decoded = json_decode((string) file_get_contents($path), true);
        return is_array($decoded) ? $decoded : [];
    }

    /** Título da rota cadastrado em sitemap-taxonomy.json (fonte única dos títulos de página). */
    protected function routeTitle(string $routePath): string
    {
        foreach ($this->taxonomy['routes'] ?? [] as $route) {
            if ($route['path'] === $routePath) {
                return (string) $route['title'];
            }
        }
        return 'Souza Guedes Advogados';
    }

    /**
     * Renderiza src/Views/{$viewName}.php dentro de layouts/default.php.
     *
     * Chaves de $data usadas pelo layout: pageTitle, metaDescription, path (rota canônica),
     * nav (seção ativa do menu) e showCta (bloco "Atendimento" antes do rodapé).
     */
    protected function render(string $viewName, array $data = [], int $status = 200): Response
    {
        $viewFile = $this->viewsPath . '/' . ltrim($viewName, '/') . '.php';
        if (!file_exists($viewFile)) {
            return new Response("View '{$viewName}' não encontrada.", 500);
        }

        \ViewData::$shared = [
            'firm' => $this->firmProfile,
            'articles' => $this->articles['articles'] ?? [],
            'educationalNotice' => $this->articles['educational_notice'] ?? '',
            'privacy' => $this->privacyPolicy,
            'taxonomy' => $this->taxonomy,
            'pageTitle' => $data['pageTitle'] ?? 'Souza Guedes Advogados',
            'metaDescription' => $data['metaDescription'] ?? '',
            'path' => $data['path'] ?? '/',
            'nav' => $data['nav'] ?? '',
            'showCta' => $data['showCta'] ?? true,
        ];

        $content = $this->capture($viewFile, \ViewData::$shared + $data);
        $page = $this->capture($this->viewsPath . '/layouts/default.php', \ViewData::$shared + ['content' => $content]);

        $headers = ['Content-Type' => 'text/html; charset=UTF-8'];
        if (AppConfig::isTemporaryHost()) {
            $headers['X-Robots-Tag'] = 'noindex, nofollow';
        }

        return new Response($page, $status, $headers);
    }

    private function capture(string $__file, array $__vars): string
    {
        extract($__vars);
        ob_start();
        try {
            include $__file;
        } catch (\Throwable $e) {
            ob_end_clean();
            throw $e;
        }
        return (string) ob_get_clean();
    }
}
