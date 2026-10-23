<?php
/**
 * Contato (WF-07): canais passivos, como funciona o primeiro contato e sede.
 * O site não tem formulário: o contato é só pelos canais oficiais (issue #15).
 */
$phone = channel('phone');
$inPerson = channel('in_person');
?>
<main id="conteudo">
    <?php partial('page-header', [
        'crumbs' => [['Início', '/'], ['Contato', null]],
        'eyebrow' => 'Contato',
        'title' => 'Contato e sede do escritório',
        'lead' => 'Atendimento ' . mb_strtolower($phone['availability'] ?? '') . ', presencialmente com agendamento ou pelos canais abaixo.',
    ]); ?>

    <section class="section section--tight band--branco" aria-labelledby="canais-t">
        <div class="wrap">
            <div class="section__head">
                <p class="eyebrow">Canais oficiais</p>
                <h2 class="h2" id="canais-t">Fale com o escritório</h2>
            </div>
            <?php partial('channels', ['group' => 'cards']); ?>
        </div>
    </section>

    <section class="section">
        <div class="wrap">
            <div class="contact-aside max-w-3xl">
                <div class="section__head m-0">
                    <p class="eyebrow">Primeiro contato</p>
                    <h2 class="h2">Como funciona</h2>
                </div>
                <ol class="steps">
                    <li><b>Envie uma mensagem</b><span>Descreva a situação de forma resumida, pelos canais oficiais.</span></li>
                    <li><b>Leitura pelos sócios</b><span>A mensagem é lida diretamente por um dos sócios, que retorna pelo mesmo canal.</span></li>
                    <li><b>Reunião com agendamento</b><span>Quando for o caso, a conversa continua em reunião na sede.</span></li>
                </ol>
                <?php partial('notice', ['title' => 'Antes de enviar', 'text' => 'Não envie documentos nem dados sensíveis no primeiro contato. Ele não constitui consulta jurídica nem estabelece relação advogado-cliente.']); ?>
            </div>
        </div>
    </section>

    <section class="section dark sede" aria-labelledby="sede-t">
        <div class="wrap sede__in">
            <div class="sede__txt">
                <p class="eyebrow">Visite a sede</p>
                <h2 class="h2" id="sede-t">Atendimento presencial na <?= e($firm['firm']['address']['neighborhood']) ?></h2>
                <p class="muted">Ambiente reservado, que preserva o sigilo das conversas. Visitas somente com agendamento prévio.</p>
                <?php partial('channels', ['group' => 'sede']); ?>
                <a class="btn btn--light" href="<?= e($inPerson['action_uri'] ?? '#') ?>" target="_blank" rel="noopener">Abrir no Google Maps</a>
            </div>
            <div class="map" role="img" aria-label="Mapa ilustrativo da sede, <?= e($inPerson['value'] ?? '') ?>">
                <div class="map__pin"><span><?= e($firm['firm']['corporate_name']) ?></span><i></i></div>
            </div>
        </div>
    </section>
</main>
