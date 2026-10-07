<?php

declare(strict_types=1);

namespace Tests\Unit;

if (file_exists(dirname(__DIR__, 2) . '/src/Http/Request.php')) {
    require_once dirname(__DIR__, 2) . '/src/Http/Request.php';
}
if (file_exists(dirname(__DIR__, 2) . '/src/Http/Response.php')) {
    require_once dirname(__DIR__, 2) . '/src/Http/Response.php';
}
if (file_exists(dirname(__DIR__, 2) . '/src/Http/Router.php')) {
    require_once dirname(__DIR__, 2) . '/src/Http/Router.php';
}
if (file_exists(dirname(__DIR__, 2) . '/src/Controllers/BaseController.php')) {
    require_once dirname(__DIR__, 2) . '/src/Controllers/BaseController.php';
    require_once dirname(__DIR__, 2) . '/src/Controllers/HomeController.php';
    require_once dirname(__DIR__, 2) . '/src/Controllers/AboutController.php';
    require_once dirname(__DIR__, 2) . '/src/Controllers/PracticeAreaController.php';
    require_once dirname(__DIR__, 2) . '/src/Controllers/ContactController.php';
    require_once dirname(__DIR__, 2) . '/src/Controllers/ErrorController.php';
}

use App\Http\Request;
use App\Http\Router;
use App\Controllers\HomeController;
use App\Controllers\AboutController;
use App\Controllers\PracticeAreaController;
use App\Controllers\ContactController;
use App\Controllers\ErrorController;

final class FrontControllerExecutionTest
{
    public function testRouterAcceptsArrayForNotFoundHandler(): void
    {
        $router = new Router();
        
        // Esta linha exatamente causava TypeError se não aceitar array com classe e método
        $router->setNotFoundHandler([ErrorController::class, 'notFound']);

        $req = new Request('GET', '/endereco-inexistente');
        $res = $router->dispatch($req);

        if ($res->statusCode !== 404) {
            throw new \AssertionError("Esperava-se status 404 para rota inexistente. Recebido: {$res->statusCode}");
        }
        if (!str_contains($res->content, 'Página Não Encontrada')) {
            throw new \AssertionError("Conteúdo da página 404 não foi renderizado pelo ErrorController.");
        }
    }

    public function testFrontControllerBootstrapFlow(): void
    {
        $router = new Router();

        $router->get('/', [HomeController::class, 'index']);
        $router->get('/sobre', [AboutController::class, 'index']);
        $router->get('/areas-de-atuacao', [PracticeAreaController::class, 'index']);
        $router->get('/areas-de-atuacao/{slug}', [PracticeAreaController::class, 'detail']);
        $router->get('/contato', [ContactController::class, 'index']);
        $router->setNotFoundHandler([ErrorController::class, 'notFound']);

        // 1. Testa rota inicial '/'
        $reqHome = new Request('GET', '/');
        $resHome = $router->dispatch($reqHome);
        if ($resHome->statusCode !== 200 || !str_contains($resHome->content, 'Souza Guedes Advogados')) {
            throw new \AssertionError("Rota '/' falhou no bootstrap do front controller (status: {$resHome->statusCode})");
        }

        // 2. Testa rota de detalhe de área
        $reqArea = new Request('GET', '/areas-de-atuacao/direito-imobiliario');
        $resArea = $router->dispatch($reqArea);
        if ($resArea->statusCode !== 200 || !str_contains($resArea->content, 'Direito Imobiliário')) {
            throw new \AssertionError("Rota '/areas-de-atuacao/direito-imobiliario' falhou (status: {$resArea->statusCode})");
        }

        // 3. Testa rota 404
        $req404 = new Request('GET', '/area-inexistente-xyz');
        $res404 = $router->dispatch($req404);
        if ($res404->statusCode !== 404) {
            throw new \AssertionError("Rota inexistente falhou ao retornar 404 (status: {$res404->statusCode})");
        }
    }

    public function testRequestDoesNotAllowRouteSpoofingViaQueryParam(): void
    {
        // Simula ataque onde o cliente envia GET /sobre?route=admin
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/sobre?route=admin';
        $_GET['route'] = 'admin';

        $req = Request::createFromGlobals();
        $path = $req->getPath();

        if ($path !== '/sobre') {
            throw new \AssertionError("Vulnerabilidade de spoofing detectada: o path foi sobrescrito para '{$path}' pelo parâmetro ?route=admin em vez de '/sobre'.");
        }
    }
}
