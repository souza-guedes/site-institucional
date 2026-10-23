<?php
/**
 * Detalhe de área (WF-04): detalhamento técnico informativo, sem promessa de resultado.
 *
 * @var array $area  item de practice_areas (firm-profile.json)
 */
$taxonomyArea = taxonomy_by_slug($area['slug']);
$tracks = $taxonomyArea['procedural_tracks'] ?? ['judicial' => [], 'extrajudicial' => []];
$others = array_filter($firm['practice_areas'], static fn (array $a): bool => $a['slug'] !== $area['slug']);
ob_start();
?>
<div class="tags">
    <?php foreach ($area['audience_types'] as $type): ?>
        <span class="tag"><?= $type === 'pf' ? 'Pessoas físicas' : 'Pessoas jurídicas' ?></span>
    <?php endforeach; ?>
</div>
<?php $tags = (string) ob_get_clean(); ?>
<main id="conteudo">
    <?php partial('page-header', [
        'crumbs' => [['Início', '/'], ['Áreas de Atuação', '/areas-de-atuacao'], [$area['title'], null]],
        'eyebrow' => 'Área de Atuação',
        'title' => $area['title'],
        'lead' => $area['focus'],
        'extra' => $tags,
    ]); ?>

    <section class="section section--tight">
        <div class="wrap detail">
            <div class="detail__main">
                <div class="block">
                    <p class="eyebrow">Sobre a área</p>
                    <h2 class="h2">Como o escritório atua</h2>
                    <div class="prose">
                        <?php foreach ($area['description'] as $paragraph): ?><p><?= e($paragraph) ?></p><?php endforeach; ?>
                    </div>
                </div>
                <div class="block">
                    <p class="eyebrow">Escopo</p>
                    <h2 class="h2">Escopo de atuação</h2>
                    <ul class="list">
                        <?php foreach ($area['scope_items'] as $item): ?><li><?= e($item) ?></li><?php endforeach; ?>
                    </ul>
                </div>
                <div class="block">
                    <p class="eyebrow">Procedimentos</p>
                    <h2 class="h2">Caminhos judiciais e extrajudiciais</h2>
                    <p class="muted max-w-[66ch]">O caminho adequado depende da análise de cada caso. Os procedimentos abaixo são os mais comuns nesta área.</p>
                    <div class="tracks">
                        <div class="track"><h3>Judicial</h3><ul><?php foreach ($tracks['judicial'] as $item): ?><li><?= e($item) ?></li><?php endforeach; ?></ul></div>
                        <div class="track"><h3>Extrajudicial</h3><ul><?php foreach ($tracks['extrajudicial'] as $item): ?><li><?= e($item) ?></li><?php endforeach; ?></ul></div>
                    </div>
                </div>
                <div class="block">
                    <p class="eyebrow">Público</p>
                    <h2 class="h2">Público atendido</h2>
                    <ul class="list">
                        <?php foreach ($area['target_audience'] as $item): ?><li><?= e($item) ?></li><?php endforeach; ?>
                    </ul>
                </div>
            </div>
            <aside class="aside dark of" aria-label="Fale com o escritório">
                <p class="eyebrow">Atendimento</p>
                <h2 class="h3">Fale com o escritório</h2>
                <p class="muted text-[16px] leading-6">Apresente sua demanda pelos canais oficiais. O primeiro contato não constitui consulta jurídica.</p>
                <?php partial('channels', ['group' => 'fale']); ?>
                <a class="btn btn--light" href="/contato">Ir para contato</a>
            </aside>
        </div>
    </section>

    <section class="section section--tight pt-0">
        <div class="wrap">
            <p class="eyebrow mb-2">Outras áreas de atuação</p>
            <div class="others">
                <?php foreach ($others as $other): ?>
                    <a href="/areas-de-atuacao/<?= e($other['slug']) ?>"><span><?= e($other['title']) ?></span><span aria-hidden="true">→</span></a>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
</main>
