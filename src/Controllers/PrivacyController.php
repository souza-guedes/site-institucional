<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Http\Request;
use App\Http\Response;

final class PrivacyController extends BaseController
{
    public function index(Request $request): Response
    {
        return $this->render('pages/privacy', [
            'pageTitle' => $this->routeTitle('/privacidade'),
            'metaDescription' => 'Política de Privacidade, Política de Cookies e Termos de Uso do site de Souza Guedes Advogados, conforme a LGPD (Lei nº 13.709/2018).',
            'path' => '/privacidade',
        ]);
    }
}
