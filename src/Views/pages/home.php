<?php
/** Início (WF-01). A composição da marca abre a página; cada seção seguinte retoma um elemento dela. */
$values = array_map(static fn (string $v): array => array_map('trim', explode(':', $v, 2)), $firm['firm']['values']);
$carousel = array_slice($articles, 0, 6);
$arrow = static fn (string $d): string => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><path d="' . $d . '"/></svg>';
?>
<main id="conteudo">
    <section class="hero">
        <div class="wrap">
            <div class="hero__txt">
                <h1 class="wordmark">Souza<br>Guedes<small>ADVOGADOS</small></h1>
                <p class="claim">Atuação jurídica estratégica, técnica e dedicada.</p>
                <p class="lead">Cuidamos dos interesses de nossos clientes, antecipamos riscos e buscamos soluções jurídicas que façam sentido para cada situação.</p>
                <div class="hero__ctas">
                    <a class="btn btn--primary" href="/areas-de-atuacao">Conhecer as áreas de atuação</a>
                    <a class="btn btn--ghost" href="/contato">Canais de atendimento</a>
                </div>
                <div class="hero__id">
                    <?php foreach ($firm['partners'] as $partner): ?>
                        <span><b><?= e($partner['name']) ?></b> · <?= e($partner['oab_number']) ?></span>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <div class="comp" aria-hidden="true">
            <span class="comp__nevoa of"></span><span class="comp__sq"></span><span class="comp__bar"></span><span class="comp__panel seal of"></span>
        </div>
    </section>

    <section class="section section--tight valband" aria-labelledby="valores-t">
        <div class="wrap valband__in">
            <div class="valband__head">
                <p class="eyebrow">Nossos valores</p>
                <h2 class="h3" id="valores-t">Os princípios que orientam cada atendimento</h2>
            </div>
            <div class="values">
                <?php foreach ($values as [$name, $text]): ?>
                    <div class="value"><h3><span class="sq"></span><?= e($name) ?></h3><p class="muted"><?= e($text) ?></p></div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="section" aria-labelledby="areas-t">
        <div class="wrap">
            <div class="section__head">
                <p class="eyebrow">Áreas de Atuação</p>
                <h2 class="h2" id="areas-t">Prática jurídica em quatro áreas do Direito Civil</h2>
            </div>
            <div class="areas areas--4">
                <?php foreach ($firm['practice_areas'] as $area) { partial('area-card', ['area' => $area]); } ?>
            </div>
            <p class="more"><a class="link arrow" href="/areas-de-atuacao">Todas as áreas de atuação</a></p>
        </div>
    </section>

    <section class="section dark team of" aria-labelledby="socios-t">
        <div class="wrap">
            <div class="section__head">
                <p class="eyebrow">Corpo Jurídico</p>
                <h2 class="h2" id="socios-t">Sócios fundadores</h2>
                <p class="muted">Cada caso é analisado e acompanhado diretamente pelos sócios.</p>
            </div>
            <div class="people">
                <?php foreach ($firm['partners'] as $partner) { partial('lawyer-card', ['partner' => $partner]); } ?>
            </div>
        </div>
    </section>

    <?php if ($carousel !== []): ?>
    <section class="section" aria-labelledby="artigos-t">
        <div class="wrap">
            <div class="arts-head">
                <div class="section__head m-0">
                    <p class="eyebrow">Artigos e Informações</p>
                    <h2 class="h2" id="artigos-t">Orientação jurídica de caráter educativo</h2>
                </div>
                <?php partial('notice', ['title' => 'Conteúdo informativo', 'text' => 'Os artigos explicam direitos e procedimentos de forma geral e não substituem a análise individual de cada caso (Art. 4º do Provimento CFOAB nº 205/2021).']); ?>
            </div>
            <div class="articles carousel" id="home-articles" role="region" aria-roledescription="carrossel" aria-label="Artigos recentes" tabindex="0">
                <?php foreach ($carousel as $article) { partial('article-card', ['article' => $article]); } ?>
            </div>
            <div class="car-ctl">
                <div class="car-dots" id="home-dots"></div>
                <div class="car-btns">
                    <button type="button" id="car-prev" aria-label="Artigo anterior"><?= $arrow('M15 5l-7 7 7 7') ?></button>
                    <button type="button" id="car-next" aria-label="Próximo artigo"><?= $arrow('M9 5l7 7-7 7') ?></button>
                </div>
            </div>
            <p class="more"><a class="link arrow" href="/artigos">Ver todos os artigos</a></p>
        </div>
    </section>
    <?php endif; ?>
</main>
