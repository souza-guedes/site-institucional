<?php
/** Página não encontrada (404), com atalhos para início, áreas e contato. */
?>
<main id="conteudo">
    <section class="section">
        <div class="wrap nf">
            <div class="nf__txt">
                <p class="eyebrow">Erro 404</p>
                <h1 class="display">Página não encontrada</h1>
                <p class="lead muted">O endereço <b><?= e($path) ?></b> não existe ou foi alterado. Use os atalhos abaixo para continuar.</p>
                <div class="nf__links">
                    <a class="btn btn--primary" href="/">Ir para o início</a>
                    <a class="btn btn--ghost" href="/areas-de-atuacao">Áreas de Atuação</a>
                    <a class="btn btn--ghost" href="/contato">Contato</a>
                </div>
            </div>
            <div class="nf__seal seal of" aria-hidden="true"><span>404</span></div>
        </div>
    </section>
</main>
