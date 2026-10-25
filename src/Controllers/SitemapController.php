<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Config\AppConfig;
use App\Http\Request;
use App\Http\Response;

/**
 * /sitemap.xml gerado a partir de data/ (WDLC 7.2): as rotas de sitemap-taxonomy.json, as áreas de atuação
 * e os artigos publicados. É uma rota, e não um arquivo, porque o deploy (rsync --delete) sobrescreve public/
 * e porque assim o mapa acompanha os dados sem passo de build.
 */
final class SitemapController extends BaseController
{
    /** Prioridade por rota; as não listadas ficam com 0.7. */
    private const PRIORITY = [
        '/' => '1.0',
        '/areas-de-atuacao' => '0.9',
        '/areas-de-atuacao/{slug}' => '0.9',
        '/privacidade' => '0.3',
    ];

    public function index(Request $request): Response
    {
        $lastmod = (string) ($this->taxonomy['last_updated'] ?? '');
        $urls = [];

        foreach ($this->taxonomy['routes'] ?? [] as $route) {
            $path = (string) $route['path'];
            if ($path === '/areas-de-atuacao/{slug}') {
                foreach ($this->firmProfile['practice_areas'] ?? [] as $area) {
                    $urls[] = [$path, '/areas-de-atuacao/' . $area['slug'], $lastmod];
                }
            } elseif ($path === '/artigos/{slug}') {
                // Rascunhos continuam acessíveis pelo link, mas só entram no mapa depois de publicados.
                foreach ($this->articles['articles'] ?? [] as $article) {
                    if (($article['status'] ?? '') !== 'rascunho') {
                        $urls[] = [$path, '/artigos/' . $article['slug'], (string) ($article['updated_at'] ?? $lastmod)];
                    }
                }
            } elseif (!str_contains($path, '{')) {
                $urls[] = [$path, $path, $lastmod];
            }
        }

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n"
            . '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        foreach ($urls as [$route, $path, $modified]) {
            $xml .= "  <url>\n"
                . '    <loc>' . htmlspecialchars(AppConfig::getCanonicalUrl($path), ENT_XML1) . "</loc>\n"
                . ($modified !== '' ? "    <lastmod>{$modified}</lastmod>\n" : '')
                . '    <priority>' . (self::PRIORITY[$route] ?? '0.7') . "</priority>\n"
                . "  </url>\n";
        }
        $xml .= "</urlset>\n";

        $headers = ['Content-Type' => 'application/xml; charset=UTF-8'];
        if (AppConfig::isTemporaryHost()) {
            $headers['X-Robots-Tag'] = 'noindex, nofollow';
        }

        return new Response($xml, 200, $headers);
    }
}
