<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Http\Request;
use App\Http\Response;

final class ArticleController extends BaseController
{
    public function index(Request $request): Response
    {
        return $this->render('pages/articles', [
            'pageTitle' => 'Artigos e Orientações Jurídicas | Souza Guedes Advogados',
            'metaDescription' => 'Artigos e conteúdos informativos sobre Direito Imobiliário, Saúde, Consumidor e Família elaborados por Souza Guedes Advogados.',
            'practiceAreas' => $this->firmProfile['practice_areas'] ?? []
        ]);
    }
}
