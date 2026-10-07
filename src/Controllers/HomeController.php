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
            'pageTitle' => 'Souza Guedes Advogados | Advocacia Cível, Imobiliária, Saúde e Família',
            'metaDescription' => 'Portal institucional do escritório Souza Guedes Advogados. Atendimento técnico, ético e personalizado em São Paulo.'
        ]);
    }
}
