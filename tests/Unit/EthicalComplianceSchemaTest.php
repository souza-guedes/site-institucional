<?php

declare(strict_types=1);

namespace Tests\Unit;

final class EthicalComplianceSchemaTest
{
    private string $schemaPath;
    private string $dataPath;
    private array $data;

    public function __construct()
    {
        $this->schemaPath = dirname(__DIR__, 2) . '/schemas/ethical-compliance.schema.json';
        $this->dataPath = dirname(__DIR__, 2) . '/data/ethical-compliance-rules.json';

        if (file_exists($this->dataPath)) {
            $raw = file_get_contents($this->dataPath);
            $decoded = json_decode($raw, true);
            $this->data = is_array($decoded) ? $decoded : [];
        } else {
            $this->data = [];
        }
    }

    public function testFilesExist(): void
    {
        if (!file_exists($this->schemaPath)) {
            throw new \AssertionError("O arquivo de schema '{$this->schemaPath}' não existe.");
        }

        if (!file_exists($this->dataPath)) {
            throw new \AssertionError("O arquivo de dados canônicos '{$this->dataPath}' não existe.");
        }

        $schemaRaw = file_get_contents($this->schemaPath);
        $schemaDecoded = json_decode($schemaRaw, true);
        if (!is_array($schemaDecoded) || empty($schemaDecoded)) {
            throw new \AssertionError("O schema '{$this->schemaPath}' contém JSON inválido ou vazio.");
        }

        if (empty($this->data)) {
            throw new \AssertionError("O arquivo de dados '{$this->dataPath}' contém JSON inválido ou vazio.");
        }
    }

    public function testRequiredRootKeysExist(): void
    {
        $requiredKeys = [
            'version',
            'last_updated',
            'regulatory_framework',
            'mandatory_identifications',
            'prohibited_expressions',
            'rules',
            'pre_publication_signoff_protocol'
        ];

        foreach ($requiredKeys as $key) {
            if (!array_key_exists($key, $this->data)) {
                throw new \AssertionError("Chave raiz obrigatória '{$key}' não encontrada no dataset de compliance.");
            }
        }
    }

    public function testRegulatoryFrameworkReferencesProvimento205(): void
    {
        $framework = $this->data['regulatory_framework'] ?? [];
        if (!is_array($framework)) {
            throw new \AssertionError("A seção 'regulatory_framework' deve ser um objeto.");
        }

        $provimento = $framework['primary_provimento'] ?? '';
        if (!str_contains($provimento, '205/2021')) {
            throw new \AssertionError("O framework regulatório deve referenciar expressamente o 'Provimento CFOAB nº 205/2021'. Encontrado: '{$provimento}'");
        }
    }

    public function testMandatoryIdentificationsConfiguration(): void
    {
        $identifications = $this->data['mandatory_identifications'] ?? [];
        if (!is_array($identifications)) {
            throw new \AssertionError("A seção 'mandatory_identifications' deve ser um objeto.");
        }

        if (($identifications['require_lawyer_full_name'] ?? false) !== true) {
            throw new \AssertionError("A regra 'require_lawyer_full_name' deve ser obrigatoriamente true.");
        }

        if (($identifications['require_oab_registration_number'] ?? false) !== true) {
            throw new \AssertionError("A regra 'require_oab_registration_number' deve ser obrigatoriamente true.");
        }

        if (($identifications['require_uf_subdivision'] ?? false) !== true) {
            throw new \AssertionError("A regra 'require_uf_subdivision' deve ser obrigatoriamente true.");
        }
    }

