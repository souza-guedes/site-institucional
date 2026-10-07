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
            'pageTitle' => 'Áreas de Atuação | Souza Guedes Advogados',
            'metaDescription' => 'Conheça as áreas de atuação jurídica de Souza Guedes Advogados: Direito Imobiliário, Saúde, Consumidor, Família e Sucessões.',
            'practiceAreas' => $this->firmProfile['practice_areas'] ?? []
        ]);
    }

    public function detail(Request $request, array $params): Response
    {
        $slug = (string) ($params['slug'] ?? '');
        $areas = $this->firmProfile['practice_areas'] ?? [];

        $foundArea = null;
        foreach ($areas as $area) {
            if (($area['slug'] ?? '') === $slug) {
                $foundArea = $area;
                break;
            }
        }

        if ($foundArea === null) {
            return (new ErrorController())->notFound($request);
        }

        return $this->render('pages/practice-area-detail', [
            'pageTitle' => ($foundArea['title'] ?? 'Área de Atuação') . ' | Souza Guedes Advogados',
            'metaDescription' => $foundArea['summary'] ?? '',
            'area' => $foundArea
        ]);
    }
}
