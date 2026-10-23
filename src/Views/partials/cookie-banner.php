<?php
/**
 * Aviso de cookies (data/privacy-policy.json, cookie_banner). Modo informativo: o site só usa cookies
 * estritamente necessários, então não há pedido de consentimento. O fechamento fica no localStorage (site.js).
 */
$banner = $privacy['cookie_banner'];
?>
<div class="cookie" id="cookie" role="region" aria-label="Aviso de cookies">
    <div class="wrap cookie__in">
        <p><?= e(str_replace($banner['link_label'] . '.', '', $banner['text'])) ?><a class="link" href="<?= e($banner['link_href']) ?>"><?= e($banner['link_label']) ?></a>.</p>
        <button class="btn btn--primary" type="button" data-cookie-dismiss><?= e($banner['button_label']) ?></button>
    </div>
</div>
