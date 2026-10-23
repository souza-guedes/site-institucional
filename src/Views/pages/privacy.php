<?php
/**
 * Privacidade e Termos de Uso (WF-08): Política de Privacidade, Política de Cookies e Termos de Uso,
 * lidos de data/privacy-policy.json (WDLC 4.2).
 */
$documents = [
    'privacidade' => $privacy['privacy_policy'],
    'cookies' => $privacy['cookie_policy'],
    'termos' => $privacy['terms_of_use'],
];
$dpo = $privacy['data_protection_officer'];
?>
<main id="conteudo">
    <?php partial('page-header', [
        'crumbs' => [['Início', '/'], [$privacy['page_title'], null]],
        'eyebrow' => 'Privacidade e LGPD',
        'title' => $privacy['page_title'],
        'extra' => '<p class="nota">Versão de ' . e(format_date($privacy['last_updated'])) . ' · Encarregada de dados: ' . e($dpo['name']) . ', ' . e($dpo['oab_number']) . '</p>',
    ]); ?>

    <section class="section section--tight">
        <div class="wrap policy">
            <nav class="toc" aria-label="Nesta página">
                <p>Nesta página</p>
                <?php foreach ($documents as $id => $document): ?>
                    <a href="#<?= e($id) ?>"><?= e($document['title']) ?></a>
                <?php endforeach; ?>
            </nav>
            <div class="policy__body">
                <?php foreach ($documents as $id => $document): ?>
                    <div class="policy__doc" id="<?= e($id) ?>">
                        <h2 class="h2"><?= e($document['title']) ?></h2>
                        <?php foreach ($document['sections'] as $section): ?>
                            <section>
                                <h3 class="h3"><?= e($section['heading']) ?></h3>
                                <?php foreach ($section['paragraphs'] as $paragraph): ?><p><?= e($paragraph) ?></p><?php endforeach; ?>
                                <?php if (isset($section['table'])): ?>
                                    <div class="ptable" role="region" aria-label="<?= e($section['heading']) ?>" tabindex="0">
                                        <table>
                                            <thead><tr><?php foreach ($section['table']['columns'] as $column): ?><th scope="col"><?= e($column) ?></th><?php endforeach; ?></tr></thead>
                                            <tbody>
                                                <?php foreach ($section['table']['rows'] as $row): ?>
                                                    <tr><?php foreach ($row as $cell): ?><td><?= e($cell) ?></td><?php endforeach; ?></tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php endif; ?>
                                <?php if ($section['list'] !== []): ?>
                                    <ul><?php foreach ($section['list'] as $item): ?><li><?= e($item) ?></li><?php endforeach; ?></ul>
                                <?php endif; ?>
                                <?php foreach ($section['closing_paragraphs'] as $paragraph): ?><p><?= e($paragraph) ?></p><?php endforeach; ?>
                            </section>
                        <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
</main>
