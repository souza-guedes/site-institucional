<?php

declare(strict_types=1);

namespace Tests\Unit;

if (file_exists(dirname(__DIR__, 2) . '/src/Domain/Compliance/EthicalContentValidator.php')) {
    require_once dirname(__DIR__, 2) . '/src/Domain/Compliance/EthicalContentValidator.php';
}

use App\Domain\Compliance\EthicalContentValidator;

/**
 * Auditoria dos textos da rota /privacidade (WDLC 4.2) contra a LGPD (Lei nº 13.709/2018):
 * bases legais do art. 7º, direitos do art. 18, encarregado (art. 41), retenção e cookies.
 */
final class PrivacyPolicyComplianceTest
{
    /** Trechos que identificam cada inciso do art. 18 na lista de direitos. */
    private const ART_18_RIGHTS = [
        'I' => 'confirmação',
        'II' => 'acesso aos dados',
        'III' => 'correção',
        'IV' => 'anonimização',
        'V' => 'portabilidade',
        'VI' => 'eliminação dos dados tratados com o seu consentimento',
        'VII' => 'entidades públicas e privadas',
        'VIII' => 'não fornecer consentimento',
        'IX' => 'revogação do consentimento',
    ];

    private const ART_7_HYPOTHESES = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X'];

    private array $policy;
    private array $profile;

    public function __construct()
    {
        $root = dirname(__DIR__, 2);
        $this->policy = $this->readJson($root . '/data/privacy-policy.json');
        $this->profile = $this->readJson($root . '/data/firm-profile.json');
    }

    private function readJson(string $path): array
    {
        if (!file_exists($path)) {
            throw new \RuntimeException("Arquivo '{$path}' não encontrado.");
        }
        return json_decode((string) file_get_contents($path), true, 512, JSON_THROW_ON_ERROR);
    }

    /** @return array<string, string> caminho => texto, sem as listas internas de pendências */
    private function texts(array $node, string $path): array
    {
        $texts = [];
        foreach ($node as $key => $value) {
            if ($key === 'pending') {
                continue;
            }
            if (is_array($value)) {
                $texts += $this->texts($value, "{$path}.{$key}");
            } elseif (is_string($value)) {
                $texts["{$path}.{$key}"] = $value;
            }
        }
        return $texts;
    }

    private function sectionText(string $document): string
    {
        return implode("\n", $this->texts($this->policy[$document]['sections'] ?? [], $document));
    }

    private function institutionalEmail(): string
    {
        foreach ($this->profile['contact_channels'] ?? [] as $channel) {
            if (($channel['channel_type'] ?? '') === 'email') {
                return (string) $channel['value'];
            }
        }
        throw new \AssertionError('firm-profile.json não tem canal de e-mail.');
    }

    public function testLegalBasesAreArticle7Hypotheses(): void
    {
        $text = $this->sectionText('privacy_policy');
        preg_match_all('/art\. 7º, ([IVX]+)\b/u', $text, $matches);
        if (empty($matches[1])) {
            throw new \AssertionError('A Política de Privacidade não indica a base legal (art. 7º da LGPD) das finalidades.');
        }
        foreach ($matches[1] as $roman) {
            if (!in_array($roman, self::ART_7_HYPOTHESES, true)) {
                throw new \AssertionError("Base legal inexistente citada: art. 7º, {$roman}.");
            }
        }
    }

    public function testEveryPurposeHasALegalBasis(): void
    {
        foreach ($this->policy['privacy_policy']['sections'] as $section) {
            foreach ($section['table']['rows'] ?? [] as $row) {
                if (($section['table']['columns'][2] ?? '') === 'Base legal' && !preg_match('/art\. 7º, [IVX]+/u', (string) ($row[2] ?? ''))) {
                    throw new \AssertionError("Finalidade sem base legal do art. 7º: '{$row[0]}'.");
                }
            }
        }
    }

    public function testAllArticle18RightsAreListed(): void
    {
        $text = mb_strtolower($this->sectionText('privacy_policy'));
        foreach (self::ART_18_RIGHTS as $inciso => $needle) {
            if (!str_contains($text, mb_strtolower($needle))) {
                throw new \AssertionError("Direito do art. 18, {$inciso}, da LGPD ausente da Política de Privacidade ('{$needle}').");
            }
        }
    }

