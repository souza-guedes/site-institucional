<?php
/** Corpo Jurídico (WF-05): qualificação verdadeira e inscrição OAB/SP obrigatórias (Art. 1º, § 1º). */
?>
<main id="conteudo">
    <?php partial('page-header', [
        'crumbs' => [['Início', '/'], ['Corpo Jurídico', null]],
        'eyebrow' => 'Corpo Jurídico',
        'title' => 'Corpo jurídico e sócios fundadores',
    ]); ?>

    <?php foreach ($firm['partners'] as $i => $partner): ?>
        <section class="section <?= $i % 2 === 0 ? 'band--branco' : 'dark' ?>" aria-label="<?= e($partner['name']) ?>">
            <div class="wrap"><?php partial('lawyer-card', ['partner' => $partner, 'variant' => 'perfil']); ?></div>
        </section>
    <?php endforeach; ?>

    <section class="section section--tight">
        <div class="wrap">
            <?php partial('notice', [
                'title' => 'Sobre as qualificações informadas',
                'text' => 'As titulações acima correspondem à formação acadêmica efetivamente concluída por cada advogado (Art. 1º, § 1º do Provimento CFOAB nº 205/2021). A indicação de área de atuação não constitui título de especialista.',
            ]); ?>
        </div>
    </section>
</main>
