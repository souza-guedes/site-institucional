<?php
/**
 * Formulário de primeiro contato (catálogo 3.3). Só interface nesta etapa: o envio pelo servidor,
 * com CSRF, honeypot e PHPMailer, é da WDLC 5.2 (#15). site.js valida os campos e informa os outros canais.
 *
 * O checkbox é declaração de ciência da Política de Privacidade, e não consentimento: a base legal do
 * contato é o art. 7º, V, da LGPD (data/privacy-policy.json, seção 3).
 */
?>
<form class="form" id="contact-form" novalidate data-envio-indisponivel>
    <div class="grid gap-1">
        <h2 class="h3">Formulário de primeiro contato</h2>
        <p class="nota">Todos os campos são obrigatórios, exceto telefone.</p>
    </div>
    <div class="field">
        <label for="f-nome">Nome completo</label>
        <input id="f-nome" name="nome" autocomplete="name" required data-msg="Informe seu nome completo.">
    </div>
    <div class="form__row">
        <div class="field">
            <label for="f-email">E-mail</label>
            <input id="f-email" name="email" type="email" autocomplete="email" required data-msg="Informe um e-mail válido, como nome@exemplo.com.">
        </div>
        <div class="field">
            <label for="f-tel">Telefone <span class="opt">(opcional)</span></label>
            <input id="f-tel" name="telefone" type="tel" autocomplete="tel" placeholder="(11) 90000-0000">
        </div>
    </div>
    <div class="field">
        <label for="f-area">Área de interesse</label>
        <select id="f-area" name="area" required data-msg="Selecione a área de interesse ou “Outro assunto”.">
            <option value="">Selecione uma área</option>
            <?php foreach ($firm['practice_areas'] as $area): ?>
                <option value="<?= e($area['slug']) ?>"><?= e($area['title']) ?></option>
            <?php endforeach; ?>
            <option value="outro">Outro assunto</option>
        </select>
    </div>
    <div class="field">
        <label for="f-msg">Mensagem</label>
        <textarea id="f-msg" name="mensagem" required aria-describedby="f-msg-hint" data-msg="Descreva brevemente a sua demanda."></textarea>
        <p class="hint" id="f-msg-hint">Descreva a situação de forma resumida. Não envie documentos nem dados sensíveis por aqui.</p>
    </div>
    <label class="check" id="f-ciencia-wrap">
        <input type="checkbox" id="f-ciencia" name="ciencia" required>
        <span>Li a <a class="link" href="/privacidade">Política de Privacidade</a> e entendo que meus dados serão usados apenas para responder a este contato.</span>
    </label>
    <div class="form__foot">
        <p class="nota">O envio desta mensagem não estabelece relação advogado-cliente nem constitui consulta jurídica, que depende de contratação formal e análise individualizada.</p>
        <div><button class="btn btn--primary" type="submit">Enviar mensagem</button></div>
        <p class="form__status" id="form-status" role="status" hidden></p>
    </div>
</form>
