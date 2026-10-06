<?php

declare(strict_types=1);

namespace Tests\Unit;

class FirmProfileSchemaTest
{
    private array $data;
    private array $schema;

    public function __construct()
    {
        $dataPath = dirname(__DIR__, 2) . '/data/firm-profile.json';
        $schemaPath = dirname(__DIR__, 2) . '/schemas/firm-profile.schema.json';

        if (!file_exists($dataPath)) {
            throw new \RuntimeException("O arquivo de dados 'data/firm-profile.json' não foi encontrado.");
        }

        if (!file_exists($schemaPath)) {
            throw new \RuntimeException("O arquivo de schema 'schemas/firm-profile.schema.json' não foi encontrado.");
        }

        $dataContent = file_get_contents($dataPath);
        $schemaContent = file_get_contents($schemaPath);

        $this->data = json_decode($dataContent, true, 512, JSON_THROW_ON_ERROR);
        $this->schema = json_decode($schemaContent, true, 512, JSON_THROW_ON_ERROR);
    }

    public function testRequiredRootKeysExist(): void
    {
        $requiredKeys = $this->schema['required'] ?? [];
        foreach ($requiredKeys as $key) {
            if (!array_key_exists($key, $this->data)) {
                throw new \AssertionError("Chave raiz obrigatória ausente: '{$key}'");
            }
        }
    }

    public function testFirmDataStructure(): void
    {
        $firm = $this->data['firm'] ?? [];
        $requiredFirm = ['corporate_name', 'trade_name', 'oab_registration', 'foundation_year', 'mission', 'vision', 'values', 'address'];

        foreach ($requiredFirm as $fKey) {
            if (!array_key_exists($fKey, $firm)) {
                throw new \AssertionError("Chave de 'firm' obrigatória ausente: '{$fKey}'");
            }
        }

        if (!is_int($firm['foundation_year']) || $firm['foundation_year'] < 1900) {
            throw new \AssertionError("Ano de fundação inválido: {$firm['foundation_year']}");
        }

        if (!is_array($firm['values']) || count($firm['values']) < 3) {
            throw new \AssertionError("A banca deve conter pelo menos 3 valores institucionais.");
        }

        $address = $firm['address'] ?? [];
        $requiredAddress = ['street', 'number', 'complement', 'neighborhood', 'city', 'state', 'postal_code'];
        foreach ($requiredAddress as $aKey) {
            if (empty($address[$aKey])) {
                throw new \AssertionError("Campo de endereço obrigatório vazio ou ausente: '{$aKey}'");
            }
        }

        if (!preg_match('/^[A-Z]{2}$/', $address['state'])) {
            throw new \AssertionError("UF do estado deve conter 2 letras maiúsculas: '{$address['state']}'");
        }
    }

    public function testPartnersDataStructure(): void
    {
        $partners = $this->data['partners'] ?? [];
        if (!is_array($partners) || count($partners) < 2) {
            throw new \AssertionError("Devem ser informados pelo menos 2 sócios fundadores.");
        }

        foreach ($partners as $index => $partner) {
            $required = ['name', 'oab_number', 'role', 'primary_area', 'bio'];
            foreach ($required as $pKey) {
                if (empty($partner[$pKey])) {
                    throw new \AssertionError("Sócio índice {$index} com campo obrigatório ausente: '{$pKey}'");
                }
            }
            if (!preg_match('/^OAB\/[A-Z]{2}/', $partner['oab_number'])) {
                throw new \AssertionError("Número OAB do sócio {$partner['name']} em formato inválido: '{$partner['oab_number']}'");
            }
        }
    }

    public function testPracticeAreasContainRequiredDisciplines(): void
    {
        $practiceAreas = $this->data['practice_areas'] ?? [];
        if (!is_array($practiceAreas) || count($practiceAreas) < 4) {
            throw new \AssertionError("Devem existir pelo menos 4 especialidades jurídicas mapeadas.");
        }

        $expectedSlugs = [
            'direito-imobiliario',
            'direito-de-saude',
            'direito-do-consumidor',
            'familia-e-sucessoes'
        ];

        $presentSlugs = array_column($practiceAreas, 'slug');
        foreach ($expectedSlugs as $slug) {
            if (!in_array($slug, $presentSlugs, true)) {
                throw new \AssertionError("Especialidade nuclear obrigatória ausente no dataset: '{$slug}'");
            }
        }

        foreach ($practiceAreas as $area) {
            if (empty($area['scope_items']) || count($area['scope_items']) < 3) {
                throw new \AssertionError("A área '{$area['slug']}' deve detalhar pelo menos 3 itens de escopo.");
            }
        }
    }

    public function testPersonasContainPJandPF(): void
    {
        $personas = $this->data['personas'] ?? [];
        if (!is_array($personas) || count($personas) < 4) {
            throw new \AssertionError("Devem ser modeladas pelo menos 4 personas de clientes.");
        }

        $pjCount = 0;
        $pfCount = 0;
        foreach ($personas as $persona) {
            if ($persona['type'] === 'PJ') {
                $pjCount++;
            } elseif ($persona['type'] === 'PF') {
                $pfCount++;
            }
            if (empty($persona['pain_points']) || count($persona['pain_points']) < 2) {
                throw new \AssertionError("Persona '{$persona['id']}' deve ter ao menos 2 dores listadas.");
            }
        }

        if ($pjCount < 2 || $pfCount < 2) {
            throw new \AssertionError("Devem existir pelo menos 2 personas PJ e 2 personas PF (atual: PJ={$pjCount}, PF={$pfCount})");
        }
    }

    public function testComplianceDisclaimerOAB(): void
    {
        $disclaimer = $this->data['compliance_disclaimer'] ?? '';
        if (mb_strlen($disclaimer) < 30) {
            throw new \AssertionError("O disclaimer de compliance ético OAB está muito curto ou ausente.");
        }

        $forbiddenTerms = [
            'melhor escritório',
            'líderes de mercado',
            'garantia de causa',
            'resultado garantido',
            'tabela de preços',
            'honorários promocionais'
        ];

        $jsonAll = json_encode($this->data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        foreach ($forbiddenTerms as $term) {
            if (stripos($jsonAll, $term) !== false) {
                throw new \AssertionError("Termo antiético vedado pelo Provimento OAB 205/2021 detectado: '{$term}'");
            }
        }
    }

    public function testContactChannels(): void
    {
        $channels = $this->data['contact_channels'] ?? [];
        if (!is_array($channels) || count($channels) < 3) {
            throw new \AssertionError("Devem existir pelo menos 3 canais de atendimento formalizados.");
        }

        $types = array_column($channels, 'channel_type');
        if (!in_array('email', $types, true) || !in_array('phone', $types, true) || !in_array('whatsapp', $types, true)) {
            throw new \AssertionError("Canais fundamentais ausentes (necessário: email, phone e whatsapp).");
        }
    }
}
