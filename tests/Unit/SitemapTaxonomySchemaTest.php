<?php

declare(strict_types=1);

namespace Tests\Unit;

final class SitemapTaxonomySchemaTest
{
    private string $schemaFile;
    private string $dataFile;

    public function __construct()
    {
        $this->schemaFile = dirname(__DIR__, 2) . '/schemas/sitemap-taxonomy.schema.json';
        $this->dataFile = dirname(__DIR__, 2) . '/data/sitemap-taxonomy.json';
    }

    public function testFilesExist(): void
    {
        if (!file_exists($this->schemaFile)) {
            throw new \AssertionError("Arquivo de schema '{$this->schemaFile}' não encontrado.");
        }
        if (!file_exists($this->dataFile)) {
            throw new \AssertionError("Arquivo de dados '{$this->dataFile}' não encontrado.");
        }
    }

    public function testRequiredRootKeysExist(): void
    {
        $this->testFilesExist();

        $data = json_decode((string) file_get_contents($this->dataFile), true);
        if (!is_array($data)) {
            throw new \AssertionError("O arquivo sitemap-taxonomy.json contém JSON inválido.");
        }

        $requiredKeys = ['version', 'last_updated', 'routes', 'practice_areas_taxonomy', 'redirects'];
        foreach ($requiredKeys as $key) {
            if (!array_key_exists($key, $data)) {
                throw new \AssertionError("Chave obrigatória '{$key}' ausente em sitemap-taxonomy.json.");
            }
        }
    }

    public function testAllCanonicalRoutesAreRegistered(): void
    {
        $this->testFilesExist();

        $data = json_decode((string) file_get_contents($this->dataFile), true);
        $routes = $data['routes'] ?? [];

        $expectedPaths = [
            '/',
            '/sobre',
            '/areas-de-atuacao',
            '/areas-de-atuacao/{slug}',
            '/advogados',
            '/artigos',
            '/contato',
            '/privacidade'
        ];

        $registeredPaths = array_column($routes, 'path');

        foreach ($expectedPaths as $path) {
            if (!in_array($path, $registeredPaths, true)) {
                throw new \AssertionError("Rota canônica obrigatória '{$path}' não registrada no sitemap.");
            }
        }
    }

    public function testRedirectsContainAtuacaoAlias(): void
    {
        $this->testFilesExist();

        $data = json_decode((string) file_get_contents($this->dataFile), true);
        $redirects = $data['redirects'] ?? [];

        $hasAtuacaoRedirect = false;
        foreach ($redirects as $redirect) {
            if (($redirect['from'] ?? '') === '/atuacao' && ($redirect['to'] ?? '') === '/areas-de-atuacao') {
                $hasAtuacaoRedirect = true;
                if (($redirect['status'] ?? 0) !== 301) {
                    throw new \AssertionError("O redirecionamento de '/atuacao' deve possuir status HTTP 301.");
                }
            }
        }

        if (!$hasAtuacaoRedirect) {
            throw new \AssertionError("Redirecionamento canônico de '/atuacao' para '/areas-de-atuacao' ausente em sitemap-taxonomy.json.");
        }
    }

    public function testPracticeAreasTaxonomyCompleteness(): void
    {
        $this->testFilesExist();

        $data = json_decode((string) file_get_contents($this->dataFile), true);
        $taxonomy = $data['practice_areas_taxonomy'] ?? [];

        $expectedAreas = [
            'real-estate-law' => 'direito-imobiliario',
            'health-law' => 'direito-de-saude',
            'consumer-law' => 'direito-do-consumidor',
            'family-successions' => 'familia-e-sucessoes'
        ];

        if (count($taxonomy) !== 4) {
            throw new \AssertionError("A taxonomia deve conter exatamente 4 áreas de atuação. Encontrado: " . count($taxonomy));
        }

        $mappedSlugs = [];
        foreach ($taxonomy as $area) {
            $id = $area['id'] ?? '';
            $slug = $area['slug'] ?? '';
            $mappedSlugs[$id] = $slug;

            $requiredAreaFields = [
                'id', 'slug', 'title', 'target_audience', 'sub_disciplines',
                'procedural_tracks', 'prohibited_terms', 'recommended_terms'
            ];

            foreach ($requiredAreaFields as $field) {
                if (!array_key_exists($field, $area)) {
                    throw new \AssertionError("Campo obrigatório '{$field}' ausente na disciplina '{$id}'.");
                }
            }

            // Validação dos ritos processuais
            $tracks = $area['procedural_tracks'] ?? [];
            if (!isset($tracks['judicial']) || !isset($tracks['extrajudicial'])) {
                throw new \AssertionError("A disciplina '{$id}' deve prever ritos judicial e extrajudicial.");
            }
        }

        foreach ($expectedAreas as $expectedId => $expectedSlug) {
            if (!isset($mappedSlugs[$expectedId]) || $mappedSlugs[$expectedId] !== $expectedSlug) {
                throw new \AssertionError("Área '{$expectedId}' com slug '{$expectedSlug}' não encontrada ou divergente.");
            }
        }
    }
}
