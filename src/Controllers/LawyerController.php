<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Http\Request;
use App\Http\Response;

final class LawyerController extends BaseController
{
    public function index(Request $request): Response
    {
        $partners = array_map(
            static fn (array $p): string => "{$p['name']} ({$p['oab_number']})",
            $this->firmProfile['partners'] ?? []
        );

        return $this->render('pages/lawyers', [
            'pageTitle' => $this->routeTitle('/advogados'),
            'metaDescription' => 'Sócios fundadores de Souza Guedes Advogados: ' . implode(' e ', $partners) . '.',
            'path' => '/advogados',
            'nav' => 'advogados',
        ]);
    }
}
