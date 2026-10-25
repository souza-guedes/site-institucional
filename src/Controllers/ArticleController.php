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
            'pageTitle' => $this->routeTitle('/artigos'),
            'metaDescription' => 'Artigos informativos e educativos escritos pelos sócios de Souza Guedes Advogados sobre direitos e procedimentos das áreas de atuação do escritório.',
            'path' => '/artigos',
            'nav' => 'artigos',
        ]);
    }

    public function detail(Request $request, array $params): Response
    {
        $slug = (string) ($params['slug'] ?? '');

        foreach ($this->articles['articles'] ?? [] as $article) {
            if (($article['slug'] ?? '') === $slug) {
                return $this->render('pages/article-detail', [
                    'pageTitle' => $article['title'] . ' | Souza Guedes Advogados',
                    'metaDescription' => $article['excerpt'] ?? '',
                    'path' => '/artigos/' . $slug,
                    'ogType' => 'article',
                    'nav' => 'artigos',
                    'article' => $article,
                ]);
            }
        }

        return (new ErrorController())->notFound($request);
    }
}
