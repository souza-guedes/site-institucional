<?php
/**
 * Aviso com moldura aberta (catálogo 3.3): avisos legais e informativos.
 *
 * @var string $title
 * @var string $text
 * @var ?string $extra HTML adicional (ex.: link)
 */
$extra ??= null;
?>
<div class="notice of">
    <b><?= e($title) ?></b>
    <p><?= e($text) ?></p>
    <?= $extra ?? '' ?>
</div>
