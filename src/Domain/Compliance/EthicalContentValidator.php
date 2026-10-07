<?php

declare(strict_types=1);

namespace App\Domain\Compliance;

/**
 * Validador de Conformidade Ética com o Provimento CFOAB nº 205/2021.
 * Inspeciona textos, metadados, identificação profissional e disclaimers institucionais.
 */
final class EthicalContentValidator
{
    /**
     * @param list<array{term: string, category?: string, severity?: string, rationale?: string, article_reference?: string}|string> $prohibitedExpressions
     */
    public function __construct(
        private readonly array $prohibitedExpressions = [],
        private readonly bool $requireOabRegistration = true
    ) {}

    /**
     * Valida um texto contra termos e expressões vedadas pelo regramento ético.
     * Retorna a lista de violações identificadas.
     *
     * @return list<array{term: string, category: string, severity: string, position: int, rationale: string, article_reference: string}>
     */
    public function validateText(string $text): array
    {
        $violations = [];
        $normalizedText = mb_strtolower($text, 'UTF-8');

        foreach ($this->prohibitedExpressions as $expr) {
            $term = is_array($expr) ? (string) ($expr['term'] ?? '') : (string) $expr;
            if (trim($term) === '') {
                continue;
            }

            $normalizedTerm = mb_strtolower(trim($term), 'UTF-8');
            $pos = mb_stripos($normalizedText, $normalizedTerm, 0, 'UTF-8');

            if ($pos !== false) {
                $category = is_array($expr) ? ($expr['category'] ?? 'mercantilizacao') : 'mercantilizacao';
                $severity = is_array($expr) ? ($expr['severity'] ?? 'HIGH') : 'HIGH';
                $rationale = is_array($expr) ? ($expr['rationale'] ?? 'Expressão vedada pelo regramento ético da OAB.') : 'Expressão vedada.';
                $articleRef = is_array($expr) ? ($expr['article_reference'] ?? 'Art. 4º') : 'Art. 4º';

                $violations[] = [
                    'term' => $term,
                    'category' => (string) $category,
                    'severity' => (string) $severity,
                    'position' => (int) $pos,
                    'rationale' => (string) $rationale,
                    'article_reference' => (string) $articleRef,
                ];
            }
        }

        return $violations;
    }

    /**
     * Valida se a apresentação do advogado atende ao Art. 3º do Provimento 205/2021:
     * - Nome completo (prenome e ao menos um sobrenome);
     * - Número de inscrição na Seccional da OAB válido.
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
                $errors[] = 'O nome do advogado deve ser completo (Art. 3º do Provimento CFOAB nº 205/2021).';
            }
        }

        if ($this->requireOabRegistration) {
            $oab = trim((string) ($professionalData['oab'] ?? $professionalData['oab_number'] ?? $professionalData['oab_registration'] ?? ''));
            if ($oab === '') {
                $errors[] = 'O número de inscrição na OAB com a respectiva Seccional é obrigatório.';
            } else {
                // Deve conter indicação de OAB ou UF e dígitos
                $hasOabOrUf = (bool) preg_match('/OAB|[A-Z]{2}/i', $oab);
                $hasDigits = (bool) preg_match('/\d{3,}/', $oab);
                if (!$hasOabOrUf || !$hasDigits) {
                    $errors[] = "Inscrição da OAB inválida: '{$oab}'. Formato exigido: 'OAB/UF nº XXXXX'.";
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
