<?php
/**
 * Card de Advogado (catálogo 3.3): nome completo e OAB/SP sempre juntos (Art. 1º, § 1º do Prov. 205/2021).
 *
 * @var array $partner  item de partners (firm-profile.json)
 * @var string $variant 'compacto' (Home, seção escura) ou 'perfil' (página Corpo Jurídico)
 */
$variant ??= 'compacto';
$portrait = '<div class="portrait"><div class="seal of"><img src="' . e($partner['photo']) . '" alt="' . e($partner['name']) . '" width="800" height="1000"></div></div>';
?>
<?php if ($variant === 'perfil'): ?>
    <article class="profile">
        <?= $portrait ?>
        <div class="profile__body">
            <div class="grid gap-1">
                <p class="eyebrow"><?= e(partner_title($partner)) ?></p>
                <h2 class="h2"><?= e($partner['name']) ?></h2>
                <p class="person__oab person__oab--light"><?= e($partner['oab_number']) ?></p>
            </div>
            <div class="prose">
                <?php foreach ($partner['bio_long'] as $paragraph): ?>
                    <p><?= e($paragraph) ?></p>
                <?php endforeach; ?>
            </div>
            <dl class="facts">
                <?php foreach ($partner['education'] as $item): ?>
                    <div><dt><?= e($item['level']) ?></dt><dd><?= e($item['course']) ?> — <?= e($item['institution']) ?></dd></div>
                <?php endforeach; ?>
                <div><dt>Atuação principal</dt><dd><?= e($partner['primary_area']) ?></dd></div>
                <div><dt>Inscrição</dt><dd><?= e($partner['oab_number']) ?></dd></div>
            </dl>
        </div>
    </article>
<?php else: ?>
    <div class="person">
        <?= $portrait ?>
        <div class="person__txt">
            <h3 class="h3"><?= e($partner['name']) ?></h3>
            <p class="person__oab"><?= e($partner['oab_number']) ?></p>
            <p class="bio muted"><?= e($partner['bio']) ?></p>
            <a class="link arrow" href="/advogados">Ver perfil completo</a>
        </div>
    </div>
<?php endif; ?>
