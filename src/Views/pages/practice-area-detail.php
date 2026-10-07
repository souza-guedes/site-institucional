<?php include dirname(__DIR__) . '/partials/header.php'; ?>
<?php include dirname(__DIR__) . '/partials/navbar.php'; ?>

<main class="min-h-screen py-16 px-4 sm:px-6 lg:px-8 max-w-5xl mx-auto">
    <div class="mb-6">
        <a href="/areas-de-atuacao" class="text-sm font-semibold text-primary-700 hover:underline">&larr; Voltar para todas as áreas</a>
    </div>

    <article class="bg-white p-8 md:p-12 rounded-lg border border-gray-200 shadow-sm">
        <h1 class="text-3xl sm:text-4xl font-serif font-bold text-gray-900 mb-4"><?= htmlspecialchars($area['title'] ?? 'Área de Atuação') ?></h1>
        <p class="text-lg text-gray-600 mb-8 leading-relaxed"><?= htmlspecialchars($area['focus'] ?? $area['summary'] ?? '') ?></p>

        <section class="mb-8">
            <h2 class="text-xl font-serif font-bold text-gray-900 mb-4 border-b border-gray-100 pb-2">Escopo de Atuação</h2>
            <ul class="space-y-3">
                <?php foreach (($area['scope_items'] ?? []) as $item): ?>
                    <li class="flex items-start text-sm text-gray-700">
                        <span class="mr-3 text-primary-700 font-bold">✓</span>
                        <span><?= htmlspecialchars($item) ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
        </section>

        <?php if (!empty($area['target_audience'])): ?>
            <section class="mb-8">
                <h2 class="text-xl font-serif font-bold text-gray-900 mb-4 border-b border-gray-100 pb-2">Público Atendido</h2>
                <ul class="space-y-2">
                    <?php foreach ($area['target_audience'] as $audience): ?>
                        <li class="flex items-start text-sm text-gray-700">
                            <span class="mr-3 text-gray-400">•</span>
                            <span><?= htmlspecialchars($audience) ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </section>
        <?php endif; ?>

        <div class="mt-12 pt-8 border-t border-gray-200 flex flex-col sm:flex-row justify-between items-center gap-4">
            <p class="text-sm text-gray-500">
                Atendimento mediante análise individualizada da demanda.
            </p>
            <a href="/contato" class="px-6 py-3 bg-gray-900 text-white text-sm font-semibold rounded-md hover:bg-gray-800 transition">
                Fale com a Banca
            </a>
        </div>
    </article>
</main>

<?php include dirname(__DIR__) . '/partials/cookie-banner.php'; ?>
<?php include dirname(__DIR__) . '/partials/footer.php'; ?>
