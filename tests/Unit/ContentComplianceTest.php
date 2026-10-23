<?php

declare(strict_types=1);

namespace Tests\Unit;

if (file_exists(dirname(__DIR__, 2) . '/src/Domain/Compliance/EthicalContentValidator.php')) {
    require_once dirname(__DIR__, 2) . '/src/Domain/Compliance/EthicalContentValidator.php';
}

use App\Domain\Compliance\EthicalContentValidator;

/**
 * Auditoria ética dos textos institucionais da WDLC 4.1 (trajetória, perfis, áreas e artigos).
 * Todo texto publicável passa pelas expressões vedadas de ethical-compliance-rules.json
 * e pelos termos vedados de cada área em sitemap-taxonomy.json.
 */
final class ContentComplianceTest
{
    private array $profile;
    private array $articles;
    private EthicalContentValidator $validator;

    public function __construct()
    {
        $root = dirname(__DIR__, 2);
        $this->profile = $this->readJson($root . '/data/firm-profile.json');
        $this->articles = $this->readJson($root . '/data/articles.json');
        $rules = $this->readJson($root . '/data/ethical-compliance-rules.json');
        $taxonomy = $this->readJson($root . '/data/sitemap-taxonomy.json');

        $prohibited = $rules['prohibited_expressions'] ?? [];
        foreach ($taxonomy['practice_areas_taxonomy'] ?? [] as $area) {
            foreach ($area['prohibited_terms'] ?? [] as $term) {
                $prohibited[] = [
                    'term' => $term,
                    'category' => 'termo_vedado_da_area',
                    'severity' => 'HIGH',
                    'rationale' => "Termo vedado na área '{$area['slug']}' (sitemap-taxonomy.json).",
                    'article_reference' => 'Art. 3º, IV',
                ];
            }
        }
        // Área de atuação não é título de especialista (Art. 3º, III); os textos da 4.1 não usam a palavra.
        foreach (['especialista', 'especialistas', 'especializado', 'especializada', 'especialidade', 'especialidades'] as $term) {
            $prohibited[] = ['term' => $term, 'category' => 'titulo_indevido', 'severity' => 'HIGH', 'article_reference' => 'Art. 3º, III'];
        }

        $this->validator = new EthicalContentValidator($prohibited);
    }

    private function readJson(string $path): array
    {
        if (!file_exists($path)) {
            throw new \RuntimeException("Arquivo '{$path}' não encontrado.");
        }
        return json_decode((string) file_get_contents($path), true, 512, JSON_THROW_ON_ERROR);
    }

    /**
     * Textos publicáveis (todas as strings), sem as listas internas de pendências.
     *
     * @return array<string, string> caminho => texto
     */
    private function publishableTexts(array $node, string $path): array
    {
        $texts = [];
        foreach ($node as $key => $value) {
            if ($key === 'pending') {
                continue;
            }
            $childPath = "{$path}.{$key}";
            if (is_array($value)) {
                $texts += $this->publishableTexts($value, $childPath);
            } elseif (is_string($value)) {
                $texts[$childPath] = $value;
            }
        }
        return $texts;
    }

    private function assertNoViolations(array $texts): void
    {
        foreach ($texts as $path => $text) {
            $violations = $this->validator->validateText($text);
            if (!empty($violations)) {
                $terms = implode(', ', array_map(fn ($v) => "'{$v['term']}' ({$v['article_reference']})", $violations));
                throw new \AssertionError("Expressão vedada em {$path}: {$terms}");
            }
        }
    }

    public function testValidatorCatchesProhibitedExpressionInArticleText(): void
    {
        $sample = ['sections' => [['paragraphs' => ['Com a nossa atuação, o resultado garantido é a liberação do medicamento.']]]];
        $violations = [];
        foreach ($this->publishableTexts($sample, 'amostra') as $text) {
            $violations = array_merge($violations, $this->validator->validateText($text));
        }
        if (empty($violations)) {
            throw new \AssertionError("A auditoria não detectou 'resultado garantido' num texto de artigo.");
        }
    }

