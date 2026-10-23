# site-institucional
Portal institucional de autoridade do escritório Souza Guedes Advogados (PHP 8.2+ / Tailwind CSS / Apache)

## Rodar localmente

Requisitos: PHP 8.2+ e, para recompilar o CSS, Node 22+.

```bash
php -S localhost:8080 -t public scripts/dev-router.php
```

Abra `http://localhost:8080`. O `scripts/dev-router.php` serve os arquivos de `public/` e manda o resto para `public/index.php`; em produção o `.htaccess` faz esse papel.

## CSS

O visual vem de `design-system/` (`tokens.css` e `componentes.css`, os mesmos do protótipo e do catálogo da Fase 3). O Tailwind compila esse CSS, com as utilities usadas nas views, em `public/assets/css/main.css`:

```bash
npm ci
npm run build:css   # ou npm run watch:css durante o desenvolvimento
```

O `main.css` gerado é versionado, porque a hospedagem não roda Node. Depois de mudar o design system ou as classes das views, rode o build e faça commit do `main.css`; o CI falha se ele estiver desatualizado.

## Fontes e imagens

As fontes são servidas pelo próprio site, em WOFF2, a partir de `public/assets/fonts/` (`@font-face` em `resources/css/fonts.css`). As fotos ficam em WebP, em vários tamanhos, a partir dos originais em `resources/img/`. Os dois resultados são versionados:

```bash
npm run fonts    # copia as fontes dos pacotes @fontsource
npm run images   # gera as variantes WebP usadas nos srcset
```

## Auditoria de performance e navegadores

Com o servidor local no ar, `npm run audit:lighthouse` roda o Lighthouse (mobile e desktop) e `npm run audit:browsers` testa Chrome, Edge, Firefox e WebKit (antes, rode `npx playwright install firefox webkit`). O relatório da WDLC 6.1 está em `docs/fase-6-testes-qa/`.

## Conteúdo

Os textos e dados do site ficam em `data/` (`firm-profile.json`, `articles.json`, `privacy-policy.json` e `sitemap-taxonomy.json`), com schemas em `schemas/`. As views em `src/Views/` só leem esses arquivos.

O site não tem formulário nem envia e-mail pelo servidor: o contato é só pelos canais oficiais (telefone, WhatsApp, e-mail e a sede), listados em `contact_channels` do `firm-profile.json`.

## Testes

```bash
php tests/run_tests.php
```

A suíte valida os dados e a conformidade ética (Provimento CFOAB nº 205/2021 e LGPD) e renderiza todas as páginas, falhando em qualquer warning ou notice do PHP.
