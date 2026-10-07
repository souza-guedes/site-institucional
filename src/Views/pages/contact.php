<?php include dirname(__DIR__) . '/partials/header.php'; ?>
<?php include dirname(__DIR__) . '/partials/navbar.php'; ?>

<main class="min-h-screen py-16 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
    <div class="mb-12 text-center">
        <h1 class="text-4xl font-serif font-bold text-gray-900 mb-4">Canais de Contato e Atendimento</h1>
        <p class="text-lg text-gray-600 max-w-3xl mx-auto">
            Entre em contato pelos nossos canais oficiais para agendamento de consultas ou esclarecimento de dúvidas institucionais.
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">
        <div class="bg-white p-8 rounded-lg border border-gray-200 shadow-sm">
            <h2 class="text-2xl font-serif font-bold text-gray-900 mb-6">Canais Formais</h2>
            
            <div class="space-y-6">
                <?php foreach (($contactChannels ?? []) as $channel): ?>
                    <div class="border-b border-gray-100 pb-4">
                        <span class="text-xs uppercase font-semibold text-gray-400"><?= htmlspecialchars($channel['label'] ?? '') ?></span>
                        <p class="text-lg font-medium text-gray-900 mt-1">
                            <?php if (!empty($channel['action_uri'])): ?>
                                <a href="<?= htmlspecialchars($channel['action_uri']) ?>" class="hover:text-primary-700 transition">
                                    <?= htmlspecialchars($channel['value']) ?>
                                </a>
                            <?php else: ?>
                                <?= htmlspecialchars($channel['value']) ?>
                            <?php endif; ?>
                        </p>
                        <?php if (!empty($channel['availability'])): ?>
                            <p class="text-xs text-gray-500 mt-1"><?= htmlspecialchars($channel['availability']) ?></p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="bg-white p-8 rounded-lg border border-gray-200 shadow-sm flex flex-col justify-between">
            <div>
                <h2 class="text-2xl font-serif font-bold text-gray-900 mb-4">Sede do Escritório</h2>
                <p class="text-sm text-gray-600 mb-6 leading-relaxed">
                    Localizado na Vila Ema, São Paulo/SP, o escritório dispõe de estrutura adequada para recebimento reservado e seguro de clientes.
                </p>
                <div class="bg-gray-50 p-4 rounded-md border border-gray-200 text-sm text-gray-700 space-y-1 mb-6">
                    <p><strong>Endereço:</strong> Rua Uhland, 784, Casa 1</p>
                    <p><strong>Bairro:</strong> Vila Ema — São Paulo/SP</p>
                    <p><strong>CEP:</strong> 03283-000</p>
                    <p><strong>Horário de Expediente:</strong> Das 10h às 18h</p>
                </div>
            </div>

            <a href="https://wa.me/5511936191904" target="_blank" rel="noopener noreferrer" class="block w-full text-center py-3 px-4 bg-primary-700 hover:bg-primary-800 text-white font-medium rounded-md transition">
                Iniciar Conversa no WhatsApp
            </a>
        </div>
    </div>
</main>

<?php include dirname(__DIR__) . '/partials/cookie-banner.php'; ?>
<?php include dirname(__DIR__) . '/partials/footer.php'; ?>
