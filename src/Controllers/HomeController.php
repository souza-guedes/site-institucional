<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Http\Request;
use App\Http\Response;

final class HomeController extends BaseController
{
    public function index(Request $request): Response
    {
        return $this->render('pages/home', [
            'pageTitle' => $this->routeTitle('/'),
            'metaDescription' => 'Portal institucional do escritório Souza Guedes Advogados, com atuação em Direito Imobiliário, de Saúde, do Consumidor e de Família e Sucessões. Sede na Vila Ema, São Paulo/SP.',
            'path' => '/',
            'nav' => 'inicio',
        ]);
    }
}
