<?php include dirname(__DIR__) . '/partials/header.php'; ?>
<?php include dirname(__DIR__) . '/partials/navbar.php'; ?>

<main class="min-h-screen py-16 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
    <div class="mb-12 text-center">
        <h1 class="text-4xl font-serif font-bold text-gray-900 mb-4">Sobre o Escritório</h1>
        <p class="text-lg text-gray-600 max-w-3xl mx-auto">
            Fundado sobre os pilares da ética, disciplina e atendimento personalizado, o escritório Souza Guedes Advogados alia solidez técnica à defesa responsável dos interesses de seus clientes.
        </p>
    </div>

    <!-- Sócios Fundadores -->
    <section class="py-8">
        <h2 class="text-2xl font-serif font-bold text-gray-900 mb-8 text-center">Sócios Fundadores</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-5xl mx-auto">
            <?php foreach (($firm['partners'] ?? []) as $partner): ?>
                <div class="bg-white p-8 rounded-lg border border-gray-200 shadow-sm">
                    <h3 class="text-2xl font-serif font-bold text-gray-900 mb-1"><?= htmlspecialchars($partner['name']) ?></h3>
                    <p class="text-sm font-semibold text-primary-700 mb-4"><?= htmlspecialchars($partner['oab_number'] ?? '') ?> • <?= htmlspecialchars($partner['role'] ?? 'Advogado(a)') ?></p>
                    <p class="text-sm text-gray-600 leading-relaxed mb-4"><?= htmlspecialchars($partner['bio'] ?? '') ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Missão, Visão e Princípios -->
    <section class="py-12 border-t border-gray-200 mt-12">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="p-6 bg-white border border-gray-200 rounded-lg">
                <h3 class="text-xl font-bold font-serif text-gray-900 mb-2">Missão</h3>
                <p class="text-sm text-gray-600"><?= htmlspecialchars($firm['firm']['mission'] ?? '') ?></p>
            </div>
            <div class="p-6 bg-white border border-gray-200 rounded-lg">
                <h3 class="text-xl font-bold font-serif text-gray-900 mb-2">Visão</h3>
                <p class="text-sm text-gray-600"><?= htmlspecialchars($firm['firm']['vision'] ?? '') ?></p>
            </div>
            <div class="p-6 bg-white border border-gray-200 rounded-lg">
                <h3 class="text-xl font-bold font-serif text-gray-900 mb-2">Princípios</h3>
                <ul class="text-sm text-gray-600 list-disc list-inside space-y-1">
                    <?php foreach (($firm['firm']['values'] ?? []) as $val): ?>
                        <li><?= htmlspecialchars($val) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </section>
</main>

<?php include dirname(__DIR__) . '/partials/cookie-banner.php'; ?>
<?php include dirname(__DIR__) . '/partials/footer.php'; ?>
