<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Http\Request;
use App\Http\Response;

final class ContactController extends BaseController
{
    public function index(Request $request): Response
    {
        return $this->render('pages/contact', [
            'pageTitle' => $this->routeTitle('/contato'),
            'metaDescription' => 'Canais oficiais de atendimento de Souza Guedes Advogados e endereço da sede na Vila Ema, São Paulo/SP.',
            'path' => '/contato',
            'nav' => 'contato',
            'showCta' => false,
        ]);
    }
}
