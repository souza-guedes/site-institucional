<?php

declare(strict_types=1);

namespace Tests\Unit;

if (file_exists(dirname(__DIR__, 2) . '/src/Domain/Compliance/EthicalComplianceRule.php')) {
    require_once dirname(__DIR__, 2) . '/src/Domain/Compliance/EthicalComplianceRule.php';
}
if (file_exists(dirname(__DIR__, 2) . '/src/Domain/Compliance/PrePublicationSignoff.php')) {
    require_once dirname(__DIR__, 2) . '/src/Domain/Compliance/PrePublicationSignoff.php';
}
if (file_exists(dirname(__DIR__, 2) . '/src/Domain/Compliance/EthicalContentValidator.php')) {
    require_once dirname(__DIR__, 2) . '/src/Domain/Compliance/EthicalContentValidator.php';
}

use App\Domain\Compliance\EthicalComplianceRule;
use App\Domain\Compliance\PrePublicationSignoff;
use App\Domain\Compliance\EthicalContentValidator;

final class EthicalContentValidatorTest
{
    private string $dataPath;
    private array $complianceData;

    public function __construct()
    {
        $this->dataPath = dirname(__DIR__, 2) . '/data/ethical-compliance-rules.json';
        if (file_exists($this->dataPath)) {
            $raw = file_get_contents($this->dataPath);
            $decoded = json_decode($raw, true);
            $this->complianceData = is_array($decoded) ? $decoded : [];
        } else {
            $this->complianceData = [];
        }
    }

    public function testEthicalComplianceRuleEntity(): void
    {
        $data = [
            'id' => 'RULE-OAB-001',
            'provimento_article' => 'Art. 1º',
            'title' => 'Caráter meramente informativo e educativo',
            'description' => 'Toda publicidade deve ter finalidade informativa e educativa.',
            'target_audience' => ['copywriters', 'developers'],
            'enforcement_type' => 'hybrid'
        ];

        $rule = EthicalComplianceRule::fromArray($data);
        if ($rule->id !== 'RULE-OAB-001') {
            throw new \AssertionError("ID da regra inconsistente: {$rule->id}");
        }
        if ($rule->provimentoArticle !== 'Art. 1º') {
            throw new \AssertionError("Artigo inconsistente: {$rule->provimentoArticle}");
        }

        $exported = $rule->toArray();
        if ($exported['title'] !== $data['title']) {
            throw new \AssertionError("Serialização da regra retornou dados inconsistentes.");
        }
    }

    public function testPrePublicationSignoffValidation(): void
    {
        $validSignoff = new PrePublicationSignoff(
            contentId: 'page-home-hero',
            contentTitle: 'Página Inicial - Hero Section',
            signoffDate: new \DateTimeImmutable('2026-10-07 10:00:00'),
            approverNames: ['Rodrigo Guedes da Silva'],
            checkedItems: [
                'identificacao_completa_advogados',
                'ausencia_termos_superlativos_e_mercantis',
                'sobriedade_visual_e_botoes_contato',
                'carater_informativo_sem_promessa_resultado'
            ],
            isApproved: true,
            notes: 'Revisado e em conformidade estrita com Provimento 205/2021.'
        );

        if (!$validSignoff->isValid()) {
            throw new \AssertionError("O sign-off válido deveria ter retornado true.");
        }

        $incompleteSignoff = new PrePublicationSignoff(
            contentId: 'page-home-hero',
            contentTitle: 'Página Inicial - Hero Section',
            signoffDate: new \DateTimeImmutable('2026-10-07 10:00:00'),
            approverNames: ['Rodrigo Guedes da Silva'],
            checkedItems: ['identificacao_completa_advogados'], // faltam itens obrigatórios
            isApproved: true
        );

        if ($incompleteSignoff->isValid()) {
            throw new \AssertionError("O sign-off com checklist incompleto deveria ter retornado false.");
        }

        $unapprovedSignoff = new PrePublicationSignoff(
            contentId: 'page-home-hero',
            contentTitle: 'Página Inicial - Hero Section',
            signoffDate: new \DateTimeImmutable('2026-10-07 10:00:00'),
            approverNames: ['Rodrigo Guedes da Silva'],
            checkedItems: [
                'identificacao_completa_advogados',
                'ausencia_termos_superlativos_e_mercantis',
                'sobriedade_visual_e_botoes_contato',
                'carater_informativo_sem_promessa_resultado'
            ],
            isApproved: false
        );

        if ($unapprovedSignoff->isValid()) {
            throw new \AssertionError("O sign-off não aprovado deveria ter retornado false.");
        }
    }

