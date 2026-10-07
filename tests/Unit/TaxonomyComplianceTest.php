<?php

declare(strict_types=1);

namespace Tests\Unit;

if (file_exists(dirname(__DIR__, 2) . '/src/Domain/Taxonomy/TaxonomyValidator.php')) {
    require_once dirname(__DIR__, 2) . '/src/Domain/Taxonomy/TaxonomyValidator.php';
}

use App\Domain\Taxonomy\TaxonomyValidator;

final class TaxonomyComplianceTest
{
    private string $dataFile;

    public function __construct()
    {
        $this->dataFile = dirname(__DIR__, 2) . '/data/sitemap-taxonomy.json';
    }

    public function testClassExists(): void
    {
        if (!class_exists(TaxonomyValidator::class)) {
            throw new \AssertionError("Classe App\Domain\Taxonomy\TaxonomyValidator não encontrada.");
        }
    }

    public function testValidatorRejectsProhibitedTermsInTaxonomy(): void
    {
        $this->testClassExists();

        $invalidData = [
            'id' => 'law-invalid',
            'slug' => 'direito-invalido',
            'title' => 'Especialistas em Imobiliário',
            'sub_disciplines' => ['Causa ganha', 'Resultado garantido'],
            'procedural_tracks' => [
                'judicial' => ['Liminar imediata sem risco'],
                'extrajudicial' => ['Solução mágica']
            ],
            'prohibited_terms' => [],
            'recommended_terms' => []
        ];

        $validator = new TaxonomyValidator();
        $violations = $validator->validateTaxonomyArea($invalidData);

        if (count($violations) === 0) {
            throw new \AssertionError("O TaxonomyValidator falhou ao detectar expressões terminantemente vedadas pela OAB.");
        }
    }

    public function testActualSitemapTaxonomyDatasetIsFullyCompliant(): void
    {
        $this->testClassExists();

        if (!file_exists($this->dataFile)) {
            throw new \AssertionError("Arquivo '{$this->dataFile}' inexistente.");
        }

        $data = json_decode((string) file_get_contents($this->dataFile), true);
        if (!is_array($data)) {
            throw new \AssertionError("JSON inválido em sitemap-taxonomy.json.");
        }

        $validator = new TaxonomyValidator();
        $taxonomy = $data['practice_areas_taxonomy'] ?? [];

        foreach ($taxonomy as $area) {
            $violations = $validator->validateTaxonomyArea($area);
            if (!empty($violations)) {
                $err = implode('; ', $violations);
                throw new \AssertionError("Violações éticas detectadas na disciplina '{$area['id']}': {$err}");
            }
        }
    }
}
