<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Http\Request;
use App\Http\Response;

final class LawyerController extends BaseController
{
    public function index(Request $request): Response
    {
        return $this->render('pages/lawyers', [
            'pageTitle' => 'Corpo Jurídico | Souza Guedes Advogados',
            'metaDescription' => 'Conheça os advogados e sócios fundadores de Souza Guedes Advogados: Rodrigo Guedes da Silva (OAB/SP 538.416) e Lays Regina de Souza (OAB/SP 511.204).',
            'partners' => $this->firmProfile['partners'] ?? []
        ]);
    }
}
