<?php
/**
 * Layout de todas as páginas: head, cabeçalho, conteúdo, bloco de atendimento, rodapé e aviso de cookies.
 * $content traz o HTML da página, que começa em <main id="conteudo">.
 */
partial('head');
?>
<a class="sr-only focus:not-sr-only focus:absolute focus:left-2 focus:top-2 focus:z-50 focus:bg-papel focus:px-3 focus:py-2" href="#conteudo">Pular para o conteúdo</a>
<?php partial('navbar'); ?>
<?= $content ?>
<?php if ($showCta) { partial('cta'); } ?>
<?php partial('footer'); ?>
<?php partial('cookie-banner'); ?>
<script src="/assets/js/site.js" defer></script>
</body>
</html>
