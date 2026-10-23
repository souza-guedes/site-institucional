<?php

declare(strict_types=1);

namespace Tests\Unit;

class ArticlesSchemaTest
{
    private array $data;
    private array $schema;

    public function __construct()
    {
        $dataPath = dirname(__DIR__, 2) . '/data/articles.json';
        $schemaPath = dirname(__DIR__, 2) . '/schemas/articles.schema.json';

        if (!file_exists($dataPath)) {
            throw new \RuntimeException("O arquivo de dados 'data/articles.json' não foi encontrado.");
        }

        if (!file_exists($schemaPath)) {
            throw new \RuntimeException("O arquivo de schema 'schemas/articles.schema.json' não foi encontrado.");
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

    public function testArticleStructure(): void
    {
        $required = $this->schema['properties']['articles']['items']['required'] ?? [];
        $statuses = $this->schema['properties']['articles']['items']['properties']['status']['enum'] ?? [];
        $slugs = [];

        foreach ($this->data['articles'] ?? [] as $i => $article) {
            foreach ($required as $key) {
                if (!array_key_exists($key, $article)) {
                    throw new \AssertionError("articles[{$i}] sem a chave obrigatória '{$key}'.");
                }
            }
            if (!preg_match('/^[a-z0-9-]+$/', $article['slug'])) {
                throw new \AssertionError("Slug inválido: '{$article['slug']}'.");
            }
            if (in_array($article['slug'], $slugs, true)) {
                throw new \AssertionError("Slug duplicado: '{$article['slug']}'.");
            }
            $slugs[] = $article['slug'];

            if (!in_array($article['status'], $statuses, true)) {
                throw new \AssertionError("Status inválido em '{$article['slug']}': '{$article['status']}'.");
            }
            if (!is_int($article['reading_time_minutes']) || $article['reading_time_minutes'] < 1) {
                throw new \AssertionError("Tempo de leitura inválido em '{$article['slug']}'.");
            }
            if (empty($article['sections']) || empty($article['references'])) {
                throw new \AssertionError("O artigo '{$article['slug']}' precisa de seções e de referências normativas.");
            }
            foreach ($article['sections'] as $section) {
                foreach (['heading', 'paragraphs', 'list', 'closing_paragraphs'] as $key) {
                    if (!array_key_exists($key, $section)) {
                        throw new \AssertionError("Seção sem '{$key}' no artigo '{$article['slug']}'.");
                    }
                }
                if (empty($section['paragraphs']) && empty($section['list'])) {
                    throw new \AssertionError("Seção vazia ('{$section['heading']}') no artigo '{$article['slug']}'.");
                }
            }
        }
    }
}
