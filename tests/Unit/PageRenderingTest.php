<?php

declare(strict_types=1);

namespace Tests\Unit;

$root = dirname(__DIR__, 2);
require_once $root . '/src/Config/AppConfig.php';
require_once $root . '/src/Http/Request.php';
require_once $root . '/src/Http/Response.php';
require_once $root . '/src/Http/Router.php';
foreach (['Base', 'Home', 'About', 'PracticeArea', 'Contact', 'Error', 'Lawyer', 'Article', 'Privacy'] as $controller) {
    require_once $root . "/src/Controllers/{$controller}Controller.php";
}

use App\Controllers\AboutController;
use App\Controllers\ArticleController;
use App\Controllers\ContactController;
use App\Controllers\ErrorController;
use App\Controllers\HomeController;
use App\Controllers\LawyerController;
use App\Controllers\PracticeAreaController;
use App\Controllers\PrivacyController;
use App\Http\Request;
use App\Http\Response;
use App\Http\Router;

/**
 * Renderização de todas as páginas no servidor (WDLC 5.1): sem warnings, notices nem deprecations,
 * com a estrutura e a identificação profissional exigidas em cada uma.
 */
final class PageRenderingTest
{
    private Router $router;
    private array $firm;
    private array $articles;
    private string $public;

    public function __construct()
    {
        $root = dirname(__DIR__, 2);
        $this->public = $root . '/public';
        $this->firm = json_decode((string) file_get_contents($root . '/data/firm-profile.json'), true, 512, JSON_THROW_ON_ERROR);
        $this->articles = json_decode((string) file_get_contents($root . '/data/articles.json'), true, 512, JSON_THROW_ON_ERROR);

        // Mesmas rotas de public/index.php
        $this->router = (new Router())
            ->get('/', [HomeController::class, 'index'])
            ->get('/sobre', [AboutController::class, 'index'])
            ->get('/areas-de-atuacao', [PracticeAreaController::class, 'index'])
            ->get('/areas-de-atuacao/{slug}', [PracticeAreaController::class, 'detail'])
            ->redirect('/atuacao', '/areas-de-atuacao', 301)
            ->get('/advogados', [LawyerController::class, 'index'])
            ->get('/artigos', [ArticleController::class, 'index'])
            ->get('/artigos/{slug}', [ArticleController::class, 'detail'])
            ->get('/contato', [ContactController::class, 'index'])
            ->get('/privacidade', [PrivacyController::class, 'index'])
            ->setNotFoundHandler([ErrorController::class, 'notFound']);
    }

    /** Despacha a rota com qualquer warning, notice ou deprecation transformado em exceção. */
    private function dispatch(string $path): Response
    {
        set_error_handler(static function (int $level, string $message, string $file, int $line): bool {
            throw new \ErrorException("{$message} ({$file}:{$line})", 0, $level, $file, $line);
        });
        try {
            return $this->router->dispatch(new Request('GET', $path));
        } finally {
            restore_error_handler();
        }
    }

    /** @return array<string, string> rota => seção do menu com aria-current ('' = nenhuma) */
    private function pages(): array
    {
        $pages = [
            '/' => 'inicio',
            '/sobre' => 'sobre',
            '/areas-de-atuacao' => 'areas-de-atuacao',
            '/advogados' => 'advogados',
            '/artigos' => 'artigos',
            '/contato' => 'contato',
            '/privacidade' => '',
        ];
        foreach ($this->firm['practice_areas'] as $area) {
            $pages['/areas-de-atuacao/' . $area['slug']] = 'areas-de-atuacao';
        }
        foreach ($this->articles['articles'] as $article) {
            $pages['/artigos/' . $article['slug']] = 'artigos';
        }
        return $pages;
    }

    private const NAV_HREF = [
        'inicio' => '/', 'sobre' => '/sobre', 'areas-de-atuacao' => '/areas-de-atuacao',
        'advogados' => '/advogados', 'artigos' => '/artigos', 'contato' => '/contato',
    ];

