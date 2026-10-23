<?php
/** Áreas de Atuação (WF-03). Nomenclatura obrigatória "Áreas de Atuação" (Art. 3º, III do Prov. 205/2021). */
?>
<main id="conteudo">
    <?php partial('page-header', [
        'crumbs' => [['Início', '/'], ['Áreas de Atuação', null]],
        'eyebrow' => 'Áreas de Atuação',
        'title' => 'Áreas de prática jurídica',
        'lead' => 'Quatro áreas de atuação para pessoas físicas e jurídicas. Cada demanda é avaliada individualmente após o primeiro contato.',
    ]); ?>

    <section class="section section--tight">
        <div class="wrap">
            <div class="filters" role="group" aria-label="Filtrar por público atendido" data-filter-group="publico">
                <span>Público:</span>
                <button class="chip" type="button" data-filter="todos" aria-pressed="true">Todos</button>
                <button class="chip" type="button" data-filter="pf" aria-pressed="false">Pessoas físicas</button>
                <button class="chip" type="button" data-filter="pj" aria-pressed="false">Pessoas jurídicas</button>
            </div>
            <div class="areas" id="areas-list">
                <?php foreach ($firm['practice_areas'] as $area) { partial('area-card', ['area' => $area, 'variant' => 'largo']); } ?>
            </div>
        </div>
    </section>
</main>