    public function testDataProtectionOfficerIsIdentifiedPublicly(): void
    {
        $dpo = $this->policy['data_protection_officer'] ?? [];
        $validator = new EthicalContentValidator();
        $errors = $validator->validateProfessionalIdentification(['name' => $dpo['name'] ?? '', 'oab' => $dpo['oab_number'] ?? '']);
        if (!empty($errors)) {
            throw new \AssertionError('Encarregada com identificação inválida: ' . implode('; ', $errors));
        }

        $partner = null;
        foreach ($this->profile['partners'] ?? [] as $p) {
            if ($p['name'] === ($dpo['name'] ?? null)) {
                $partner = $p;
            }
        }
        if ($partner === null || $partner['oab_number'] !== $dpo['oab_number']) {
            throw new \AssertionError('A encarregada deve ser sócia cadastrada em firm-profile.json, com a mesma inscrição na OAB.');
        }

        if (($dpo['email'] ?? '') !== $this->institutionalEmail()) {
            throw new \AssertionError('O e-mail da encarregada deve ser o canal de e-mail de firm-profile.json.');
        }

        // Art. 41, § 1º: identidade e contato divulgados publicamente.
        $text = $this->sectionText('privacy_policy');
        if (!str_contains($text, $dpo['name']) || !str_contains($text, $dpo['email'])) {
            throw new \AssertionError('A Política de Privacidade deve publicar o nome e o e-mail da encarregada (art. 41, § 1º).');
        }
    }

    public function testRetentionPeriodsAreStatedInTheTexts(): void
    {
        $months = (int) ($this->policy['retention']['contact_without_engagement_months'] ?? 0);
        $logs = (int) ($this->policy['retention']['access_logs_months'] ?? 0);
        if ($months < 1 || $logs < 1) {
            throw new \AssertionError('Prazos de retenção ausentes.');
        }
        foreach (['privacy_policy', 'terms_of_use'] as $document) {
            if (!str_contains($this->sectionText($document), "{$months} meses")) {
                throw new \AssertionError("O prazo de retenção de {$months} meses deve constar em '{$document}'.");
            }
        }
        if (!str_contains($this->sectionText('privacy_policy'), "{$logs} meses")) {
            throw new \AssertionError("O prazo de {$logs} meses dos registros de acesso deve constar na Política de Privacidade.");
        }
    }

    public function testInformationalBannerOnlyWithStrictlyNecessaryCookies(): void
    {
        $mode = $this->policy['cookie_banner']['mode'] ?? '';
        $categories = $this->policy['cookie_policy']['cookie_categories_in_use'] ?? [];
        if ($mode === 'informativo' && $categories !== ['estritamente_necessarios']) {
            throw new \AssertionError('Banner informativo (sem consentimento) só é admitido com cookies estritamente necessários.');
        }
        if (!str_contains((string) ($this->policy['cookie_banner']['link_href'] ?? ''), '/privacidade')) {
            throw new \AssertionError('O banner de cookies deve levar à Política de Privacidade.');
        }
    }

    public function testTermsStateNoLegalAdviceAndNoAttorneyClientRelationship(): void
    {
        $text = $this->sectionText('terms_of_use');
        foreach (['não estabelece relação advogado-cliente', 'consulta jurídica', '205/2021'] as $needle) {
            if (!str_contains($text, $needle)) {
                throw new \AssertionError("Os Termos de Uso devem conter '{$needle}'.");
            }
        }
    }

    public function testTextsHaveNoProhibitedExpressions(): void
    {
        $rules = $this->readJson(dirname(__DIR__, 2) . '/data/ethical-compliance-rules.json');
        $validator = new EthicalContentValidator($rules['prohibited_expressions'] ?? []);
        foreach ($this->texts($this->policy, 'privacy-policy.json') as $path => $text) {
            $violations = $validator->validateText($text);
            if (!empty($violations)) {
                throw new \AssertionError("Expressão vedada em {$path}: '{$violations[0]['term']}'.");
            }
        }
    }

    public function testApprovedVersionHasNoPlaceholders(): void
    {
        if (($this->policy['status'] ?? '') !== 'aprovado') {
            return;
        }
        $raw = json_encode($this->policy, JSON_UNESCAPED_UNICODE);
        if (!empty($this->policy['pending']) || str_contains((string) $raw, '[A DEFINIR')) {
            throw new \AssertionError('Os textos de /privacidade estão aprovados, mas ainda têm pendências ou marcadores [A DEFINIR].');
        }
    }
}
