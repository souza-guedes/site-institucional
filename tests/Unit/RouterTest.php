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

use App\Http\Request;
use App\Http\Response;
use App\Http\Router;

final class RouterTest
{
    public function testExactRouteMatching(): void
    {
        $router = new Router();
        $router->get('/', function (Request $req) {
            return new Response('Página Inicial', 200);
        });

        $req = new Request('GET', '/');
        $res = $router->dispatch($req);

        if ($res->statusCode !== 200 || $res->content !== 'Página Inicial') {
            throw new \AssertionError("Falha ao casar rota raiz exata '/' (status: {$res->statusCode}, content: '{$res->content}')");
        }
    }

    public function testStaticCleanRoutes(): void
    {
        $router = new Router();
        $router->get('/sobre', fn(Request $r) => new Response('Sobre', 200));
        $router->get('/areas-de-atuacao', fn(Request $r) => new Response('Áreas', 200));
        $router->get('/contato', fn(Request $r) => new Response('Contato', 200));

        foreach (['/sobre' => 'Sobre', '/areas-de-atuacao' => 'Áreas', '/contato' => 'Contato'] as $path => $expected) {
            $req = new Request('GET', $path);
            $res = $router->dispatch($req);
            if ($res->content !== $expected || $res->statusCode !== 200) {
                throw new \AssertionError("Falha na rota estática '{$path}'");
            }
        }
    }

    public function testParameterizedRouteMatching(): void
    {
        $router = new Router();
        $router->get('/areas-de-atuacao/{slug}', function (Request $req, array $params) {
            return new Response("Área: " . ($params['slug'] ?? ''), 200);
        });

        $req = new Request('GET', '/areas-de-atuacao/direito-imobiliario');
        $res = $router->dispatch($req);

        if ($res->content !== 'Área: direito-imobiliario' || $res->statusCode !== 200) {
            throw new \AssertionError("Falha ao casar rota parametrizada '/areas-de-atuacao/{slug}'. Retorno: '{$res->content}'");
        }
    }

    public function testTrailingSlashNormalization(): void
    {
        $router = new Router();
        $router->get('/sobre', fn(Request $r) => new Response('Sobre', 200));

        // Rota com trailing slash '/sobre/' deve ser normalizada para '/sobre'
        $req = new Request('GET', '/sobre/');
        $res = $router->dispatch($req);

        if ($res->content !== 'Sobre' || $res->statusCode !== 200) {
            throw new \AssertionError("Roteador falhou ao normalizar rota com trailing slash '/sobre/'");
        }
    }

    public function testNotFoundDispatchesCustom404(): void
    {
        $router = new Router();
        $router->get('/', fn(Request $r) => new Response('Home', 200));
        $router->setNotFoundHandler(function (Request $req) {
            return new Response('Página Não Encontrada (404)', 404);
        });

        $req = new Request('GET', '/rota-inexistente-123');
        $res = $router->dispatch($req);

        if ($res->statusCode !== 404) {
            throw new \AssertionError("Rota inexistente deveria responder com status HTTP 404. Recebido: {$res->statusCode}");
        }
        if (!str_contains($res->content, '404')) {
            throw new \AssertionError("Handler 404 não renderizou o conteúdo esperado.");
        }
    }

    public function testRouteSanitization(): void
    {
        $router = new Router();
        $router->setNotFoundHandler(fn(Request $r) => new Response('404', 404));

        // Tentativa de path traversal
        $req = new Request('GET', '/../../../etc/passwd');
        $res = $router->dispatch($req);

        if ($res->statusCode !== 404) {
            throw new \AssertionError("Requisição com Directory Traversal não deve casar rotas válidas (status: {$res->statusCode})");
        }
    }
}
