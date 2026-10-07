<?php

declare(strict_types=1);

namespace App\Domain\Taxonomy;

/**
 * Validador ético da taxonomia de áreas de atuação.
 * Garante conformidade com o Provimento CFOAB nº 205/2021 e veda expressões mercantilistas e superlativos.
 */
final class TaxonomyValidator
{
    /** @var array<int, string> */
    private array $globalProhibitedTerms = [
        'especialista',
        'especialistas',
        'o melhor',
        'os melhores',
        'líder',
        'líderes',
        'resultado garantido',
        'causa ganha',
        'liminar garantida',
        'liminar imediata sem risco',
        'solução mágica',
        'risco zero',
        'sem risco',
        'indenização certa',
        'indenização garantida',
        'preço popular',
        'consulta grátis',
        'limpe seu nome',
        'divórcio relâmpago'
    ];

    /**
     * Valida um registro de taxonomia de área de atuação e retorna lista de violações encontradas.
     *
     * @param array<string, mixed> $areaData
     * @return array<int, string>
     */
    public function validateTaxonomyArea(array $areaData): array
    {
        $violations = [];

        // Coleta todos os textos a serem inspecionados
        $textsToInspect = [];

        if (isset($areaData['title']) && is_string($areaData['title'])) {
            $textsToInspect[] = "Título: " . $areaData['title'];
        }

        if (isset($areaData['sub_disciplines']) && is_array($areaData['sub_disciplines'])) {
            foreach ($areaData['sub_disciplines'] as $sub) {
                if (is_string($sub)) {
                    $textsToInspect[] = "Sub-disciplina: " . $sub;
                }
            }
        }

        if (isset($areaData['procedural_tracks']) && is_array($areaData['procedural_tracks'])) {
            foreach ($areaData['procedural_tracks'] as $trackName => $items) {
                if (is_array($items)) {
                    foreach ($items as $item) {
                        if (is_string($item)) {
                            $textsToInspect[] = "Rito {$trackName}: " . $item;
                        }
                    }
                }
            }
        }

        // Lista de termos vedados nesta área específica + lista global
        $prohibitedList = $this->globalProhibitedTerms;
        if (isset($areaData['prohibited_terms']) && is_array($areaData['prohibited_terms'])) {
            foreach ($areaData['prohibited_terms'] as $term) {
                if (is_string($term)) {
                    $prohibitedList[] = $term;
                }
            }
        }

        // Inspeção de cada texto coletado
        foreach ($textsToInspect as $labeledText) {
            $lowerText = mb_strtolower($labeledText, 'UTF-8');

            foreach ($prohibitedList as $term) {
                $lowerTerm = mb_strtolower(trim($term), 'UTF-8');
                if ($lowerTerm === '') {
                    continue;
                }

                // Cria padrão regex com limite de palavra
                $pattern = '/(?:\b|^)' . preg_quote($lowerTerm, '/') . '(?:\b|$)/iu';

                if (preg_match($pattern, $lowerText)) {
                    $violations[] = "Termo proibido detectado: '{$term}' em [{$labeledText}]";
                }
            }
        }

        return $violations;
    }
}
