<?php include dirname(__DIR__) . '/partials/header.php'; ?>
<?php include dirname(__DIR__) . '/partials/navbar.php'; ?>

<main class="min-h-screen py-16 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
    <!-- Hero Section Informativo e Moderado -->
    <section class="text-center py-12 md:py-20 border-b border-gray-200">
        <h1 class="text-4xl sm:text-5xl font-serif font-bold text-gray-900 tracking-tight mb-6">
            Advocacia Estratégica com Rigor Técnico e Ética
        </h1>
        <p class="max-w-3xl mx-auto text-lg text-gray-600 mb-8 leading-relaxed">
            O escritório Souza Guedes Advogados presta assessoria jurídica contenciosa e consultiva com foco no atendimento individualizado, transparência e segurança jurídica.
        </p>
        <div class="flex flex-wrap justify-center gap-4">
            <a href="/areas-de-atuacao" class="px-6 py-3 bg-gray-900 text-white font-medium rounded-md hover:bg-gray-800 transition">
                Conheça Nossas Áreas de Atuação
            </a>
            <a href="/contato" class="px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-md hover:bg-gray-100 transition">
                Canais de Atendimento
            </a>
        </div>
    </section>

    <!-- Resumo das Áreas de Atuação -->
    <section class="py-16">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-serif font-bold text-gray-900 mb-4">Áreas de Atuação</h2>
            <p class="text-gray-600 max-w-2xl mx-auto">
                Atuação dedicada em demandas imobiliárias, saúde suplementar, relações de consumo e planejamento familiar.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php foreach (($firm['practice_areas'] ?? []) as $area): ?>
                <div class="bg-white p-6 rounded-lg border border-gray-200 shadow-sm flex flex-col justify-between">
                    <div>
                        <h3 class="text-xl font-bold font-serif text-gray-900 mb-3"><?= htmlspecialchars($area['title']) ?></h3>
                        <p class="text-sm text-gray-600 mb-4"><?= htmlspecialchars($area['summary'] ?? '') ?></p>
                    </div>
                    <a href="/areas-de-atuacao/<?= htmlspecialchars($area['slug']) ?>" class="text-sm font-semibold text-primary-700 hover:underline mt-2">
                        Saiba mais &rarr;
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
</main>

<?php include dirname(__DIR__) . '/partials/cookie-banner.php'; ?>
<?php include dirname(__DIR__) . '/partials/footer.php'; ?>
