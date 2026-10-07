<?php

declare(strict_types=1);

namespace Tests\Unit;

require_once dirname(__DIR__, 2) . '/src/Http/Request.php';
require_once dirname(__DIR__, 2) . '/src/Http/Response.php';
require_once dirname(__DIR__, 2) . '/src/Http/Router.php';
require_once dirname(__DIR__, 2) . '/src/Controllers/BaseController.php';
require_once dirname(__DIR__, 2) . '/src/Controllers/HomeController.php';
require_once dirname(__DIR__, 2) . '/src/Controllers/AboutController.php';
require_once dirname(__DIR__, 2) . '/src/Controllers/PracticeAreaController.php';
require_once dirname(__DIR__, 2) . '/src/Controllers/ContactController.php';
require_once dirname(__DIR__, 2) . '/src/Controllers/ErrorController.php';

if (file_exists(dirname(__DIR__, 2) . '/src/Controllers/LawyerController.php')) {
    require_once dirname(__DIR__, 2) . '/src/Controllers/LawyerController.php';
}
if (file_exists(dirname(__DIR__, 2) . '/src/Controllers/ArticleController.php')) {
    require_once dirname(__DIR__, 2) . '/src/Controllers/ArticleController.php';
}
if (file_exists(dirname(__DIR__, 2) . '/src/Controllers/PrivacyController.php')) {
    require_once dirname(__DIR__, 2) . '/src/Controllers/PrivacyController.php';
}

use App\Http\Request;
use App\Http\Router;
use App\Controllers\HomeController;
use App\Controllers\AboutController;
use App\Controllers\PracticeAreaController;
use App\Controllers\ContactController;
use App\Controllers\ErrorController;
use App\Controllers\LawyerController;
use App\Controllers\ArticleController;
use App\Controllers\PrivacyController;

final class RouterExpandedTest
{
    private function buildRouter(): Router
    {
        $router = new Router();

        $router->get('/', [HomeController::class, 'index']);
        $router->get('/sobre', [AboutController::class, 'index']);
        $router->get('/areas-de-atuacao', [PracticeAreaController::class, 'index']);
        $router->get('/areas-de-atuacao/{slug}', [PracticeAreaController::class, 'detail']);
        $router->get('/contato', [ContactController::class, 'index']);

        // Redirect canônico /atuacao -> /areas-de-atuacao
        $router->redirect('/atuacao', '/areas-de-atuacao', 301);

        if (class_exists(LawyerController::class)) {
            $router->get('/advogados', [LawyerController::class, 'index']);
        }
        if (class_exists(ArticleController::class)) {
            $router->get('/artigos', [ArticleController::class, 'index']);
        }
        if (class_exists(PrivacyController::class)) {
            $router->get('/privacidade', [PrivacyController::class, 'index']);
        }

        $router->setNotFoundHandler([ErrorController::class, 'notFound']);

        return $router;
    }

    public function testRedirectAtuacaoToAreasDeAtuacao(): void
    {
        $router = $this->buildRouter();
        $req = new Request('GET', '/atuacao');
        $res = $router->dispatch($req);

        if ($res->statusCode !== 301) {
            throw new \AssertionError("Esperava-se código HTTP 301 no redirecionamento de '/atuacao'. Recebido: {$res->statusCode}");
        }

        if (($res->headers['Location'] ?? '') !== '/areas-de-atuacao') {
            throw new \AssertionError("Header Location incorreto para o redirecionamento. Recebido: " . ($res->headers['Location'] ?? 'nenhum'));
        }
    }

    public function testExpandedCanonicalRoutesDispatch(): void
    {
        if (!class_exists(LawyerController::class) || !class_exists(ArticleController::class) || !class_exists(PrivacyController::class)) {
            throw new \AssertionError("Novos controladores (LawyerController, ArticleController, PrivacyController) ainda não implementados.");
        }

        $router = $this->buildRouter();

        // 1. Rota /advogados
        $reqLawyers = new Request('GET', '/advogados');
        $resLawyers = $router->dispatch($reqLawyers);
        if ($resLawyers->statusCode !== 200 || !str_contains($resLawyers->content, 'Rodrigo Guedes da Silva')) {
            throw new \AssertionError("Rota '/advogados' falhou no despacho ou na identificação dos advogados.");
        }

        // 2. Rota /artigos
        $reqArticles = new Request('GET', '/artigos');
        $resArticles = $router->dispatch($reqArticles);
        if ($resArticles->statusCode !== 200 || !str_contains($resArticles->content, 'Artigos')) {
            throw new \AssertionError("Rota '/artigos' falhou no despacho.");
        }

        // 3. Rota /privacidade
        $reqPrivacy = new Request('GET', '/privacidade');
        $resPrivacy = $router->dispatch($reqPrivacy);
        if ($resPrivacy->statusCode !== 200 || !str_contains($resPrivacy->content, 'Privacidade')) {
            throw new \AssertionError("Rota '/privacidade' falhou no despacho.");
        }
    }

    public function testInvalidAreaSlugReturns404(): void
    {
        $router = $this->buildRouter();
        $req = new Request('GET', '/areas-de-atuacao/slug-inexistente');
        $res = $router->dispatch($req);

        if ($res->statusCode !== 404) {
            throw new \AssertionError("Slug de área inexistente deve retornar 404. Recebido: {$res->statusCode}");
        }
    }
}
