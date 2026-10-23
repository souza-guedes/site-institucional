<?php
/**
 * Bloco final "Atendimento" (catálogo 3.3): atendimento passivo pelos canais oficiais.
 * Aparece antes do rodapé em todas as páginas, exceto Contato e 404 (showCta = false).
 */
?>
<section class="cta dark" aria-labelledby="cta-t">
    <span class="cta__bar" aria-hidden="true"></span>
    <div class="wrap cta__in">
        <div class="grid content-start gap-2">
            <p class="eyebrow">Atendimento</p>
            <h2 class="h2" id="cta-t">Fale com o escritório pelos canais oficiais.</h2>
            <p class="muted">O primeiro contato serve para apresentação e para que você exponha a sua demanda. Ele não constitui consulta jurídica.</p>
            <div class="mt-1"><a class="btn btn--light" href="/contato">Ir para contato</a></div>
        </div>
        <div class="cgroups">
            <section class="cgroup"><h3 class="cgroup__t">Fale com o escritório</h3><?php partial('channels', ['group' => 'fale']); ?></section>
            <section class="cgroup"><h3 class="cgroup__t">Visite a sede</h3><?php partial('channels', ['group' => 'sede']); ?></section>
        </div>
    </div>
</section>
