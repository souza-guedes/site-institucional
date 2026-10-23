<?php
/**
 * Rodapé institucional: identificação OAB dos sócios e disclaimer do Provimento CFOAB nº 205/2021
 * (Art. 1º, § 1º e Anexo Único), lidos de data/firm-profile.json.
 */
$address = $firm['firm']['address'];
$phone = channel('phone');
$email = channel('email');
?>
<footer class="ftr">
    <div class="wrap">
        <div class="ftr__grid">
            <div class="grid content-start gap-3">
                <a class="brand" href="/" aria-label="Souza Guedes Advogados — início">
                    <svg class="logo-mono" aria-hidden="true"><use href="/assets/img/logos.svg#logo-monograma"/></svg>
                    <svg class="logo-wm" aria-hidden="true"><use href="/assets/img/logos.svg#logo-wordmark"/></svg>
                </a>
                <ul class="ftr__oab">
                    <?php foreach ($firm['partners'] as $partner): ?>
                        <li><?= e($partner['name']) ?><span><?= e($partner['oab_number']) ?></span></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div>
                <h2>Sede</h2>
                <p class="muted"><?= e("{$address['street']}, {$address['number']}, {$address['complement']}") ?><br><?= e("{$address['neighborhood']}, {$address['city']}/{$address['state']}") ?><br>CEP <?= e($address['postal_code']) ?></p>
                <p class="muted mt-1"><?= e($phone['availability'] ?? '') ?></p>
            </div>
            <div>
                <h2>Contato</h2>
                <ul>
                    <li><?= e($phone['value'] ?? '') ?> <span class="muted">· telefone e WhatsApp</span></li>
                    <li><?= e($email['value'] ?? '') ?></li>
                </ul>
            </div>
            <div>
                <h2>Navegação</h2>
                <ul>
                    <li><a href="/sobre">O Escritório</a></li>
                    <li><a href="/areas-de-atuacao">Áreas de Atuação</a></li>
                    <li><a href="/advogados">Corpo Jurídico</a></li>
                    <li><a href="/artigos">Artigos</a></li>
                    <li><a href="/privacidade">Privacidade e Termos de Uso</a></li>
                </ul>
            </div>
        </div>
        <div class="ftr__legal">
            <p><?= e($firm['compliance_disclaimer']) ?></p>
            <p>© <?= e((string) $firm['firm']['foundation_year']) ?> <?= e($firm['firm']['corporate_name']) ?> · Sociedade de advogados registrada na OAB/SP</p>
        </div>
    </div>
</footer>
