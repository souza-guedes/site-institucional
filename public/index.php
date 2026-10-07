<?php

declare(strict_types=1);

/**
 * Souza Guedes Advogados - Front Controller (Ponto Único de Entrada)
 * WDLC 2.1 - Roteamento amigável e despacho modular
 */

// Autoload simples / requires das classes de domínio e aplicação
$baseDir = dirname(__DIR__);

require_once $baseDir . '/src/Http/Request.php';
require_once $baseDir . '/src/Http/Response.php';
require_once $baseDir . '/src/Http/Router.php';

require_once $baseDir . '/src/Controllers/BaseController.php';
require_once $baseDir . '/src/Controllers/HomeController.php';
require_once $baseDir . '/src/Controllers/AboutController.php';
require_once $baseDir . '/src/Controllers/PracticeAreaController.php';
require_once $baseDir . '/src/Controllers/ContactController.php';
require_once $baseDir . '/src/Controllers/ErrorController.php';

use App\Http\Request;
use App\Http\Router;
use App\Controllers\HomeController;
use App\Controllers\AboutController;
use App\Controllers\PracticeAreaController;
use App\Controllers\ContactController;
use App\Controllers\ErrorController;

// Instanciação e configuração das rotas limpas
$router = new Router();

$router->get('/', [HomeController::class, 'index']);
$router->get('/sobre', [AboutController::class, 'index']);
$router->get('/areas-de-atuacao', [PracticeAreaController::class, 'index']);
$router->get('/areas-de-atuacao/{slug}', [PracticeAreaController::class, 'detail']);
$router->get('/contato', [ContactController::class, 'index']);

// Handler customizado para 404 Not Found
$router->setNotFoundHandler([ErrorController::class, 'notFound']);

// Captura a requisição e despacha a resposta
$request = Request::createFromGlobals();
$response = $router->dispatch($request);
$response->send();
