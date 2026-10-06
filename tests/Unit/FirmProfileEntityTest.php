<?php

declare(strict_types=1);

namespace Tests\Unit;

require_once dirname(__DIR__, 2) . '/src/Domain/Entities/FirmProfile.php';

use App\Domain\Entities\FirmProfile;
use App\Domain\Entities\PracticeArea;
use App\Domain\Entities\Partner;
use App\Domain\Entities\ClientPersona;
use App\Domain\Entities\ContactChannel;

class FirmProfileEntityTest
{
    private FirmProfile $profile;

    public function __construct()
    {
        $dataPath = dirname(__DIR__, 2) . '/data/firm-profile.json';
        $this->profile = FirmProfile::loadFromJsonFile($dataPath);
    }

    public function testFirmBasicProperties(): void
    {
        if ($this->profile->corporateName !== 'Souza Guedes Advogados') {
            throw new \AssertionError("Nome corporativo inesperado: {$this->profile->corporateName}");
        }

        if ($this->profile->tradeName !== 'Souza Guedes Advogados') {
            throw new \AssertionError("Nome fantasia inesperado: {$this->profile->tradeName}");
        }

        if ($this->profile->foundationYear !== 2026) {
            throw new \AssertionError("Ano de fundação inesperado: {$this->profile->foundationYear}");
        }

        if (count($this->profile->values) < 3) {
            throw new \AssertionError("Menos de 3 valores institucionais carregados.");
        }
    }

    public function testPartnersInstantiation(): void
    {
        if (count($this->profile->partners) < 2) {
            throw new \AssertionError("Menos de 2 sócios fundadores instanciados.");
        }

        $names = array_map(fn($partner) => $partner->name, $this->profile->partners);
        foreach (['Rodrigo Guedes da Silva', 'Lays Regina de Souza'] as $expectedName) {
            if (!in_array($expectedName, $names, true)) {
                throw new \AssertionError("Sócio fundador ausente: {$expectedName}");
            }
        }

        foreach ($this->profile->partners as $partner) {
            if (!$partner instanceof Partner) {
                throw new \AssertionError("Elemento não é instância de Partner.");
            }
            if (empty($partner->name) || empty($partner->oabNumber)) {
                throw new \AssertionError("Sócio com dados essenciais vazios.");
            }
        }
    }

    public function testPracticeAreasInstantiation(): void
    {
        if (count($this->profile->practiceAreas) < 4) {
            throw new \AssertionError("Menos de 4 áreas de atuação instanciadas.");
        }

        $slugs = array_map(fn($a) => $a->slug, $this->profile->practiceAreas);
        foreach (['direito-imobiliario', 'direito-de-saude', 'direito-do-consumidor', 'familia-e-sucessoes'] as $slug) {
            if (!in_array($slug, $slugs, true)) {
                throw new \AssertionError("Especialidade ausente: {$slug}");
            }
        }
    }

    public function testPersonasInstantiation(): void
    {
        if (count($this->profile->personas) < 4) {
            throw new \AssertionError("Menos de 4 personas instanciadas.");
        }

        foreach ($this->profile->personas as $persona) {
            if (!$persona instanceof ClientPersona) {
                throw new \AssertionError("Elemento não é instância de ClientPersona.");
            }
            if (!in_array($persona->type, ['PJ', 'PF'], true)) {
                throw new \AssertionError("Tipo de persona inválido: {$persona->type}");
            }
        }
    }

    public function testContactChannelsInstantiation(): void
    {
        if (count($this->profile->contactChannels) < 3) {
            throw new \AssertionError("Menos de 3 canais de contato instanciados.");
        }

        foreach ($this->profile->contactChannels as $channel) {
            if (!$channel instanceof ContactChannel) {
                throw new \AssertionError("Elemento não é instância de ContactChannel.");
            }
        }
    }
}
