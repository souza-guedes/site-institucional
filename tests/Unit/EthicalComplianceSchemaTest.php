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

    public function testProhibitedExpressionsContainsCoreCategories(): void
    {
        $expressions = $this->data['prohibited_expressions'] ?? [];
        if (!is_array($expressions) || count($expressions) < 10) {
            throw new \AssertionError("Devem ser catalogadas pelo menos 10 expressões/termos proibidos fundamentais.");
        }

        $categories = array_unique(array_column($expressions, 'category'));
        $expectedCategories = ['mercantilizacao', 'superlativo', 'promessa_resultado', 'preco_honorarios', 'captacao_litigio'];

        foreach ($expectedCategories as $expCat) {
            if (!in_array($expCat, $categories, true)) {
                throw new \AssertionError("Categoria proibida obrigatória '{$expCat}' não está presente nas expressões catalogadas.");
            }
        }

        $terms = array_map('mb_strtolower', array_column($expressions, 'term'));
        $essentialTerms = ['o melhor', 'líder', 'resultado garantido', 'tabela de preços'];
        foreach ($essentialTerms as $term) {
            $found = false;
            foreach ($terms as $t) {
                if (str_contains($t, $term)) {
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                throw new \AssertionError("Termo mandatório vedado pela OAB '{$term}' não está presente no catálogo.");
            }
        }
    }

    public function testRulesCoverArticles1Through6(): void
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

        for ($i = 1; $i <= 6; $i++) {
            $found = false;
            foreach ($articlesCovered as $covered) {
                if (str_contains($covered, (string)$i)) {
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                throw new \AssertionError("Não foi encontrada regra cobrindo o Artigo {$i}º do Provimento 205/2021.");
            }
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
    }
}
