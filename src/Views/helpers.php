<?php

declare(strict_types=1);

/**
 * Funções de apoio das views (WDLC 5.1).
 * Os dados compartilhados (firm-profile, artigos, privacidade, taxonomia e rota atual)
 * são preenchidos pelo BaseController a cada renderização.
 */
final class ViewData
{
    /** @var array<string, mixed> */
    public static array $shared = [];
}

/** Escapa texto para HTML. */
function e(mixed $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/** Inclui um partial de src/Views/partials com os dados compartilhados e as variáveis indicadas. */
function partial(string $name, array $vars = []): void
{
    $__partialFile = __DIR__ . '/partials/' . $name . '.php';
    extract(ViewData::$shared);
    extract($vars);
    include $__partialFile;
}

/** Atributo aria-current para o item de navegação da seção atual. */
function nav_current(string $section): string
{
    return (ViewData::$shared['nav'] ?? '') === $section ? ' aria-current="page"' : '';
}

/** @return array<string, mixed>|null */
function area_by_slug(string $slug): ?array
{
    foreach (ViewData::$shared['firm']['practice_areas'] ?? [] as $area) {
        if ($area['slug'] === $slug) {
            return $area;
        }
    }
    return null;
}

/** @return array<string, mixed>|null Disciplinas e ritos da área em sitemap-taxonomy.json. */
function taxonomy_by_slug(string $slug): ?array
{
    foreach (ViewData::$shared['taxonomy']['practice_areas_taxonomy'] ?? [] as $area) {
        if ($area['slug'] === $slug) {
            return $area;
        }
    }
    return null;
}

/** @return array<string, mixed>|null */
function partner_by_name(string $name): ?array
{
    foreach (ViewData::$shared['firm']['partners'] ?? [] as $partner) {
        if ($partner['name'] === $name) {
            return $partner;
        }
    }
    return null;
}

/** "Sócio fundador" ou "Sócia fundadora", conforme o cargo cadastrado. */
function partner_title(array $partner): string
{
    return ($partner['role'] ?? '') === 'Advogada' ? 'Sócia fundadora' : 'Sócio fundador';
}

/** Nome curto da área, usado em tags e filtros. */
function area_short(string $slug): string
{
    return [
        'direito-imobiliario' => 'Imobiliário',
        'direito-de-saude' => 'Saúde',
        'direito-do-consumidor' => 'Consumidor',
        'familia-e-sucessoes' => 'Família',
    ][$slug] ?? '';
}

/** Data AAAA-MM-DD em DD/MM/AAAA. */
function format_date(?string $isoDate): string
{
    if ($isoDate === null || !preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $isoDate, $m)) {
        return '';
    }
    return "{$m[3]}/{$m[2]}/{$m[1]}";
}

/** Data de exibição do artigo: publicação ou, enquanto rascunho, última atualização. */
function article_date(array $article): string
{
    return format_date($article['published_at'] ?? $article['updated_at'] ?? null);
}

/** Canal de contato de firm-profile.json por tipo (email, phone, whatsapp, in_person). */
function channel(string $type): ?array
{
    foreach (ViewData::$shared['firm']['contact_channels'] ?? [] as $channel) {
        if ($channel['channel_type'] === $type) {
            return $channel;
        }
    }
    return null;
}

/** Ícones de traço (1,5px) das áreas de atuação e dos canais, os mesmos do protótipo. */
function icon(string $key): string
{
    $paths = [
        'direito-imobiliario' => '<path d="M3 11l9-7 9 7"/><path d="M5 10v10h14V10"/><path d="M10 20v-6h4v6"/>',
        'direito-de-saude' => '<path d="M12 20s-7-4.4-7-9.6A3.9 3.9 0 0 1 12 8a3.9 3.9 0 0 1 7 2.4C19 15.6 12 20 12 20z"/><path d="M7.5 12.5h2.6l1.2-2 1.8 3.6 1.1-1.6h2.3"/>',
        'direito-do-consumidor' => '<path d="M6 3h12v18l-3-2-3 2-3-2-3 2z"/><path d="M9 8h6M9 12h6M9 16h3"/>',
        'familia-e-sucessoes' => '<circle cx="9" cy="8" r="3"/><circle cx="17" cy="10" r="2.2"/><path d="M3 20c0-3.3 2.7-6 6-6s6 2.7 6 6"/><path d="M15.4 15.4c.5-.3 1-.4 1.6-.4 2.2 0 4 1.8 4 4"/>',
        'phone' => '<path d="M5 4h4l2 5-2.5 1.5a11 11 0 0 0 5 5L15 13l5 2v4a2 2 0 0 1-2 2A16 16 0 0 1 3 6a2 2 0 0 1 2-2"/>',
        'mail' => '<rect x="3" y="5" width="18" height="14"/><path d="M3 7l9 6 9-6"/>',
        'pin' => '<path d="M12 21s-7-6.2-7-11.5a7 7 0 0 1 14 0C19 14.8 12 21 12 21z"/><circle cx="12" cy="9.5" r="2.5"/>',
        'clock' => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>',
    ];
    return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">'
        . ($paths[$key] ?? '') . '</svg>';
}
