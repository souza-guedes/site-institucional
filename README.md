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

## Deploy

Cada push na `main` que passa no CI é publicado na Hostinger pelo workflow `.github/workflows/deploy.yml`. Pela aba Actions dá para publicar à mão ("Run workflow").

- **Envio:** rsync por SSH, só com `public/`, `src/` e `data/`, com arquivos em 644 e diretórios em 755. No servidor, o código fica em `~/domains/<site>/app`, e o `public_html` do site é um link para `app/public`.
- **Chave:** é só de deploy. No `~/.ssh/authorized_keys` do servidor ela fica presa ao `rrsync` (`command="$HOME/bin/rrsync -wo …/app",restrict`). Assim só escreve na pasta deste site e não abre shell. A conta hospeda outros sites.
- **Secrets do environment `production`:**
  - `DEPLOY_SSH_KEY` (chave privada);
  - `DEPLOY_HOST`, `DEPLOY_PORT` e `DEPLOY_USER`;
  - `DEPLOY_KNOWN_HOSTS` (chave do servidor).

  A variável `DEPLOY_URL` é a URL do site.
- **Teste de fumaça:** depois do envio, o workflow abre as 9 páginas principais e falha se alguma não responder 200 ou exibir mensagem do PHP.
- **Endereço temporário:** em `*.hostingersite.com`, as páginas saem com `X-Robots-Tag: noindex`.
- **Trocar a chave:** gere um par novo (`ssh-keygen -t ed25519`), troque a linha correspondente no `authorized_keys` do servidor e atualize o secret `DEPLOY_SSH_KEY`.

## Conteúdo

Os textos e dados do site ficam em `data/` (`firm-profile.json`, `articles.json`, `privacy-policy.json` e `sitemap-taxonomy.json`), com schemas em `schemas/`. As views em `src/Views/` só leem esses arquivos.

O site não tem formulário nem envia e-mail pelo servidor: o contato é só pelos canais oficiais (telefone, WhatsApp, e-mail e a sede), listados em `contact_channels` do `firm-profile.json`.

## Testes

```bash
php tests/run_tests.php
```

A suíte valida os dados e a conformidade ética (Provimento CFOAB nº 205/2021 e LGPD) e renderiza todas as páginas, falhando em qualquer warning ou notice do PHP.
