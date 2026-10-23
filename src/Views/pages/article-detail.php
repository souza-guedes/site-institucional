<?php
/**
 * Artigo (WF-06b, rota /artigos/{slug}): autoria com OAB/SP, natureza educativa (Art. 4º)
 * e atendimento passivo.
 *
 * @var array $article  item de articles (articles.json)
 */
$area = area_by_slug($article['area_slug']);
$author = partner_by_name($article['author']);
$sameArea = array_filter($articles, static fn (array $a): bool => $a['slug'] !== $article['slug'] && $a['area_slug'] === $article['area_slug']);
$otherAreas = array_filter($articles, static fn (array $a): bool => $a['slug'] !== $article['slug'] && $a['area_slug'] !== $article['area_slug']);
$related = array_slice(array_merge(array_values($sameArea), array_values($otherAreas)), 0, 3);
$byline = '<div class="byline"><img src="' . e($author['photo']) . '" alt="" width="52" height="52"><div><b>' . e($author['name'])
    . '</b><span>' . e(partner_title($author)) . ' · ' . e($author['oab_number']) . '</span></div></div>';
?>
<main id="conteudo">
    <header class="arthead">
        <div class="wrap">
            <div class="arthead__in">
                <nav class="crumbs" aria-label="Você está em">
                    <ol><li><a href="/">Início</a></li><li><a href="/artigos">Artigos e Informações</a></li><li aria-current="page"><?= e(area_short($article['area_slug'])) ?></li></ol>
                </nav>
                <p class="arttag"><span><?= e($area['title']) ?></span><span><?= e((string) $article['reading_time_minutes']) ?> min de leitura</span><span><?= e(article_date($article)) ?></span></p>
                <h1 class="art-h1"><?= e($article['title']) ?></h1>
                <?= $byline ?>
            </div>
            <div class="feat__media arthero"><span class="feat__bar" aria-hidden="true"></span><span class="art__img"><img src="<?= e($article['image']) ?>" alt="" width="900" height="600"></span></div>
        </div>
    </header>

    <section class="section band--branco">
        <div class="wrap artbody">
            <article class="artbody__main">
                <p class="lead"><?= e($article['excerpt']) ?></p>
                <?php foreach ($article['sections'] as $section): ?>
                    <h2><?= e($section['heading']) ?></h2>
                    <?php foreach ($section['paragraphs'] as $paragraph): ?><p><?= e($paragraph) ?></p><?php endforeach; ?>
                    <?php if ($section['list'] !== []): ?>
                        <ul class="list"><?php foreach ($section['list'] as $item): ?><li><?= e($item) ?></li><?php endforeach; ?></ul>
                    <?php endif; ?>
                    <?php foreach ($section['closing_paragraphs'] as $paragraph): ?><p><?= e($paragraph) ?></p><?php endforeach; ?>
                <?php endforeach; ?>
                <div class="artbody__end">
                    <p class="nota"><b>Referências:</b> <?= e(implode(' ', $article['references'])) ?></p>
                    <p class="nota"><?= e($educationalNotice) ?></p>
                </div>
            </article>
            <aside class="artbody__aside" aria-label="Sobre este artigo">
                <div class="author-card of">
                    <p class="eyebrow">Quem escreveu</p>
                    <?= $byline ?>
                    <p><?= e($author['bio']) ?></p>
                    <a class="link arrow" href="/advogados">Ver perfil completo</a>
                </div>
                <?php partial('notice', [
                    'title' => 'Área de atuação relacionada',
                    'text' => $area['title'] . ': ' . $area['summary'],
                    'extra' => '<p><a class="link arrow" href="/areas-de-atuacao/' . e($area['slug']) . '">Conhecer a área</a></p>',
                ]); ?>
            </aside>
        </div>
    </section>

    <?php if ($related !== []): ?>
        <section class="section" aria-labelledby="leia-t">
            <div class="wrap">
                <div class="section__head"><p class="eyebrow">Leia também</p><h2 class="h2" id="leia-t">Outros artigos</h2></div>
                <div class="articles"><?php foreach ($related as $item) { partial('article-card', ['article' => $item]); } ?></div>
                <p class="more"><a class="link arrow" href="/artigos">Ver todos os artigos</a></p>
            </div>
        </section>
    <?php endif; ?>
</main>
