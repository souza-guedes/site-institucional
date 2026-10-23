<?php
/** Artigos e Informações (WF-06): conteúdo educativo (Art. 4º), com aviso antes da lista. */
$featured = $articles[0] ?? null;
$areasWithArticles = array_values(array_unique(array_column($articles, 'area_slug')));
$notice = '';
if ($featured !== null) {
    ob_start();
    partial('notice', ['title' => 'Conteúdo de natureza educativa', 'text' => 'Os textos desta seção têm finalidade exclusivamente informativa e acadêmica (Art. 4º do Provimento CFOAB nº 205/2021). Eles não substituem a análise individual de um caso por advogado.']);
    $notice = (string) ob_get_clean();
}
?>
<main id="conteudo">
    <?php partial('page-header', [
        'crumbs' => [['Início', '/'], ['Artigos e Informações', null]],
        'eyebrow' => 'Artigos e Informações',
        'title' => 'Artigos e informações jurídicas',
        'lead' => 'Textos escritos pelos sócios para explicar, em linguagem clara, direitos e procedimentos das nossas áreas de atuação.',
        'extra' => $notice,
    ]); ?>

    <?php if ($featured !== null): ?>
        <section class="section dark" aria-labelledby="feat-t">
            <div class="wrap">
                <div class="section__head"><p class="eyebrow" id="feat-t">Publicação mais recente</p></div>
                <?php partial('article-card', ['article' => $featured, 'variant' => 'destaque']); ?>
            </div>
        </section>

        <section class="section" aria-labelledby="todos-t">
            <div class="wrap">
                <div class="arts-bar">
                    <div class="arts-bar__t">
                        <h2 class="h2" id="todos-t">Todos os artigos</h2>
                        <p class="nota" id="arts-count" aria-live="polite"></p>
                    </div>
                    <div class="filters" role="group" aria-label="Filtrar artigos por área" data-filter-group="area">
                        <span>Área:</span>
                        <button class="chip" type="button" data-afilter="todas" aria-pressed="true">Todas</button>
                        <?php foreach ($areasWithArticles as $slug): ?>
                            <button class="chip" type="button" data-afilter="<?= e($slug) ?>" data-label="<?= e(area_short($slug)) ?>" aria-pressed="false"><?= e(area_short($slug)) ?></button>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="articles" id="articles-list">
                    <?php foreach ($articles as $article) { partial('article-card', ['article' => $article]); } ?>
                </div>
            </div>
        </section>
    <?php endif; ?>
</main>
