<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Http\Request;
use App\Http\Response;

final class ErrorController extends BaseController
{
    public function notFound(Request $request): Response
    {
        return $this->render('pages/404', [
            'pageTitle' => 'Página Não Encontrada (404) | Souza Guedes Advogados',
            'metaDescription' => 'O endereço solicitado não foi encontrado no portal institucional de Souza Guedes Advogados.'
        ], 404);
    }
}