    public function testValidatorDetectsProhibitedTerms(): void
    {
        $prohibited = $this->complianceData['prohibited_expressions'] ?? [
            [
                'term' => 'o melhor',
                'category' => 'superlativo',
                'severity' => 'CRITICAL',
                'rationale' => 'Superlativo vedado',
                'article_reference' => 'Art. 4º'
            ],
            [
                'term' => 'resultado garantido',
                'category' => 'promessa_resultado',
                'severity' => 'CRITICAL',
                'rationale' => 'Promessa de resultado vedada',
                'article_reference' => 'Art. 4º'
            ],
            [
                'term' => 'tabela de preços',
                'category' => 'preco_honorarios',
                'severity' => 'CRITICAL',
                'rationale' => 'Preços públicos vedados',
                'article_reference' => 'Art. 4º'
            ]
        ];

        $validator = new EthicalContentValidator($prohibited);

        $badText = 'Somos O MELHOR escritório da região e oferecemos RESULTADO GARANTIDO para sua causa. Consulte nossa tabela de preços!';
        $violations = $validator->validateText($badText);

        if (count($violations) < 3) {
            throw new \AssertionError("Esperava-se pelo menos 3 violações no texto proibido. Encontradas: " . count($violations));
        }

        $detectedTerms = array_column($violations, 'term');
        if (!in_array('o melhor', $detectedTerms, true)) {
            throw new \AssertionError("Deveria detectar 'o melhor'.");
        }
        if (!in_array('resultado garantido', $detectedTerms, true)) {
            throw new \AssertionError("Deveria detectar 'resultado garantido'.");
        }
    }

    public function testValidatorCleanTextPasses(): void
    {
        $prohibited = $this->complianceData['prohibited_expressions'] ?? [];
        $validator = new EthicalContentValidator($prohibited);

        $cleanText = 'Souza Guedes Advogados atua com foco em Direito Imobiliário, Saúde, Consumidor e Família, prestando atendimento técnico, ético e personalizado.';
        $violations = $validator->validateText($cleanText);

        if (!empty($violations)) {
            throw new \AssertionError("Texto limpo gerou falsos positivos: " . json_encode($violations, JSON_UNESCAPED_UNICODE));
        }
    }

    public function testProfessionalIdentificationValidation(): void
    {
        $validator = new EthicalContentValidator([]);

        $validPartner = [
            'name' => 'Rodrigo Guedes da Silva',
            'oab' => 'OAB/SP nº 538.416'
        ];
        $errorsValid = $validator->validateProfessionalIdentification($validPartner);
        if (!empty($errorsValid)) {
            throw new \AssertionError("Identificação válida foi rejeitada indevidamente: " . implode(', ', $errorsValid));
        }

        $invalidPartnerMissingOab = [
            'name' => 'Rodrigo Guedes da Silva',
            'oab' => ''
        ];
        $errorsMissingOab = $validator->validateProfessionalIdentification($invalidPartnerMissingOab);
        if (empty($errorsMissingOab)) {
            throw new \AssertionError("Deveria rejeitar identificação sem número de inscrição OAB.");
        }

        $invalidPartnerSingleName = [
            'name' => 'Rodrigo',
            'oab' => 'OAB/SP nº 538.416'
        ];
        $errorsSingleName = $validator->validateProfessionalIdentification($invalidPartnerSingleName);
        if (empty($errorsSingleName)) {
            throw new \AssertionError("Deveria rejeitar identificação profissional sem nome completo.");
        }
    }

    public function testComplianceDisclaimerValidation(): void
    {
        $validator = new EthicalContentValidator([]);

        $validDisclaimer = "Este portal tem finalidade exclusivamente institucional, informativa e educativa, em conformidade com o Provimento CFOAB nº 205/2021 e o Código de Ética e Disciplina da OAB.";
        if (!$validator->validateComplianceDisclaimer($validDisclaimer)) {
            throw new \AssertionError("Disclaimer válido deveria ter sido aprovado.");
        }

        $invalidDisclaimer = "Visite nosso site.";
        if ($validator->validateComplianceDisclaimer($invalidDisclaimer)) {
            throw new \AssertionError("Disclaimer insuficiente deveria ter sido rejeitado.");
        }
    }

    public function testExistingFirmProfileIsCompliant(): void
    {
        $firmProfilePath = dirname(__DIR__, 2) . '/data/firm-profile.json';
        if (!file_exists($firmProfilePath)) {
            throw new \AssertionError("Arquivo firm-profile.json não encontrado.");
        }

        $profileData = json_decode(file_get_contents($firmProfilePath), true);
        $validator = new EthicalContentValidator($this->complianceData['prohibited_expressions'] ?? []);

        // Valida todo o JSON contra expressões vedadas
        $allText = json_encode($profileData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $violations = $validator->validateText($allText);
        if (!empty($violations)) {
            throw new \AssertionError("O perfil institucional atual contém violações éticas: " . json_encode($violations, JSON_UNESCAPED_UNICODE));
        }

        // Valida os sócios
        foreach ($profileData['partners'] as $partner) {
            $errs = $validator->validateProfessionalIdentification([
                'name' => $partner['name'],
                'oab' => $partner['oab_number'] ?? $partner['oab_registration'] ?? ''
            ]);
            if (!empty($errs)) {
                throw new \AssertionError("Sócio {$partner['name']} não atende aos requisitos do Art. 3º: " . implode(', ', $errs));
            }
        }

        // Valida disclaimer
        if (!$validator->validateComplianceDisclaimer($profileData['compliance_disclaimer'] ?? '')) {
            throw new \AssertionError("O disclaimer de conformidade do firm-profile.json é inválido.");
        }
    }
}
