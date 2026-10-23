<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Http\Request;
use App\Http\Response;

final class PracticeAreaController extends BaseController
{
    public function index(Request $request): Response
    {
        return $this->render('pages/practice-areas', [
            'pageTitle' => $this->routeTitle('/areas-de-atuacao'),
            'metaDescription' => 'Áreas de atuação de Souza Guedes Advogados: Direito Imobiliário, Direito de Saúde, Direito do Consumidor e Direito de Família e Sucessões.',
            'path' => '/areas-de-atuacao',
            'nav' => 'areas-de-atuacao',
        ]);
    }

    public function detail(Request $request, array $params): Response
    {
        $slug = (string) ($params['slug'] ?? '');

        foreach ($this->firmProfile['practice_areas'] ?? [] as $area) {
            if (($area['slug'] ?? '') === $slug) {
                return $this->render('pages/practice-area-detail', [
                    'pageTitle' => $area['title'] . ' | Souza Guedes Advogados',
                    'metaDescription' => $area['summary'] ?? '',
                    'path' => '/areas-de-atuacao/' . $slug,
                    'nav' => 'areas-de-atuacao',
                    'area' => $area,
                ]);
            }
        }

        return (new ErrorController())->notFound($request);
    }
}
