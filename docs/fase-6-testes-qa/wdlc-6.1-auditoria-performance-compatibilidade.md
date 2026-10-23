# WDLC 6.1 — Auditoria de performance, Core Web Vitals e compatibilidade

> **Status:** Concluída. Metas atingidas em todas as páginas, no servidor local
> **Fase:** 6 — Testes e garantia de qualidade
> **Sub-issue:** [#17](https://github.com/souza-guedes/site-institucional/issues/17)
> **Épico Relacionado:** [#16](https://github.com/souza-guedes/site-institucional/issues/16)
> **Dados:** [`lighthouse-antes.json`](lighthouse-antes.json) e [`lighthouse-resumo.json`](lighthouse-resumo.json) (Lighthouse) · [`navegadores-antes.json`](navegadores-antes.json) e [`navegadores-resumo.json`](navegadores-resumo.json) (navegadores)

---

## 1. Resultado

| Meta da issue | Resultado (pior página) |
| --- | --- |
| Performance acima de 90 | 99 no mobile e 100 no desktop |
| Acessibilidade acima de 95 | 100 |
| Boas Práticas acima de 95 | 100 |
| SEO acima de 95 | 100 |
| LCP abaixo de 2,0 s | 1,96 s no mobile (artigo) e 0,49 s no desktop |
| Imagens em WebP | Todas as fotos em WebP, com `srcset` |
| Chrome, Safari, Firefox e Edge | Chrome, Edge, Firefox e WebKit sem falhas (seção 5). Falta a conferência no Safari de um iPhone (seção 7) |

## 2. Método

- **Onde:** o site ainda não está publicado (o deploy é a Fase 7, [#20](https://github.com/souza-guedes/site-institucional/issues/20) e [#21](https://github.com/souza-guedes/site-institucional/issues/21)). Toda a auditoria roda no servidor local: `php -S localhost:8080 -t public scripts/dev-router.php`.
- **Páginas:** as 9 views. Início, O Escritório, Áreas de Atuação, uma área (`direito-imobiliario`), Corpo Jurídico, Artigos, um artigo (`usucapiao-extrajudicial-requisitos-e-documentos`), Contato e Privacidade. A lista está em `scripts/audit-pages.mjs`.
- **Lighthouse 13.5.0**, no Google Chrome 156 (`npm run audit:lighthouse`):
  - mobile: perfil padrão, com throttling simulado de rede 4G lenta e CPU 4x;
  - desktop: perfil `desktop` do Lighthouse.

  Os relatórios HTML completos ficam em `reports/lighthouse/`, fora do Git. O resumo por página fica em `lighthouse-resumo.json`.
- **Navegadores:** Playwright 1.64.0 (`npm run audit:browsers`), com Google Chrome 156, Microsoft Edge 155, Firefox 157 (build do Playwright) e WebKit 27.2 (o motor do Safari), em 390 px e 1440 px. Em cada página a checagem confere:
  - nenhum erro no console;
  - nenhuma rolagem horizontal;
  - as três fontes carregadas;
  - nenhuma imagem exibida quebrada;
  - em 390 px, a gaveta do menu abre e fecha com Esc.

  As capturas de página inteira ficam em `reports/browsers/`.
- **Compressão:**
  - **Antes:** a medição inicial usou o servidor local sem compressão.
  - **Depois:** o `dev-router.php` passou a comprimir HTML, CSS, JS e SVG com gzip, como o `mod_deflate` do `.htaccess` fará em produção. Parte do ganho do "depois" vem dessa compressão, que só passa a valer de fato quando o site estiver no servidor.

## 3. Lighthouse antes e depois

Notas de 0 a 100. LCP em segundos, TBT em milissegundos. Valor único quando não mudou.

### Mobile

| Página | Performance | Acessibilidade | Boas Práticas | SEO | LCP (s) | CLS | TBT (ms) |
| --- | --- | --- | --- | --- | --- | --- | --- |
| `/` | 89 → 100 | 100 | 96 → 100 | 100 | 3,16 → 1,59 | 0,011 → 0,001 | 0 |
| `/sobre` | 96 → 99 | 100 | 100 | 100 | 2,66 → 1,81 | 0,000 | 0 |
| `/areas-de-atuacao` | 99 → 100 | 100 | 100 | 100 | 1,58 → 1,51 | 0,032 | 0 |
| `/areas-de-atuacao/direito-imobiliario` | 99 → 100 | 100 | 100 | 100 | 1,61 → 1,51 | 0,002 → 0,000 | 0 |
| `/advogados` | 91 → 99 | 100 | 100 | 100 | 3,01 → 1,81 | 0,000 | 0 |
| `/artigos` | 97 → 100 | 98 → 100 | 100 | 100 | 2,48 → 1,21 | 0,028 | 0 |
| `/artigos/usucapiao-…` | 88 → 99 | 100 | 100 | 100 | 3,31 → 1,96 | 0,001 → 0,000 | 0 |
| `/contato` | 100 | 100 | 100 | 100 | 1,43 → 1,51 | 0,029 → 0,024 | 0 |
| `/privacidade` | 99 → 100 | 100 | 100 | 100 | 1,58 → 1,51 | 0,004 | 0 |

### Desktop

| Página | Performance | Acessibilidade | Boas Práticas | SEO | LCP (s) | CLS | TBT (ms) |
| --- | --- | --- | --- | --- | --- | --- | --- |
| `/` | 98 → 100 | 100 | 100 | 100 | 0,77 → 0,40 | 0,069 → 0,001 | 0 |
| `/sobre` | 100 | 100 | 100 | 100 | 0,45 → 0,37 | 0,015 → 0,000 | 0 |
| `/areas-de-atuacao` | 99 → 100 | 100 | 100 | 100 | 0,75 → 0,33 | 0,001 → 0,000 | 0 |
| `/areas-de-atuacao/direito-imobiliario` | 100 | 100 | 100 | 100 | 0,48 → 0,33 | 0,002 → 0,000 | 0 |
| `/advogados` | 99 → 100 | 100 | 100 | 100 | 0,72 → 0,41 | 0,001 | 0 |
| `/artigos` | 99 → 100 | 98 → 100 | 100 | 100 | 0,78 → 0,45 | 0,033 → 0,025 | 0 |
| `/artigos/usucapiao-…` | 99 → 100 | 100 | 100 | 100 | 0,79 → 0,49 | 0,001 → 0,000 | 0 |
| `/contato` | 100 | 100 | 100 | 100 | 0,44 → 0,33 | 0,028 | 0 |
| `/privacidade` | 99 → 100 | 100 | 100 | 100 | 0,74 → 0,33 | 0,026 | 0 |

## 4. O que foi otimizado

### 4.1 Fontes servidas pelo próprio site
**Problema:** a folha do Google Fonts bloqueava a renderização e era o maior atraso do LCP. O Lighthouse estimou cerca de 0,9 s a menos sem ela. Na maioria das páginas, o elemento de LCP é um parágrafo de texto, que só aparece depois da fonte.

**O que mudou:**
- As fontes passaram a ser WOFF2 em `public/assets/fonts/`, só do subconjunto latin, que cobre o português, e só nos 7 pesos usados: Cinzel 400 e 500, Plus Jakarta Sans 500, 600 e 700 e Source Sans 3 400 e 600. São cerca de 97 KB no total, com a licença OFL de cada família.
- Os `@font-face` ficam em `resources/css/fonts.css`, com `font-display: swap`, e entram no `main.css` pelo build.
- Só a Source Sans 3 400, a fonte do texto, tem `preload`. Um preload a mais, da Plus Jakarta Sans 600, competia com as imagens de LCP e deixava `/sobre` e o artigo acima de 2,0 s, então ficou de fora.
- Nenhuma requisição sai mais para domínios da Google. A Política de Privacidade (seção 5) deixou de citar o Google Fonts.
- O protótipo e o catálogo da Fase 3 continuam com o Google Fonts. Eles não são publicados.
- Os arquivos vêm dos pacotes `@fontsource` com versão exata e são copiados por `npm run fonts` (`scripts/copy-fonts.mjs`).

### 4.2 Imagens em WebP com tamanhos responsivos
`npm run images` (`scripts/optimize-images.mjs`, com `sharp`) gera as variantes a partir dos originais em `resources/img/`, em WebP com qualidade 78. Nas views, o `srcset()` de `src/Views/helpers.php` monta os candidatos, e o `sizes` segue a largura real de cada bloco.

| Imagem | Antes | Depois (o que um celular baixa) |
| --- | --- | --- |
| Foto dos sócios (`/sobre`) | JPEG 1000×1250, 118 KB | WebP 720 px, 41 KB |
| Capa de artigo (usucapião, a mais pesada) | JPEG 900×600, 103 KB | WebP 720 px, 42 KB, ou 480 px, 20 KB |
| Capas de inventário e medicamento | JPEG, 36 KB e 56 KB | WebP 720 px, 13 KB e 14 KB |
| Retrato na Home e no Corpo Jurídico | WebP 800 px, 69 a 77 KB | WebP 400 px, 15 a 18 KB |
| Retrato na byline do artigo (52 px) | WebP 800 px, 69 a 77 KB | WebP 104 px, 2,6 KB |

Outros ajustes:
- A imagem que é o LCP (a foto de `/sobre` e a capa do artigo) não tem mais `loading="lazy"` e recebeu `fetchpriority="high"`.
- Os retratos da Home continuam com `loading="lazy"`, porque ficam fora da primeira tela.
- Todas as `<img>` têm `width` e `height`, para não haver deslocamento de layout (CLS).

### 4.3 Outros ajustes do Lighthouse
- **Favicon:** o navegador pedia `/favicon.ico`, que não existia, e o erro 404 no console custava pontos em Boas Práticas na Home. Agora há um favicon SVG com o monograma oficial em latão sobre petróleo profundo (`public/favicon.svg`) e o ícone de 180 px para a tela inicial do iOS (`public/apple-touch-icon.png`).
- **Ordem dos títulos em `/artigos`:** o título do artigo em destaque era `<h3>` logo depois do `<h1>`. Passou a ser `<h2>`, e Acessibilidade foi de 98 para 100.

### 4.4 Servidor de produção (`public/.htaccess`)
Estas regras não são medidas aqui, porque o servidor local não lê o `.htaccess`:
- `mod_deflate` para HTML, CSS, JS, SVG e JSON;
- cache de 1 ano (`immutable`) para fontes, CSS e JS. CSS e JS levam `?v=<data de modificação>` no link, pelo `asset()` de `src/Views/helpers.php`, então uma versão nova nunca fica presa no cache;
- cache de 30 dias para imagens, que mantêm o nome quando são trocadas.

## 5. Compatibilidade entre navegadores

72 combinações: 4 navegadores × 2 larguras × 9 páginas.

| Navegador | 390 px | 1440 px |
| --- | --- | --- |
| Google Chrome 156 | 9/9 | 9/9 |
| Microsoft Edge 155 | 9/9 | 9/9 |
| Firefox 157 | 9/9 | 9/9 |
| WebKit 27.2 (motor do Safari) | 9/9 | 9/9 |

**Antes das otimizações:** 70 de 72. As duas falhas eram o 404 do `/favicon.ico` na Home, em Chrome e Edge.

## 6. Limitações

- **Rede simulada:** os números são do servidor local, sem HTTPS e sem a latência da rede real. O Lighthouse simula a rede do celular, mas o tempo de resposta do servidor de produção e a distância até ele não entram na conta. Vale repetir a auditoria no endereço definitivo depois do deploy (Fase 7). O comando é o mesmo, com `AUDIT_BASE_URL=https://… npm run audit:lighthouse`.
- **LCP no limite:** o LCP do artigo (1,96 s) está perto da meta. A capa do artigo de usucapião é uma foto com muitos detalhes, e baixar a qualidade economizaria pouco (de 42 para 34 KB). Se um artigo novo tiver uma capa pesada, vale conferir de novo.
- **Safari:** o WebKit do Playwright é o motor do Safari, mas não é o Safari do iPhone. Por isso a seção 7 pede uma conferência em aparelho real.
- **Glifo da seta:** a seta "→" do link de voltar, no detalhe da área, não existe no subconjunto latin. Ela aparece com a fonte do sistema, como já acontecia com o Google Fonts.

## 7. Roteiro para o Safari no iPhone (cerca de 5 minutos)

Com o site publicado (ou o servidor local acessível pela rede), abra no Safari do iPhone:

1. **Início:** o wordmark "Souza Guedes" aparece em Cinzel e o texto em Source Sans, sem trocar de fonte depois de carregar. Role até o fim e confira que os retratos e o carrossel de artigos aparecem.
2. **Menu:** toque em "Menu". A gaveta abre, os links funcionam, e tocar fora dela a fecha.
3. **Artigos:** troque os filtros por área. Abra um artigo e confira a capa e a foto pequena do autor.
4. **Contato:** toque em "Copiar" no e-mail e no telefone e cole em outro app. O aviso "Copiado" aparece.
5. **Aviso de cookies:** toque em "Entendi" e recarregue a página. O aviso não volta.
6. **Ícone:** "Compartilhar → Adicionar à Tela de Início" mostra o monograma.

Anote o que sair diferente no comentário da [#17](https://github.com/souza-guedes/site-institucional/issues/17).

## 8. Como repetir

```bash
npm ci
npx playwright install firefox webkit
php -S localhost:8080 -t public scripts/dev-router.php   # em outro terminal
npm run audit:lighthouse
npm run audit:browsers
```

Depois de trocar uma foto em `resources/img/`, rode `npm run images`. Depois de mudar as fontes, rode `npm run fonts` e `npm run build:css`.
