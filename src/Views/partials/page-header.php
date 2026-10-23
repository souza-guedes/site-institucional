<?php
/**
 * Cabeçalho das páginas internas (catálogo 3.3, "Hero e cabeçalho de página").
 *
 * @var array<array{0: string, 1: ?string}> $crumbs  [rótulo, href]; o último item é a página atual
 * @var string $eyebrow
 * @var string $title     H1 da página (.display)
 * @var ?string $lead
 * @var ?string $extra    HTML adicional abaixo do título (aviso, tags)
 */
$lead ??= null;
$extra ??= null;
?>
<div class="phead">
    <div class="wrap phead__in">
        <nav class="crumbs" aria-label="Você está em">
            <ol>
                <?php foreach ($crumbs as $i => [$label, $href]): ?>
                    <?php if ($i === array_key_last($crumbs)): ?>
                        <li aria-current="page"><?= e($label) ?></li>
                    <?php else: ?>
                        <li><a href="<?= e($href) ?>"><?= e($label) ?></a></li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ol>
        </nav>
        <p class="eyebrow"><?= e($eyebrow) ?></p>
        <h1 class="display"><?= e($title) ?></h1>
        <?php if ($lead !== null): ?><p class="lead muted"><?= e($lead) ?></p><?php endif; ?>
        <?= $extra ?? '' ?>
    </div>
    <div class="phead__comp" aria-hidden="true"><span class="bar"></span><span class="seal of"></span></div>
</div>
