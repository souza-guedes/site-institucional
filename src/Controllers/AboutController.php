<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Http\Request;
use App\Http\Response;

final class AboutController extends BaseController
{
    public function index(Request $request): Response
    {
        return $this->render('pages/about', [
            'pageTitle' => 'Sobre o Escritório | Souza Guedes Advogados',
            'metaDescription' => 'Conheça a história, valores e corpo jurídico do escritório Souza Guedes Advogados.'
        ]);
    }
}
