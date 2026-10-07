<?php

declare(strict_types=1);

namespace App\Domain\Entities;

/**
 * Entidade que modela uma Área de Atuação e sua respectiva taxonomia jurídica.
 */
final readonly class TaxonomyArea
{
    /**
     * @param array<int, string> $targetAudience
     * @param array<int, string> $subDisciplines
     * @param array<string, array<int, string>> $proceduralTracks
     * @param array<int, string> $prohibitedTerms
     * @param array<int, string> $recommendedTerms
     */
    public function __construct(
        public string $id,
        public string $slug,
        public string $title,
        public array $targetAudience,
        public array $subDisciplines,
        public array $proceduralTracks,
        public array $prohibitedTerms = [],
        public array $recommendedTerms = []
    ) {}

    /**
     * Verifica se a área contempla o rito processual indicado (ex: 'judicial' ou 'extrajudicial').
     */
    public function hasProceduralTrack(string $track): bool
    {
        return isset($this->proceduralTracks[$track]) && !empty($this->proceduralTracks[$track]);
    }
}
