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
            'pageTitle' => $this->routeTitle('/sobre'),
            'metaDescription' => 'Trajetória, missão, visão e princípios éticos do escritório Souza Guedes Advogados.',
            'path' => '/sobre',
            'nav' => 'sobre',
        ]);
    }
}
