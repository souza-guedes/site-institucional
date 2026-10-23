<?php
/**
 * Canais oficiais de contato (catálogo 3.3, "Atendimento e avisos"), de firm-profile.json.
 *
 * @var string $group 'fale' (telefone/WhatsApp e e-mail), 'sede' (endereço e horário) ou 'cards' (Contato)
 */
$phone = channel('phone');
$email = channel('email');
$address = $firm['firm']['address'];

$items = [
    'phone' => ['icon' => 'phone', 'label' => 'Telefone e WhatsApp', 'value' => $phone['value'] ?? '', 'copy' => true, 'extra' => null],
    'email' => ['icon' => 'mail', 'label' => 'E-mail', 'value' => $email['value'] ?? '', 'copy' => true, 'extra' => null],
    'address' => ['icon' => 'pin', 'label' => 'Endereço', 'value' => "{$address['street']}, {$address['number']}, {$address['complement']}",
        'copy' => false, 'extra' => "{$address['neighborhood']}, {$address['city']}/{$address['state']} · CEP {$address['postal_code']}"],
    'hours' => ['icon' => 'clock', 'label' => 'Horário de atendimento', 'value' => $phone['availability'] ?? '', 'copy' => false,
        'extra' => 'Presencial somente com agendamento'],
];

$renderItem = static function (array $item): string {
    $value = str_replace('@', '<wbr>@', e($item['value']));
    $html = '<div class="channel"><dt>' . icon($item['icon']) . e($item['label']) . '</dt><dd><strong>' . $value . '</strong>';
    if ($item['copy']) {
        $html .= '<button class="copy" type="button" data-copy="' . e($item['value']) . '">Copiar</button>';
    }
    if ($item['extra'] !== null) {
        $html .= '<span class="nota basis-full">' . e($item['extra']) . '</span>';
    }
    return $html . '</dd></div>';
};
?>
<?php if ($group === 'cards'): ?>
    <div class="ccards">
        <?php foreach (['phone', 'email', 'hours'] as $key): ?>
            <dl class="ccard of"><?= $renderItem($items[$key]) ?></dl>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <dl class="channels">
        <?php foreach ($group === 'sede' ? ['address', 'hours'] : ['phone', 'email'] as $key): ?>
            <?= $renderItem($items[$key]) ?>
        <?php endforeach; ?>
    </dl>
<?php endif; ?>