    public function testFirmHistoryAndPartnerBiosAreCompliant(): void
    {
        $firm = $this->profile['firm'] ?? [];
        if (empty($firm['history'])) {
            throw new \AssertionError('firm.history está vazio.');
        }
        $texts = $this->publishableTexts(['history' => $firm['history']], 'firm');
        foreach ($this->profile['partners'] ?? [] as $i => $partner) {
            if (empty($partner['bio_long'])) {
                throw new \AssertionError("partners[{$i}].bio_long está vazio.");
            }
            $texts += $this->publishableTexts(['bio_long' => $partner['bio_long'], 'education' => $partner['education'] ?? []], "partners[{$i}]");
        }
        $this->assertNoViolations($texts);
    }

    public function testPartnerBiosStateFullNameAndOab(): void
    {
        foreach ($this->profile['partners'] ?? [] as $partner) {
            $bio = implode(' ', $partner['bio_long'] ?? []);
            $number = preg_replace('/^OAB\/SP\s+nº\s*/u', '', (string) $partner['oab_number']);
            if (!str_contains($bio, $partner['name']) || !str_contains($bio, $number)) {
                throw new \AssertionError("A minibio de {$partner['name']} deve citar o nome completo e a inscrição OAB/SP {$number} (Art. 1º, § 1º).");
            }
        }
    }

    public function testPracticeAreaDescriptionsAreCompliant(): void
    {
        $texts = [];
        foreach ($this->profile['practice_areas'] ?? [] as $area) {
            if (empty($area['description'])) {
                throw new \AssertionError("A área '{$area['slug']}' não tem description.");
            }
            $texts += $this->publishableTexts(['description' => $area['description']], "practice_areas.{$area['slug']}");
        }
        $this->assertNoViolations($texts);
    }

    public function testArticlesAreCompliant(): void
    {
        $this->assertNoViolations($this->publishableTexts($this->articles, 'articles.json'));

        if (!$this->validator->validateComplianceDisclaimer((string) ($this->articles['educational_notice'] ?? ''))) {
            throw new \AssertionError('O aviso educativo dos artigos deve citar o Provimento 205/2021 e não conter termos vedados.');
        }
    }

    public function testArticleAuthorsAreRegisteredPartnersWithValidOab(): void
    {
        $partners = [];
        foreach ($this->profile['partners'] ?? [] as $partner) {
            $partners[$partner['name']] = $partner;
        }
        foreach ($this->articles['articles'] ?? [] as $article) {
            $partner = $partners[$article['author']] ?? null;
            if ($partner === null) {
                throw new \AssertionError("O autor '{$article['author']}' do artigo '{$article['slug']}' não é sócio cadastrado em firm-profile.json.");
            }
            $errors = $this->validator->validateProfessionalIdentification($partner);
            if (!empty($errors)) {
                throw new \AssertionError("Identificação inválida do autor '{$article['author']}': " . implode('; ', $errors));
            }
        }
    }

    public function testArticlesBelongToPracticeAreas(): void
    {
        $slugs = array_column($this->profile['practice_areas'] ?? [], 'slug');
        foreach ($this->articles['articles'] ?? [] as $article) {
            if (!in_array($article['area_slug'], $slugs, true)) {
                throw new \AssertionError("O artigo '{$article['slug']}' está na área '{$article['area_slug']}', que não é área de atuação da banca.");
            }
        }
    }

    public function testApprovedArticlesHaveNoPendingItems(): void
    {
        foreach ($this->articles['articles'] ?? [] as $article) {
            if (($article['status'] ?? '') !== 'aprovado') {
                continue;
            }
            $raw = json_encode($article, JSON_UNESCAPED_UNICODE);
            if (!empty($article['pending']) || str_contains((string) $raw, '[A CONFIRMAR')) {
                throw new \AssertionError("O artigo '{$article['slug']}' está aprovado, mas ainda tem pendências.");
            }
            if (empty($article['published_at'])) {
                throw new \AssertionError("O artigo '{$article['slug']}' está aprovado sem data de publicação.");
            }
        }
    }
}
