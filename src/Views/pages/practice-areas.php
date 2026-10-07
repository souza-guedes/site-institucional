<?php include dirname(__DIR__) . '/partials/header.php'; ?>
<?php include dirname(__DIR__) . '/partials/navbar.php'; ?>

<main class="min-h-screen py-16 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
    <div class="mb-12 text-center">
        <h1 class="text-4xl font-serif font-bold text-gray-900 mb-4">Áreas de Atuação</h1>
        <p class="text-lg text-gray-600 max-w-3xl mx-auto">
            Atuação jurídica técnica e personalizada nas principais áreas do Direito, orientada para a prevenção e resolução de litígios.
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <?php foreach (($practiceAreas ?? []) as $area): ?>
            <div class="bg-white p-8 rounded-lg border border-gray-200 shadow-sm flex flex-col justify-between">
                <div>
                    <h2 class="text-2xl font-serif font-bold text-gray-900 mb-3"><?= htmlspecialchars($area['title']) ?></h2>
                    <p class="text-gray-600 text-sm mb-4"><?= htmlspecialchars($area['focus'] ?? $area['summary'] ?? '') ?></p>
                    
                    <h3 class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2">Principais demandas:</h3>
                    <ul class="text-xs text-gray-600 space-y-1 mb-6">
                        <?php foreach (array_slice(($area['scope_items'] ?? []), 0, 3) as $scope): ?>
                            <li class="flex items-start">
                                <span class="mr-2 text-primary-700">•</span>
                                <span><?= htmlspecialchars($scope) ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <a href="/areas-de-atuacao/<?= htmlspecialchars($area['slug']) ?>" class="inline-flex items-center text-sm font-semibold text-primary-700 hover:text-primary-800">
                    Ver escopo completo &rarr;
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</main>

<?php include dirname(__DIR__) . '/partials/cookie-banner.php'; ?>
<?php include dirname(__DIR__) . '/partials/footer.php'; ?>