    private function assertCommonStructure(string $path, string $html): void
    {
        if (preg_match_all('/<h1[\s>]/', $html) !== 1) {
            throw new \AssertionError("'{$path}' deve ter exatamente um <h1>.");
        }
        if (!str_contains($html, '<main id="conteudo">') || !str_contains($html, 'href="#conteudo"')) {
            throw new \AssertionError("'{$path}' deve ter <main id=\"conteudo\"> e o link para pular até o conteúdo.");
        }
        foreach ($this->firm['partners'] as $partner) {
            if (!str_contains($html, $partner['name']) || !str_contains($html, $partner['oab_number'])) {
                throw new \AssertionError("'{$path}' deve identificar {$partner['name']} com a inscrição {$partner['oab_number']} (Art. 1º, § 1º).");
            }
        }
        if (!str_contains($html, '205/2021')) {
            throw new \AssertionError("'{$path}' deve trazer o disclaimer do Provimento CFOAB nº 205/2021.");
        }
        if (preg_match('/href="[^"]+\.php"/i', $html)) {
            throw new \AssertionError("'{$path}' expõe link com extensão .php.");
        }
        // "especialista" só pode aparecer no aviso de que área de atuação não é título de especialista (Art. 3º, III)
        $withoutNotice = str_replace('não constitui título de especialista', '', $html);
        if (stripos($withoutNotice, 'especialista') !== false) {
            throw new \AssertionError("'{$path}' usa a palavra 'especialista'.");
        }
        // Todo asset local referenciado existe em public/, inclusive cada candidato dos srcset
        preg_match_all('#(?:src|href)="(/[^"\#?]+\.(?:css|js|svg|webp|png|woff2))#', $html, $assets);
        preg_match_all('#srcset="([^"]+)"#', $html, $srcsets);
        foreach ($srcsets[1] as $srcset) {
            foreach (explode(',', $srcset) as $candidate) {
                $assets[1][] = strtok(trim($candidate), ' ');
            }
        }
        foreach (array_unique($assets[1]) as $asset) {
            if (!file_exists($this->public . $asset)) {
                throw new \AssertionError("'{$path}' referencia '{$asset}', que não existe em public/.");
            }
        }
        // Desempenho (WDLC 6.1): fontes servidas pelo site e imagens com dimensões, para não gerar CLS
        if (preg_match('#fonts\.(googleapis|gstatic)\.com#', $html)) {
            throw new \AssertionError("'{$path}' carrega fontes do Google; elas são servidas pelo próprio site.");
        }
        preg_match_all('#<img\b[^>]*>#', $html, $images);
        foreach ($images[0] as $img) {
            if (!preg_match('#\swidth="\d+"#', $img) || !preg_match('#\sheight="\d+"#', $img)) {
                throw new \AssertionError("'{$path}' tem imagem sem width e height: {$img}");
            }
        }
    }

    public function testEveryPageRendersWithoutWarnings(): void
    {
        foreach ($this->pages() as $path => $section) {
            $response = $this->dispatch($path);
            if ($response->statusCode !== 200) {
                throw new \AssertionError("'{$path}' respondeu {$response->statusCode}.");
            }
            $this->assertCommonStructure($path, $response->content);
        }
    }

    public function testNavigationMarksTheCurrentSection(): void
    {
        foreach ($this->pages() as $path => $section) {
            $html = $this->dispatch($path)->content;
            preg_match('#<nav class="nav"[^>]*>(.*?)</nav>#s', $html, $nav);
            $current = preg_match_all('/aria-current="page"/', $nav[1] ?? '');
            if ($section === '') {
                if ($current !== 0) {
                    throw new \AssertionError("'{$path}' não deveria marcar item do menu.");
                }
                continue;
            }
            $href = self::NAV_HREF[$section];
            if ($current !== 1 || !str_contains($nav[1], "<a href=\"{$href}\" aria-current=\"page\">")) {
                throw new \AssertionError("'{$path}' deve marcar '{$href}' como página atual no menu.");
            }
        }
    }

