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
            'provimento_article' => 'Art. 1º, caput e § 1º',
            'title' => 'Marketing jurídico com informação objetiva e verdadeira',
            'description' => 'Toda publicidade deve veicular informações objetivas e verdadeiras.',
            'target_audience' => ['copywriters', 'developers'],
            'enforcement_type' => 'hybrid'
        ];

        $rule = EthicalComplianceRule::fromArray($data);
        if ($rule->id !== 'RULE-OAB-001') {
            throw new \AssertionError("ID da regra inconsistente: {$rule->id}");
        }
        if ($rule->provimentoArticle !== 'Art. 1º, caput e § 1º') {
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
                'carater_informativo_sem_promessa_resultado',
                'canais_atendimento_passivo'
            ],
            isApproved: true,
            notes: 'Revisado e em conformidade estrita com Provimento 205/2021.'
        );

        if (!$validSignoff->isValid()) {
            throw new \AssertionError("O sign-off válido deveria ter retornado true.");
        }

        // Faltando canais_atendimento_passivo
        $incompleteSignoff = new PrePublicationSignoff(
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
            isApproved: true
        );

        if ($incompleteSignoff->isValid()) {
            throw new \AssertionError("O sign-off faltando canais_atendimento_passivo deveria ter retornado false.");
        }

        // Aprovador não autorizado (fora dos sócios fundadores)
        $unauthorizedApproverSignoff = new PrePublicationSignoff(
            contentId: 'page-home-hero',
            contentTitle: 'Página Inicial - Hero Section',
            signoffDate: new \DateTimeImmutable('2026-10-07 10:00:00'),
            approverNames: ['Estagiário João da Silva'],
            checkedItems: [
                'identificacao_completa_advogados',
                'ausencia_termos_superlativos_e_mercantis',
                'sobriedade_visual_e_botoes_contato',
                'carater_informativo_sem_promessa_resultado',
                'canais_atendimento_passivo'
            ],
            isApproved: true
        );

        if ($unauthorizedApproverSignoff->isValid()) {
            throw new \AssertionError("Sign-off com aprovador não autorizado deveria ter retornado false.");
        }

        // Não aprovado (isApproved = false)
        $unapprovedSignoff = new PrePublicationSignoff(
            contentId: 'page-home-hero',
            contentTitle: 'Página Inicial - Hero Section',
            signoffDate: new \DateTimeImmutable('2026-10-07 10:00:00'),
            approverNames: ['Lays Regina de Souza'],
            checkedItems: [
                'identificacao_completa_advogados',
                'ausencia_termos_superlativos_e_mercantis',
                'sobriedade_visual_e_botoes_contato',
                'carater_informativo_sem_promessa_resultado',
                'canais_atendimento_passivo'
            ],
            isApproved: false
        );

        if ($unapprovedSignoff->isValid()) {
            throw new \AssertionError("O sign-off com isApproved=false deveria ter retornado false.");
        }
    }

    public function testValidatorDetectsProhibitedTerms(): void
    {
        $prohibited = $this->complianceData['prohibited_expressions'] ?? [
            [
                'term' => 'o melhor',
                'category' => 'superlativo',
                'severity' => 'CRITICAL',
                'article_reference' => 'Art. 3º, IV'
            ],
            [
                'term' => 'resultado garantido',
                'category' => 'promessa_resultado',
                'severity' => 'CRITICAL',
                'article_reference' => 'Art. 6º, caput'
            ],
            [
                'term' => 'tabela de preços',
                'category' => 'preco_honorarios',
                'severity' => 'CRITICAL',
                'article_reference' => 'Art. 3º, I'
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

    public function testValidatorWordBoundariesAvoidsFalsePositives(): void
    {
        $prohibited = [
            ['term' => 'líder', 'category' => 'superlativo', 'severity' => 'CRITICAL', 'article_reference' => 'Art. 3º, IV'],
            ['term' => 'o melhor', 'category' => 'superlativo', 'severity' => 'CRITICAL', 'article_reference' => 'Art. 3º, IV']
        ];
        $validator = new EthicalContentValidator($prohibited);

        // "liderança" NÃO deve casar com "líder"
        $neutralText = "Nossa liderança acadêmica e compromisso com o constante aprimoramento institucional.";
        $violationsNeutral = $validator->validateText($neutralText);
        if (!empty($violationsNeutral)) {
            throw new \AssertionError("Falso positivo detectado: 'liderança' casou indevidamente com 'líder'. Violações: " . json_encode($violationsNeutral, JSON_UNESCAPED_UNICODE));
        }

        // "melhorar" NÃO deve casar com "o melhor"
        $neutralText2 = "Buscamos melhorar a cada dia o atendimento consultivo aos nossos clientes.";
        $violationsNeutral2 = $validator->validateText($neutralText2);
        if (!empty($violationsNeutral2)) {
            throw new \AssertionError("Falso positivo detectado: 'melhorar' casou indevidamente com 'o melhor'. Violações: " . json_encode($violationsNeutral2, JSON_UNESCAPED_UNICODE));
        }

        // "líder" isolado DEVE casar
        $badText = "O escritório é líder no segmento corporativo.";
        $violationsBad = $validator->validateText($badText);
        if (empty($violationsBad)) {
            throw new \AssertionError("Deveria ter detectado 'líder' isolado no texto.");
        }
    }

    public function testValidatorCatchesPhrasesFromRuleForbiddenList(): void
    {
        $validator = new EthicalContentValidator($this->complianceData['prohibited_expressions'] ?? []);

        $textWithSpecialtiesAndMarkdownTerms = "Somos especialistas em direito de saúde e temos os melhores advogados da região. Oferecemos atendimento com risco zero e preço popular.";
        $violations = $validator->validateText($textWithSpecialtiesAndMarkdownTerms);

        $detected = array_map('mb_strtolower', array_column($violations, 'term'));
        $expected = ['especialistas em direito de saúde', 'os melhores', 'risco zero', 'preço popular'];

        foreach ($expected as $exp) {
            if (!in_array($exp, $detected, true)) {
                throw new \AssertionError("O validador falhou ao identificar o termo proibido '{$exp}'. Detectados: " . implode(', ', $detected));
            }
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

    public function testProfessionalIdentificationStrictValidation(): void
    {
        $validator = new EthicalContentValidator([]);

        // Casos Válidos
        $validPartner1 = [
            'name' => 'Rodrigo Guedes da Silva',
            'oab' => 'OAB/SP nº 538.416'
        ];
        $errors1 = $validator->validateProfessionalIdentification($validPartner1);
        if (!empty($errors1)) {
            throw new \AssertionError("Identificação válida foi rejeitada indevidamente: " . implode(', ', $errors1));
        }

        $validPartner2 = [
            'name' => 'Lays Regina de Souza',
            'oab' => 'OAB/SP 511.204'
        ];
        $errors2 = $validator->validateProfessionalIdentification($validPartner2);
        if (!empty($errors2)) {
            throw new \AssertionError("Identificação válida foi rejeitada indevidamente: " . implode(', ', $errors2));
        }

        // Casos Inválidos que DEVEM ser rejeitados:
        // 1. "abc 123"
        $invalidAbc = ['name' => 'Beltrano da Silva', 'oab' => 'abc 123'];
        if (empty($validator->validateProfessionalIdentification($invalidAbc))) {
            throw new \AssertionError("Validador aceitou indevidamente 'abc 123' como inscrição na OAB.");
        }

        // 2. Apenas números sem OAB e sem UF
        $invalidNumbersOnly = ['name' => 'Beltrano da Silva', 'oab' => '538416'];
        if (empty($validator->validateProfessionalIdentification($invalidNumbersOnly))) {
            throw new \AssertionError("Validador aceitou indevidamente número puro sem sigla OAB e Seccional.");
        }

        // 3. Sem Seccional (ex: "OAB 538416")
        $invalidNoUf = ['name' => 'Beltrano da Silva', 'oab' => 'OAB 538416'];
        if (empty($validator->validateProfessionalIdentification($invalidNoUf))) {
            throw new \AssertionError("Validador aceitou indevidamente inscrição sem indicação da Seccional (UF).");
        }

        // 4. Sem número (ex: "OAB/SP")
        $invalidNoNumber = ['name' => 'Beltrano da Silva', 'oab' => 'OAB/SP'];
        if (empty($validator->validateProfessionalIdentification($invalidNoNumber))) {
            throw new \AssertionError("Validador aceitou indevidamente inscrição sem número de registro.");
        }

        // 5. Nome simples (não completo)
        $invalidSingleName = ['name' => 'Rodrigo', 'oab' => 'OAB/SP nº 538.416'];
        if (empty($validator->validateProfessionalIdentification($invalidSingleName))) {
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
