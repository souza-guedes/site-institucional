<?php
/** O Escritório (WF-02): trajetória, missão e visão em faixa branca; princípios éticos em faixa papel. */
$values = array_map(static fn (string $v): array => array_map('trim', explode(':', $v, 2)), $firm['firm']['values']);
?>
<main id="conteudo">
    <?php partial('page-header', [
        'crumbs' => [['Início', '/'], ['O Escritório', null]],
        'eyebrow' => 'O Escritório',
        'title' => 'Sobre o escritório',
        'lead' => $firm['firm']['history'][0],
    ]); ?>

    <section class="section band--branco" aria-labelledby="trajetoria-t">
        <div class="wrap chapter chapter--photo">
            <div class="chapter__t">
                <p class="eyebrow">Trajetória</p>
                <h2 class="h2" id="trajetoria-t">Uma advocacia próxima e técnica</h2>
                <figure class="photo">
                    <img src="/assets/img/socios-palacio.jpg" alt="<?= e(implode(' e ', array_column($firm['partners'], 'name'))) ?>, de terno azul-marinho, em frente a um edifício histórico" width="1000" height="1250" loading="lazy">
                    <figcaption><?= e(implode(' e ', array_column($firm['partners'], 'name'))) ?>, sócios fundadores.</figcaption>
                </figure>
            </div>
            <div class="prose">
                <?php foreach (array_slice($firm['firm']['history'], 1) as $paragraph): ?>
                    <p><?= e($paragraph) ?></p>
                <?php endforeach; ?>
                <div class="mv">
                    <div class="of"><p class="eyebrow">Missão</p><p><?= e($firm['firm']['mission']) ?></p></div>
                    <div class="of"><p class="eyebrow">Visão</p><p><?= e($firm['firm']['vision']) ?></p></div>
                </div>
            </div>
        </div>
    </section>

    <section class="section band--papel" aria-labelledby="etica-t">
        <div class="wrap chapter">
            <div class="chapter__t"><p class="eyebrow">Ética</p><h2 class="h2" id="etica-t">Rigor ético, sigilo profissional e transparência</h2></div>
            <div class="prose">
                <p>A atuação segue o Estatuto da Advocacia, o Código de Ética e Disciplina da OAB e o Provimento CFOAB nº 205/2021: informação clara, sem promessa de resultado e sem captação de clientela.</p>
                <ul class="list">
                    <?php foreach ($values as [$name, $text]): ?>
                        <li><b><?= e(mb_convert_case($name, MB_CASE_TITLE)) ?>.</b> <?= e($text) ?></li>
                    <?php endforeach; ?>
                </ul>
                <p><a class="link arrow" href="/advogados">Conhecer o corpo jurídico</a></p>
            </div>
        </div>
    </section>
</main>