    public function testUnknownRoutesRenderThe404Page(): void
    {
        foreach (['/pagina-inexistente', '/areas-de-atuacao/direito-tributario', '/artigos/artigo-inexistente'] as $path) {
            $response = $this->dispatch($path);
            if ($response->statusCode !== 404 || !str_contains($response->content, 'Página não encontrada')) {
                throw new \AssertionError("'{$path}' deveria responder 404 com a página de erro (status {$response->statusCode}).");
            }
            $this->assertCommonStructure($path, $response->content);
        }
    }

    public function testNotFoundPageEscapesTheRequestedPath(): void
    {
        $html = $this->dispatch('/<script>alert(1)</script>')->content;
        if (str_contains($html, '<script>alert(1)</script>')) {
            throw new \AssertionError('A página 404 reflete o caminho pedido sem escapar o HTML.');
        }
    }

    public function testLegacyRouteRedirects(): void
    {
        $response = $this->dispatch('/atuacao');
        if ($response->statusCode !== 301 || ($response->headers['Location'] ?? '') !== '/areas-de-atuacao') {
            throw new \AssertionError('/atuacao deve redirecionar (301) para /areas-de-atuacao.');
        }
    }

    public function testContactAnd404HideTheFinalCallToAction(): void
    {
        foreach (['/contato' => false, '/pagina-inexistente' => false, '/sobre' => true] as $path => $expected) {
            $hasCta = str_contains($this->dispatch($path)->content, 'class="cta dark"');
            if ($hasCta !== $expected) {
                throw new \AssertionError("'{$path}' " . ($expected ? 'deve' : 'não deve') . ' exibir o bloco final de atendimento.');
            }
        }
    }

    public function testPrivacyPageShowsTheThreeDocumentsAndTheOfficer(): void
    {
        $html = $this->dispatch('/privacidade')->content;
        foreach (['id="privacidade"', 'id="cookies"', 'id="termos"', 'Lays Regina de Souza', 'LGPD'] as $needle) {
            if (!str_contains($html, $needle)) {
                throw new \AssertionError("/privacidade deve conter '{$needle}'.");
            }
        }
    }

    public function testContactPageHasNoForm(): void
    {
        // O site não tem formulário (issue #15): o contato é só pelos canais oficiais.
        $html = $this->dispatch('/contato')->content;
        if (stripos($html, '<form') !== false) {
            throw new \AssertionError('/contato não deve ter formulário.');
        }
        foreach ($this->firm['contact_channels'] as $channel) {
            if (in_array($channel['channel_type'], ['phone', 'email'], true)
                && !str_contains($html, 'data-copy="' . htmlspecialchars($channel['value'], ENT_QUOTES) . '"')) {
                throw new \AssertionError("/contato deve oferecer o canal '{$channel['label']}'.");
            }
        }
    }

    public function testTemporaryHostIsNotIndexed(): void
    {
        // O endereço de homologação (*.hostingersite.com) sai com noindex; o domínio definitivo, não (WDLC 7.1).
        $previous = $_SERVER['HTTP_HOST'] ?? null;
        try {
            $_SERVER['HTTP_HOST'] = 'xyz-123456.hostingersite.com';
            foreach (['/', '/pagina-inexistente'] as $path) {
                if (($this->dispatch($path)->headers['X-Robots-Tag'] ?? '') !== 'noindex, nofollow') {
                    throw new \AssertionError("'{$path}' no endereço temporário deve sair com X-Robots-Tag: noindex.");
                }
            }
            $_SERVER['HTTP_HOST'] = 'souzaguedesadv.com.br';
            if (isset($this->dispatch('/')->headers['X-Robots-Tag'])) {
                throw new \AssertionError('O domínio definitivo não pode sair com X-Robots-Tag.');
            }
        } finally {
            if ($previous === null) {
                unset($_SERVER['HTTP_HOST']);
            } else {
                $_SERVER['HTTP_HOST'] = $previous;
            }
        }
    }
}
