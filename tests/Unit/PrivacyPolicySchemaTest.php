<?php

declare(strict_types=1);

namespace Tests\Unit;

class PrivacyPolicySchemaTest
{
    private array $data;
    private array $schema;

    public function __construct()
    {
        $dataPath = dirname(__DIR__, 2) . '/data/privacy-policy.json';
        $schemaPath = dirname(__DIR__, 2) . '/schemas/privacy-policy.schema.json';

        if (!file_exists($dataPath)) {
            throw new \RuntimeException("O arquivo de dados 'data/privacy-policy.json' não foi encontrado.");
        }

        if (!file_exists($schemaPath)) {
            throw new \RuntimeException("O arquivo de schema 'schemas/privacy-policy.schema.json' não foi encontrado.");
        }

        $this->data = json_decode((string) file_get_contents($dataPath), true, 512, JSON_THROW_ON_ERROR);
        $this->schema = json_decode((string) file_get_contents($schemaPath), true, 512, JSON_THROW_ON_ERROR);
    }

    public function testRequiredRootKeysExist(): void
    {
        foreach ($this->schema['required'] ?? [] as $key) {
            if (!array_key_exists($key, $this->data)) {
                throw new \AssertionError("Chave raiz obrigatória ausente: '{$key}'");
            }
        }
    }

    public function testRouteAndStatus(): void
    {
        if ($this->data['route'] !== $this->schema['properties']['route']['const']) {
            throw new \AssertionError("Rota inválida: '{$this->data['route']}'.");
        }
        if (!in_array($this->data['status'], $this->schema['properties']['status']['enum'], true)) {
            throw new \AssertionError("Status inválido: '{$this->data['status']}'.");
        }
    }

    public function testDocumentsHaveValidSections(): void
    {
        $sectionKeys = $this->schema['$defs']['section']['required'];
        foreach (['privacy_policy', 'cookie_policy', 'terms_of_use'] as $document) {
            $sections = $this->data[$document]['sections'] ?? [];
            if (empty($this->data[$document]['title']) || empty($sections)) {
                throw new \AssertionError("Documento '{$document}' sem título ou sem seções.");
            }
            foreach ($sections as $section) {
                foreach ($sectionKeys as $key) {
                    if (!array_key_exists($key, $section)) {
                        throw new \AssertionError("Seção sem '{$key}' em '{$document}'.");
                    }
                }
                if (empty($section['paragraphs']) && empty($section['list']) && empty($section['table'])) {
                    throw new \AssertionError("Seção vazia ('{$section['heading']}') em '{$document}'.");
                }
                if (isset($section['table'])) {
                    $columns = count($section['table']['columns']);
                    foreach ($section['table']['rows'] as $row) {
                        if (count($row) !== $columns) {
                            throw new \AssertionError("Linha de tabela com número errado de colunas em '{$section['heading']}'.");
                        }
                    }
                }
            }
        }
    }

    public function testCookieBannerAndCategories(): void
    {
        $modes = $this->schema['properties']['cookie_banner']['properties']['mode']['enum'];
        if (!in_array($this->data['cookie_banner']['mode'], $modes, true)) {
            throw new \AssertionError("Modo de banner inválido: '{$this->data['cookie_banner']['mode']}'.");
        }
        $allowed = $this->schema['properties']['cookie_policy']['properties']['cookie_categories_in_use']['items']['enum'];
        foreach ($this->data['cookie_policy']['cookie_categories_in_use'] as $category) {
            if (!in_array($category, $allowed, true)) {
                throw new \AssertionError("Categoria de cookie inválida: '{$category}'.");
            }
        }
    }
}
