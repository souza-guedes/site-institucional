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
            'pageTitle' => 'Contato e Localização | Souza Guedes Advogados',
            'metaDescription' => 'Entre em contato pelos canais institucionais de atendimento de Souza Guedes Advogados. Sede na Vila Ema, São Paulo/SP.',
            'contactChannels' => $this->firmProfile['contact_channels'] ?? []
        ]);
    }
}
