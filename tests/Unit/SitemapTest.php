<?php

declare(strict_types=1);

namespace Tests\Unit;

$root = dirname(__DIR__, 2);
require_once $root . '/src/Config/AppConfig.php';
require_once $root . '/src/Http/Request.php';
require_once $root . '/src/Http/Response.php';
require_once $root . '/src/Controllers/BaseController.php';
require_once $root . '/src/Controllers/SitemapController.php';

use App\Controllers\SitemapController;
use App\Http\Request;
use App\Http\Response;

/**
 * /sitemap.xml (WDLC 7.2): XML válido, com todas as páginas públicas em URL absoluta e sem rascunhos.
 */
final class SitemapTest
{
    private array $firm;
    private array $articles;

    public function __construct()
    {
        $root = dirname(__DIR__, 2);
        $this->firm = json_decode((string) file_get_contents($root . '/data/firm-profile.json'), true, 512, JSON_THROW_ON_ERROR);
        $this->articles = json_decode((string) file_get_contents($root . '/data/articles.json'), true, 512, JSON_THROW_ON_ERROR);
    }

    /** Gera o sitemap como se a requisição viesse do host informado, em HTTPS. */
    private function sitemap(string $host = 'souzaguedes.adv.br'): Response
    {
        $previous = [$_SERVER['HTTP_HOST'] ?? null, $_SERVER['HTTPS'] ?? null];
        $_SERVER['HTTP_HOST'] = $host;
        $_SERVER['HTTPS'] = 'on';
        try {
            return (new SitemapController())->index(new Request('GET', '/sitemap.xml'));
        } finally {
            foreach (['HTTP_HOST' => $previous[0], 'HTTPS' => $previous[1]] as $key => $value) {
                if ($value === null) {
                    unset($_SERVER[$key]);
                } else {
                    $_SERVER[$key] = $value;
                }
            }
        }
    }

    /** @return list<string> */
    private function locations(): array
    {
        $xml = simplexml_load_string($this->sitemap()->content);
        if ($xml === false) {
            throw new \AssertionError('O sitemap não é um XML válido.');
        }
        $xml->registerXPathNamespace('s', 'http://www.sitemaps.org/schemas/sitemap/0.9');
        return array_map('strval', $xml->xpath('//s:url/s:loc'));
    }

    public function testIsXmlWithTheSitemapContentType(): void
    {
        $response = $this->sitemap();
        if ($response->statusCode !== 200 || $response->headers['Content-Type'] !== 'application/xml; charset=UTF-8') {
            throw new \AssertionError('O sitemap deve responder 200 com Content-Type application/xml.');
        }
        if (isset($response->headers['X-Robots-Tag'])) {
            throw new \AssertionError('O sitemap do domínio definitivo não pode sair com X-Robots-Tag.');
        }
    }

    public function testListsEveryPublicPageWithAbsoluteUrls(): void
    {
        $expected = ['https://souzaguedes.adv.br'];
        foreach (['/sobre', '/areas-de-atuacao', '/advogados', '/artigos', '/contato', '/privacidade'] as $path) {
            $expected[] = 'https://souzaguedes.adv.br' . $path;
        }
        foreach ($this->firm['practice_areas'] as $area) {
            $expected[] = 'https://souzaguedes.adv.br/areas-de-atuacao/' . $area['slug'];
        }

        $locations = $this->locations();
        if ($missing = array_diff($expected, $locations)) {
            throw new \AssertionError('Faltam no sitemap: ' . implode(', ', $missing));
        }
        foreach ($locations as $location) {
            if (str_contains($location, '{') || !str_starts_with($location, 'https://souzaguedes.adv.br')) {
                throw new \AssertionError("URL inválida no sitemap: {$location}");
            }
        }
        if (count($locations) !== count(array_unique($locations))) {
            throw new \AssertionError('O sitemap tem URLs repetidas.');
        }
    }

    public function testOnlyPublishedArticlesAreListed(): void
    {
        $locations = $this->locations();
        foreach ($this->articles['articles'] as $article) {
            $listed = in_array('https://souzaguedes.adv.br/artigos/' . $article['slug'], $locations, true);
            if ($listed === ($article['status'] === 'rascunho')) {
                throw new \AssertionError("Artigo '{$article['slug']}' ({$article['status']}): rascunhos ficam fora do sitemap; publicados entram.");
            }
        }
    }

    public function testForgedHostDoesNotChangeTheUrls(): void
    {
        // Issue #31: o sitemap usa o domínio definitivo, seja qual for o Host
        if (str_contains($this->sitemap('evil.example')->content, 'evil.example')) {
            throw new \AssertionError('O Host forjado aparece no sitemap.');
        }
    }

    public function testTemporaryHostIsNotIndexed(): void
    {
        if (($this->sitemap('xyz-123456.hostingersite.com')->headers['X-Robots-Tag'] ?? '') !== 'noindex, nofollow') {
            throw new \AssertionError('O sitemap no endereço temporário deve sair com X-Robots-Tag: noindex.');
        }
    }
}