    public function testProhibitedExpressionsContainsCoreCategoriesAndPreciseArticles(): void
    {
        $expressions = $this->data['prohibited_expressions'] ?? [];
        if (!is_array($expressions) || count($expressions) < 10) {
            throw new \AssertionError("Devem ser catalogadas pelo menos 10 expressões/termos proibidos fundamentais.");
        }

        $categories = array_unique(array_column($expressions, 'category'));
        $expectedCategories = [
            'mercantilizacao',
            'superlativo',
            'promessa_resultado',
            'preco_honorarios',
            'captacao_litigio',
            'especialidade_sem_titulo',
            'ostentacao'
        ];

        foreach ($expectedCategories as $expCat) {
            if (!in_array($expCat, $categories, true)) {
                throw new \AssertionError("Categoria proibida obrigatória '{$expCat}' não está presente nas expressões catalogadas.");
            }
        }

        // Valida referências precisas aos artigos do Provimento 205/2021
        foreach ($expressions as $expr) {
            $term = mb_strtolower($expr['term'] ?? '');
            $article = $expr['article_reference'] ?? '';

            if ($term === 'o melhor' || $term === 'líder') {
                if (!str_contains($article, 'Art. 3º, IV') && !str_contains($article, 'Art. 3º, inc. IV')) {
                    throw new \AssertionError("O termo '{$term}' deve referenciar o Art. 3º, IV (expressões persuasivas e autoengrandecimento), e não '{$article}'.");
                }
            }

            if ($term === 'tabela de preços' || $term === 'consulta grátis') {
                if (!str_contains($article, 'Art. 3º, I') && !str_contains($article, 'Art. 3º, inc. I')) {
                    throw new \AssertionError("O termo '{$term}' deve referenciar o Art. 3º, I (honorários e gratuidade), e não '{$article}'.");
                }
            }

            if ($term === 'resultado garantido') {
                if (!str_contains($article, 'Art. 6º') && !str_contains($article, 'Art. 3º')) {
                    throw new \AssertionError("O termo '{$term}' deve referenciar o Art. 6º ou Art. 3º, I (promessa de resultado), e não '{$article}'.");
                }
            }

            if (str_contains($term, 'especialista')) {
                if (!str_contains($article, 'Art. 3º, III') && !str_contains($article, 'Art. 3º, inc. III')) {
                    throw new \AssertionError("Termos de especialidade devem referenciar o Art. 3º, III (especialidade sem título). Encontrado: '{$article}'.");
                }
            }

            if (str_contains($term, 'ostentação')) {
                if (!str_contains($article, 'Art. 6º, parágrafo único') && !str_contains($article, 'Art. 6º')) {
                    throw new \AssertionError("Termo de ostentação deve referenciar o Art. 6º, parágrafo único. Encontrado: '{$article}'.");
                }
            }
        }
    }

    public function testRulesCoverArticles1Through6Strictly(): void
    {
        $rules = $this->data['rules'] ?? [];
        if (!is_array($rules) || empty($rules)) {
            throw new \AssertionError("A lista de regras éticas 'rules' não pode ser vazia.");
        }

        $articlesCovered = [];
        foreach ($rules as $rule) {
            $art = $rule['provimento_article'] ?? '';
            $articlesCovered[] = $art;
        }

        // Validação estrita por regex, impedindo falsos positivos como 'Art. 16'
        for ($i = 1; $i <= 6; $i++) {
            $found = false;
            foreach ($articlesCovered as $covered) {
                if (preg_match('/^Art\.\s*' . $i . '[ºo]/iu', trim($covered))) {
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                throw new \AssertionError("Não foi encontrada regra cobrindo formalmente o Artigo {$i}º do Provimento 205/2021 (cobertura atual: " . implode(', ', $articlesCovered) . ").");
            }
        }
    }

    public function testRulesContainArticle3SpecialtyRule(): void
    {
        $rules = $this->data['rules'] ?? [];
        $found = false;
        foreach ($rules as $rule) {
            $art = $rule['provimento_article'] ?? '';
            $desc = $rule['description'] ?? '';
            if (str_contains($art, '3º') && (str_contains($desc, 'especialidade') || str_contains($desc, 'especialização'))) {
                $found = true;
                break;
            }
        }

        if (!$found) {
            throw new \AssertionError("Deve existir regra cobrindo expressamente o Art. 3º, III (vedação ao anúncio de especialidade sem título formal ou notória especialização).");
        }
    }

    public function testPrePublicationSignoffProtocolStructure(): void
    {
        $protocol = $this->data['pre_publication_signoff_protocol'] ?? [];
        if (!is_array($protocol)) {
            throw new \AssertionError("A seção 'pre_publication_signoff_protocol' deve ser um objeto.");
        }

        $steps = $protocol['workflow_steps'] ?? [];
        $approvers = $protocol['required_approvers'] ?? [];
        $checklist = $protocol['checklist_items'] ?? [];

        if (empty($steps) || empty($approvers) || empty($checklist)) {
            throw new \AssertionError("O protocolo de sign-off deve conter workflow_steps, required_approvers e checklist_items preenchidos.");
        }

        if (!in_array('canais_atendimento_passivo', $checklist, true)) {
            throw new \AssertionError("O checklist pré-publicação deve conter obrigatoriamente o item 'canais_atendimento_passivo'.");
        }
    }
}
