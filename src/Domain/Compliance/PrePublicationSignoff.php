<?php

declare(strict_types=1);

namespace App\Domain\Compliance;

/**
 * Representa o Termo Interno de Validação Jurídica Pré-Publicação.
 * Garante a governança de aprovação formal pelos sócios antes da veiculação de qualquer conteúdo.
 */
final readonly class PrePublicationSignoff
{
    public const MANDATORY_CHECKLIST_ITEMS = [
        'identificacao_completa_advogados',
        'ausencia_termos_superlativos_e_mercantis',
        'sobriedade_visual_e_botoes_contato',
        'carater_informativo_sem_promessa_resultado'
    ];

    public const AUTHORIZED_APPROVERS = [
        'Rodrigo Guedes da Silva',
        'Lays Regina de Souza'
    ];

    /**
     * @param list<string> $approverNames
     * @param list<string> $checkedItems
     */
    public function __construct(
        public string $contentId,
        public string $contentTitle,
        public \DateTimeImmutable $signoffDate,
        public array $approverNames,
        public array $checkedItems,
        public bool $isApproved,
        public ?string $notes = null
    ) {}

    /**
     * Valida se todos os critérios formais para publicação foram estritamente cumpridos.
     */
    public function isValid(): bool
    {
        if (!$this->isApproved) {
            return false;
        }

        if (trim($this->contentId) === '' || trim($this->contentTitle) === '') {
            return false;
        }

        // Verifica se há pelo menos um aprovador autorizado (sócio fundador)
        $hasAuthorizedApprover = false;
        foreach ($this->approverNames as $approver) {
            if (in_array(trim($approver), self::AUTHORIZED_APPROVERS, true)) {
                $hasAuthorizedApprover = true;
                break;
            }
        }

        if (!$hasAuthorizedApprover) {
            return false;
        }

        // Verifica se todos os itens mandatórios do checklist foram conferidos e marcados
        foreach (self::MANDATORY_CHECKLIST_ITEMS as $item) {
            if (!in_array($item, $this->checkedItems, true)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'content_id' => $this->contentId,
            'content_title' => $this->contentTitle,
            'signoff_date' => $this->signoffDate->format(\DateTimeInterface::ATOM),
            'approver_names' => $this->approverNames,
            'checked_items' => $this->checkedItems,
            'is_approved' => $this->isApproved,
            'notes' => $this->notes,
            'is_valid' => $this->isValid(),
        ];
    }
}
