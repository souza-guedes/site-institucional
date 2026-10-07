<?php include dirname(__DIR__) . '/partials/header.php'; ?>
<?php include dirname(__DIR__) . '/partials/navbar.php'; ?>

<main class="min-h-screen py-24 px-4 sm:px-6 lg:px-8 max-w-4xl mx-auto text-center">
    <div class="bg-white p-12 rounded-lg border border-gray-200 shadow-sm">
        <span class="text-6xl font-bold font-serif text-gray-400 block mb-4">404</span>
        <h1 class="text-3xl font-serif font-bold text-gray-900 mb-4">Página Não Encontrada</h1>
        <p class="text-gray-600 mb-8 max-w-lg mx-auto">
            O endereço solicitado não existe ou foi remanejado. Utilize a navegação principal ou retorne à página inicial.
        </p>
        <div class="flex justify-center gap-4">
            <a href="/" class="px-6 py-3 bg-gray-900 text-white text-sm font-medium rounded-md hover:bg-gray-800 transition">
                Retornar ao Início
            </a>
            <a href="/areas-de-atuacao" class="px-6 py-3 border border-gray-300 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-100 transition">
                Áreas de Atuação
            </a>
        </div>
    </div>
</main>

<?php include dirname(__DIR__) . '/partials/cookie-banner.php'; ?>
<?php include dirname(__DIR__) . '/partials/footer.php'; ?>
