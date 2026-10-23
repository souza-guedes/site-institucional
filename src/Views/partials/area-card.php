<?php
/**
 * Card de Área de Atuação (catálogo 3.3). O card inteiro é o link para o detalhe.
 *
 * @var array $area     item de practice_areas (firm-profile.json)
 * @var string $variant 'compacto' (Home) ou 'largo' (página Áreas de Atuação, com subáreas e público)
 */
$variant ??= 'compacto';
$href = '/areas-de-atuacao/' . $area['slug'];
?>
<?php if ($variant === 'largo'): ?>
    <?php $taxonomyArea = taxonomy_by_slug($area['slug']); ?>
    <a class="acard acard--wide of" href="<?= e($href) ?>" data-publico="<?= e(implode(' ', $area['audience_types'])) ?>">
        <span class="acard__icon"><?= icon($area['slug']) ?></span>
        <div class="grid gap-[10px]"><h2 class="h3"><?= e($area['title']) ?></h2><p><?= e($area['summary']) ?></p></div>
        <ul>
            <?php foreach (array_slice($taxonomyArea['sub_disciplines'] ?? [], 0, 4) as $sub): ?>
                <li><?= e($sub) ?></li>
            <?php endforeach; ?>
        </ul>
        <div class="tags">
            <?php foreach ($area['audience_types'] as $type): ?>
                <span class="tag"><?= $type === 'pf' ? 'Pessoas físicas' : 'Pessoas jurídicas' ?></span>
            <?php endforeach; ?>
        </div>
        <span class="acard__more">Ver escopo de atuação</span>
    </a>
<?php else: ?>
    <a class="acard of" href="<?= e($href) ?>">
        <span class="acard__icon"><?= icon($area['slug']) ?></span>
        <h3 class="h3"><?= e($area['title']) ?></h3>
        <p><?= e($area['summary']) ?></p>
        <span class="acard__more">Ver detalhes</span>
    </a>
<?php endif; ?>
