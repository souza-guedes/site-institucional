<?php

declare(strict_types=1);

namespace App\Domain\Compliance;

/**
 * Validador de Conformidade Ética com o Provimento CFOAB nº 205/2021.
 * Inspeciona textos, metadados, identificação profissional dos advogados e disclaimers institucionais.
 */
final class EthicalContentValidator
{
    private const VALID_UFS = [
        'AC', 'AL', 'AP', 'AM', 'BA', 'CE', 'DF', 'ES', 'GO', 'MA',
        'MT', 'MS', 'MG', 'PA', 'PB', 'PR', 'PE', 'PI', 'RJ', 'RN',
        'RS', 'RO', 'RR', 'SC', 'SP', 'SE', 'TO'
    ];

    /**
     * @param list<array{term: string, category?: string, severity?: string, rationale?: string, article_reference?: string}|string> $prohibitedExpressions
     */
    public function __construct(
        private readonly array $prohibitedExpressions = [],
        private readonly bool $requireOabRegistration = true
    ) {}

    /**
     * Valida um texto contra termos e expressões vedadas pelo regramento ético.
     * Utiliza correspondência exata de limites de palavras Unicode para evitar falsos positivos
     * (por exemplo, assegura que 'líder' não case indevidamente com 'liderança').
     *
     * @return list<array{term: string, category: string, severity: string, position: int, rationale: string, article_reference: string}>
     */
    public function validateText(string $text): array
    {
        $violations = [];

        foreach ($this->prohibitedExpressions as $expr) {
            $term = is_array($expr) ? (string) ($expr['term'] ?? '') : (string) $expr;
            $trimmedTerm = trim($term);
            if ($trimmedTerm === '') {
                continue;
            }

            $escapedTerm = preg_quote($trimmedTerm, '/');
            // Limite de palavra Unicode: o caractere antes e depois não pode ser uma letra Unicode
            $pattern = '/(?<!\p{L})' . $escapedTerm . '(?!\p{L})/iu';

            if (preg_match_all($pattern, $text, $matches, PREG_OFFSET_CAPTURE)) {
                $category = is_array($expr) ? ($expr['category'] ?? 'mercantilizacao') : 'mercantilizacao';
                $severity = is_array($expr) ? ($expr['severity'] ?? 'HIGH') : 'HIGH';
                $rationale = is_array($expr) ? ($expr['rationale'] ?? 'Expressão vedada pelo regramento ético da OAB.') : 'Expressão vedada.';
                $articleRef = is_array($expr) ? ($expr['article_reference'] ?? 'Art. 3º, IV') : 'Art. 3º, IV';

                $firstMatch = $matches[0][0];
                $pos = (int) $firstMatch[1];

                $violations[] = [
                    'term' => $term,
                    'category' => (string) $category,
                    'severity' => (string) $severity,
                    'position' => $pos,
                    'rationale' => (string) $rationale,
                    'article_reference' => (string) $articleRef,
                ];
            }
        }

        return $violations;
    }

    /**
     * Valida se a apresentação do advogado atende ao Art. 1º, § 1º, Art. 3º e Anexo Único do Provimento 205/2021:
     * - Nome completo (prenome e ao menos um sobrenome);
     * - Número de inscrição na Seccional da OAB em formato estrito (ex.: OAB/SP nº 538.416 ou OAB/SP 511.204).
     *
     * @param array<string, mixed> $professionalData
     * @return list<string> Lista de mensagens de erro encontradas (vazio se válido)
     */
    public function validateProfessionalIdentification(array $professionalData): array
    {
        $errors = [];

        $name = trim((string) ($professionalData['name'] ?? ''));
        if ($name === '') {
            $errors[] = 'O nome do advogado é obrigatório.';
        } else {
            $parts = preg_split('/\s+/', $name);
            if (!is_array($parts) || count($parts) < 2) {
                $errors[] = 'O nome do advogado deve ser completo (Art. 1º, § 1º c/c Anexo Único do Provimento CFOAB nº 205/2021).';
            }
        }

        if ($this->requireOabRegistration) {
            $oab = trim((string) ($professionalData['oab'] ?? $professionalData['oab_number'] ?? $professionalData['oab_registration'] ?? ''));
            if ($oab === '') {
                $errors[] = 'O número de inscrição na OAB com a respectiva Seccional é obrigatório.';
            } else {
                // Regex estrita: OAB/UF nº XXXXX ou OAB/UF XXXXX (suportando nº, n°, no., n.)
                $strictPattern = '/^OAB\/([A-Z]{2})\s+(?:n[º°o]\.?\s*)?((?:\d{1,3}(?:\.\d{3})*|\d{3,8}))$/iu';
                if (!preg_match($strictPattern, $oab, $matches)) {
                    $errors[] = "Inscrição da OAB inválida: '{$oab}'. Formato estrito exigido: 'OAB/UF nº XXXXX'.";
                } else {
                    $uf = strtoupper($matches[1]);
                    if (!in_array($uf, self::VALID_UFS, true)) {
                        $errors[] = "Seccional da OAB inválida: '{$uf}'.";
                    }
                }
            }
        }

        return $errors;
    }

    /**
     * Valida o disclaimer institucional de conformidade ética da banca.
     */
    public function validateComplianceDisclaimer(string $disclaimer): bool
    {
        $trimmed = trim($disclaimer);
        if (mb_strlen($trimmed) < 40) {
            return false;
        }

        // Deve citar Provimento ou 205/2021 ou Código de Ética
        $hasNormativeReference = (bool) (
            stripos($trimmed, '205/2021') !== false ||
            stripos($trimmed, 'Código de Ética') !== false ||
            stripos($trimmed, 'CFOAB') !== false
        );

        if (!$hasNormativeReference) {
            return false;
        }

        // Não pode conter termos vedados
        $violations = $this->validateText($trimmed);
        return empty($violations);
    }
}
