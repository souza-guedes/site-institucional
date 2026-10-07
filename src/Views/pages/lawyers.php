<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Breadcrumb -->
    <nav class="text-sm text-gray-500 mb-6" aria-label="Breadcrumb">
        <ol class="list-none p-0 inline-flex space-x-2">
            <li><a href="/" class="hover:underline text-primary-700">Início</a></li>
            <li><span>&rarr;</span></li>
            <li class="text-gray-700 font-semibold">Corpo Jurídico</li>
        </ol>
    </nav>

    <header class="mb-12 text-center max-w-3xl mx-auto">
        <h1 class="text-3xl sm:text-4xl font-serif font-bold text-gray-900 mb-4">
            Corpo Jurídico & Sócios Fundadores
        </h1>
        <p class="text-lg text-gray-600 leading-relaxed">
            Profissionais dedicados à prestação de serviços jurídicos com rigor técnico, atendimento artesanal e ética profissional inegociável.
        </p>
    </header>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
        <?php foreach ($partners as $partner): ?>
            <article class="bg-white rounded-lg border border-gray-200 shadow-sm p-8 flex flex-col justify-between">
                <div>
                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center text-primary-800 font-serif font-bold text-2xl mb-6 border border-gray-200">
                        <?= htmlspecialchars(substr($partner['name'] ?? 'SG', 0, 1)) ?>
                    </div>
                    <h2 class="text-2xl font-serif font-bold text-gray-900 mb-1">
                        <?= htmlspecialchars($partner['name'] ?? '') ?>
                    </h2>
                    <p class="text-sm font-semibold text-primary-700 mb-4">
                        <?= htmlspecialchars($partner['oab_number'] ?? '') ?> &bull; <?= htmlspecialchars($partner['role'] ?? 'Advogado(a)') ?>
                    </p>
                    <div class="border-t border-gray-100 pt-4 mb-4">
                        <p class="text-xs uppercase font-semibold text-gray-400 tracking-wider mb-2">Área Principal de Atuação</p>
                        <p class="text-sm font-medium text-gray-800"><?= htmlspecialchars($partner['primary_area'] ?? '') ?></p>
                    </div>
                    <div class="text-sm text-gray-600 leading-relaxed mb-6">
                        <p><?= htmlspecialchars($partner['bio'] ?? '') ?></p>
                    </div>
                </div>

                <div class="pt-4 border-t border-gray-100 text-xs text-gray-500">
                    Atuação em conformidade com o Código de Ética e Disciplina da OAB.
                </div>
            </article>
        <?php endforeach; ?>
    </div>

    <!-- Disclaimer Regulamentar OAB -->
    <section class="bg-gray-50 border border-gray-200 rounded-lg p-6 text-center max-w-3xl mx-auto">
        <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Conformidade Regulamentar (Provimento CFOAB nº 205/2021)</h3>
        <p class="text-xs text-gray-500 leading-relaxed">
            As informações curriculares e qualificações acadêmicas divulgadas nesta página refletem fielmente a titulação de seus integrantes (Art. 1º, § 1º e Art. 4º, § 1º), tendo propósito exclusivamente informativo e de identificação profissional.
        </p>
    </section>
</main>
