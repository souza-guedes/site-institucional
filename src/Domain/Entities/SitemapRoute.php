<?php

declare(strict_types=1);

namespace App\Domain\Entities;

/**
 * Entidade que modela uma rota canônica no mapa de navegação institucional.
 */
final readonly class SitemapRoute
{
    public function __construct(
        public string $path,
        public string $controller,
        public string $action,
        public string $title,
        public bool $navVisible,
        public string $complianceNotes = ''
    ) {}
}
