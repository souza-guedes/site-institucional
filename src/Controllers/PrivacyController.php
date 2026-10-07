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
            'pageTitle' => 'Política de Privacidade & LGPD | Souza Guedes Advogados',
            'metaDescription' => 'Política de Privacidade e Governança de Dados Pessoais do escritório Souza Guedes Advogados, em estrita conformidade com a LGPD (Lei nº 13.709/2018).',
            'dpoEmail' => $this->firmProfile['firm']['contact']['email'] ?? 'souzaguedes.adv@gmail.com'
        ]);
    }
}
