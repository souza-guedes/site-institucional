<?php

declare(strict_types=1);

namespace App\Domain\Compliance;

/**
 * Entidade de Domínio representando uma regra normativa de conformidade ética da OAB.
 */
final readonly class EthicalComplianceRule
{
    /**
     * @param list<string> $targetAudience
     */
    public function __construct(
        public string $id,
        public string $provimentoArticle,
        public string $title,
        public string $description,
        public array $targetAudience,
        public string $enforcementType
    ) {}

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: (string) ($data['id'] ?? ''),
            provimentoArticle: (string) ($data['provimento_article'] ?? ''),
            title: (string) ($data['title'] ?? ''),
            description: (string) ($data['description'] ?? ''),
            targetAudience: is_array($data['target_audience'] ?? null) ? array_values($data['target_audience']) : [],
            enforcementType: (string) ($data['enforcement_type'] ?? 'manual_review')
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'provimento_article' => $this->provimentoArticle,
            'title' => $this->title,
            'description' => $this->description,
            'target_audience' => $this->targetAudience,
            'enforcement_type' => $this->enforcementType,
        ];
    }
}
