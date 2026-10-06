<?php

declare(strict_types=1);

namespace App\Domain\Entities;

readonly class PracticeArea
{
    /**
     * @param array<string> $scopeItems
     * @param array<string> $targetAudience
     */
    public function __construct(
        public string $id,
        public string $slug,
        public string $title,
        public string $focus,
        public string $summary,
        public array $scopeItems,
        public array $targetAudience
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            slug: $data['slug'],
            title: $data['title'],
            focus: $data['focus'],
            summary: $data['summary'],
            scopeItems: $data['scope_items'] ?? [],
            targetAudience: $data['target_audience'] ?? []
        );
    }
}

readonly class Partner
{
    public function __construct(
        public string $name,
        public string $oabNumber,
        public string $role,
        public string $primaryArea,
        public string $bio
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            oabNumber: $data['oab_number'],
            role: $data['role'],
            primaryArea: $data['primary_area'],
            bio: $data['bio']
        );
    }
}

readonly class ClientPersona
{
    /**
     * @param array<string> $painPoints
     * @param array<string> $legalNeeds
     * @param array<string> $preferredChannels
     */
    public function __construct(
        public string $id,
        public string $type,
        public string $title,
        public string $demographics,
        public array $painPoints,
        public array $legalNeeds,
        public array $preferredChannels
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            type: $data['type'],
            title: $data['title'],
            demographics: $data['demographics'],
            painPoints: $data['pain_points'] ?? [],
            legalNeeds: $data['legal_needs'] ?? [],
            preferredChannels: $data['preferred_channels'] ?? []
        );
    }
}

readonly class ContactChannel
{
    public function __construct(
        public string $channelType,
        public string $label,
        public string $value,
        public string $actionUri,
        public string $availability
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            channelType: $data['channel_type'],
            label: $data['label'],
            value: $data['value'],
            actionUri: $data['action_uri'],
            availability: $data['availability']
        );
    }
}

readonly class FirmProfile
{
    /**
     * @param array<string> $values
     * @param array<string, string> $address
     * @param array<Partner> $partners
     * @param array<PracticeArea> $practiceAreas
     * @param array<ClientPersona> $personas
     * @param array<ContactChannel> $contactChannels
     */
    public function __construct(
        public string $corporateName,
        public string $tradeName,
        public string $oabRegistration,
        public int $foundationYear,
        public string $mission,
        public string $vision,
        public array $values,
        public array $address,
        public array $partners,
        public array $practiceAreas,
        public array $personas,
        public array $contactChannels,
        public string $complianceDisclaimer
    ) {}

    public static function loadFromJsonFile(string $filePath): self
    {
        if (!file_exists($filePath)) {
            throw new \InvalidArgumentException("Arquivo de dados não encontrado: {$filePath}");
        }

        $raw = file_get_contents($filePath);
        $data = json_decode($raw, true, 512, JSON_THROW_ON_ERROR);

        $firm = $data['firm'];
        $partners = array_map(fn($p) => Partner::fromArray($p), $data['partners'] ?? []);
        $practiceAreas = array_map(fn($a) => PracticeArea::fromArray($a), $data['practice_areas'] ?? []);
        $personas = array_map(fn($p) => ClientPersona::fromArray($p), $data['personas'] ?? []);
        $channels = array_map(fn($c) => ContactChannel::fromArray($c), $data['contact_channels'] ?? []);

        return new self(
            corporateName: $firm['corporate_name'],
            tradeName: $firm['trade_name'],
            oabRegistration: $firm['oab_registration'],
            foundationYear: $firm['foundation_year'],
            mission: $firm['mission'],
            vision: $firm['vision'],
            values: $firm['values'],
            address: $firm['address'],
            partners: $partners,
            practiceAreas: $practiceAreas,
            personas: $personas,
            contactChannels: $channels,
            complianceDisclaimer: $data['compliance_disclaimer']
        );
    }
}
