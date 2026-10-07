<?php

declare(strict_types=1);

namespace Tests\Unit;

if (file_exists(dirname(__DIR__, 2) . '/src/Domain/Entities/TaxonomyArea.php')) {
    require_once dirname(__DIR__, 2) . '/src/Domain/Entities/TaxonomyArea.php';
}
if (file_exists(dirname(__DIR__, 2) . '/src/Domain/Entities/SitemapRoute.php')) {
    require_once dirname(__DIR__, 2) . '/src/Domain/Entities/SitemapRoute.php';
}

use App\Domain\Entities\TaxonomyArea;
use App\Domain\Entities\SitemapRoute;

final class TaxonomyEntityTest
{
    public function testClassesExist(): void
    {
        if (!class_exists(TaxonomyArea::class)) {
            throw new \AssertionError("Classe App\Domain\Entities\TaxonomyArea não encontrada.");
        }
        if (!class_exists(SitemapRoute::class)) {
            throw new \AssertionError("Classe App\Domain\Entities\SitemapRoute não encontrada.");
        }
    }

    public function testTaxonomyAreaInstantiation(): void
    {
        $this->testClassesExist();

        $data = [
            'id' => 'real-estate-law',
            'slug' => 'direito-imobiliario',
            'title' => 'Direito Imobiliário',
            'target_audience' => ['Proprietários', 'Empresas'],
            'sub_disciplines' => ['Usucapião', 'Despejo', 'Posse'],
            'procedural_tracks' => [
                'judicial' => ['Ação de Usucapião', 'Ação de Despejo'],
                'extrajudicial' => ['Usucapião em Cartório', 'Notificação premonitória']
            ],
            'prohibited_terms' => ['Imóvel garantido', 'Despejo em 24h'],
            'recommended_terms' => ['Análise de regularidade registral', 'Ações possessórias']
        ];

        $area = new TaxonomyArea(
            id: $data['id'],
            slug: $data['slug'],
            title: $data['title'],
            targetAudience: $data['target_audience'],
            subDisciplines: $data['sub_disciplines'],
            proceduralTracks: $data['procedural_tracks'],
            prohibitedTerms: $data['prohibited_terms'],
            recommendedTerms: $data['recommended_terms']
        );

        if ($area->id !== 'real-estate-law' || $area->slug !== 'direito-imobiliario') {
            throw new \AssertionError("Propriedades id/slug incorretas na entidade TaxonomyArea.");
        }
        if (!$area->hasProceduralTrack('judicial') || !$area->hasProceduralTrack('extrajudicial')) {
            throw new \AssertionError("Falha na verificação de ritos processuais em TaxonomyArea.");
        }
        if (count($area->subDisciplines) !== 3) {
            throw new \AssertionError("Contagem incorreta de sub-disciplinas.");
        }
    }

    public function testSitemapRouteInstantiation(): void
    {
        $this->testClassesExist();

        $route = new SitemapRoute(
            path: '/advogados',
            controller: 'App\Controllers\LawyerController',
            action: 'index',
            title: 'Corpo Jurídico | Souza Guedes Advogados',
            navVisible: true,
            complianceNotes: 'Divulgação estrita nos termos do Art. 1º, § 1º do Prov. 205/2021'
        );

        if ($route->path !== '/advogados' || $route->action !== 'index') {
            throw new \AssertionError("Propriedades incorretas na entidade SitemapRoute.");
        }
        if (!$route->navVisible) {
            throw new \AssertionError("Visibilidade na navbar deve ser verdadeira para /advogados.");
        }
    }
}
