<?php
/**
 * Bloco de Artigo (catálogo 3.3): tag da área, data, autor e leitura. Imagem decorativa (alt vazio).
 *
 * @var array $article  item de articles (articles.json)
 * @var string $variant 'card' (listas e carrossel) ou 'destaque' (publicação mais recente, seção escura, título em <h2>)
 */
$variant ??= 'card';
$href = '/artigos/' . $article['slug'];
$area = area_by_slug($article['area_slug']);
$author = partner_by_name($article['author']);
$sizes = $variant === 'destaque' ? '(min-width: 1100px) 680px, calc(100vw - 32px)' : '(min-width: 1100px) 380px, (min-width: 760px) 50vw, 85vw';
$image = '<span class="art__img"><img src="' . e($article['image']) . '" srcset="' . e(srcset($article['image'], [900, 720, 480])) . '" sizes="' . $sizes
    . '" alt="" width="900" height="600" loading="lazy"></span>';
?>
<?php if ($variant === 'destaque'): ?>
    <a class="feat" href="<?= e($href) ?>">
        <span class="feat__media"><span class="feat__bar" aria-hidden="true"></span><?= $image ?></span>
        <span class="feat__body">
            <span class="feat__tag"><span><?= e(area_short($article['area_slug'])) ?></span><span><?= e((string) $article['reading_time_minutes']) ?> min de leitura</span></span>
            <h2 class="h2"><?= e($article['title']) ?></h2>
            <p class="muted"><?= e($article['excerpt']) ?></p>
            <span class="feat__by"><b>Por <?= e($article['author']) ?></b><span><?= e($author['oab_number'] ?? '') ?> · <?= e(article_date($article)) ?></span></span>
            <span class="link arrow">Ler artigo</span>
        </span>
    </a>
<?php else: ?>
    <a class="art" href="<?= e($href) ?>" data-area="<?= e($article['area_slug']) ?>">
        <?= $image ?>
        <span class="art__area"><?= e(area_short($article['area_slug'])) ?></span>
        <h3 class="h3"><?= e($article['title']) ?></h3>
        <p><?= e($article['excerpt']) ?></p>
        <span class="art__meta"><span><?= e(article_date($article)) ?></span><span><?= e($article['author']) ?></span><span><?= e((string) $article['reading_time_minutes']) ?> min de leitura</span></span>
    </a>
<?php endif; ?>
