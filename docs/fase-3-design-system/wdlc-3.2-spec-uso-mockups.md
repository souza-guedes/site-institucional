# WDLC 3.2 — Spec de uso dos mockups

> **Status:** Proposta. Vale a partir da homologação dos mockups pelos sócios.  
> **Fase:** 3 — Design System, UI/UX mobile-first e acessibilidade  
> **Sub-issue:** [#9](https://github.com/souza-guedes/site-institucional/issues/9)  
> **Público:** quem vai implementar a Fase 5 (views PHP + Tailwind) e quem vai montar o catálogo de componentes da [#22](https://github.com/souza-guedes/site-institucional/issues/22)  
> **Entrega de origem:** [`wdlc-3.2-mockups-alta-fidelidade.md`](wdlc-3.2-mockups-alta-fidelidade.md)

---

## 1. Para que serve esta spec

Os mockups da WDLC 3.2 são a referência visual de todas as telas do site. Esta spec define:

- qual arquivo consultar em cada situação;
- o que do protótipo vira produção e o que é só cenário de revisão;
- como traduzir o protótipo (container queries, CSS próprio, dados embutidos) para o stack do projeto (PHP + Tailwind + `data/*.json`);
- o que fazer quando o mockup, os tokens e os dados discordam;
- como alterar os mockups depois da homologação.

Ela não substitui o catálogo de componentes (#22) nem a copy final (Fase 4).

## 2. Arquivos e quando usar cada um

| Arquivo | Para que serve | Quem usa |
| --- | --- | --- |
| [`wdlc-3.2-mockups/prototipo.html`](wdlc-3.2-mockups/prototipo.html) | **Fonte de verdade do layout.** HTML + CSS + JS legíveis, com as imagens em `assets/`. É o único arquivo que se edita. | Fase 5, #22 |
| `prototipo-souza-guedes.html` (raiz do repositório) | Cópia autônoma com as imagens embutidas, gerada por `build-standalone.py`. Serve para abrir com dois cliques ou mandar por e-mail. **Nunca editar à mão.** | Sócios, revisão rápida |
| [`wdlc-3.2-mockups/apresentacao/`](wdlc-3.2-mockups/apresentacao/) e o link https://claude.ai/artifact/9KUxcLx4LV4qbWviWRQqvg | Capturas das telas em moldura de dispositivo, para homologação. São imagens: não servem para medir nem copiar código. | Sócios |
| [`wdlc-3.2-mockups/assets/`](wdlc-3.2-mockups/assets/) | Logos e fotos já tratados (ver seção 8). | Fase 5 |
| [`design-system/tokens.css`](../../design-system/tokens.css), [`tokens.json`](../../design-system/tokens.json), [`tailwind.config.js`](../../tailwind.config.js) | Valores oficiais de cor, tipo, espaçamento e raio. | Fase 5, #22 |

**Como abrir o protótipo:** na pasta `wdlc-3.2-mockups/`, rode `python -m http.server` e acesse `http://localhost:8000/prototipo.html`, ou abra o arquivo direto no navegador. A barra escura no topo escolhe a tela, alterna **Mobile 390px / Desktop 1440px** e mostra a rota e a nota de conformidade de cada tela.

## 3. Ordem de precedência

Quando duas fontes discordarem, vale a primeira da lista:

1. **Regras éticas da OAB** ([`data/ethical-compliance-rules.json`](../../data/ethical-compliance-rules.json), [matriz WDLC 1.2](../fase-1-analise/wdlc-1.2-matriz-conformidade-etica-oab.md)). Nenhum mockup autoriza descumprir uma regra.
2. **Auditoria WCAG** ([WDLC 3.1](wdlc-3.1-auditoria-wcag.md)): só as combinações de cor aprovadas.
3. **Dados** ([`data/firm-profile.json`](../../data/firm-profile.json), [`data/sitemap-taxonomy.json`](../../data/sitemap-taxonomy.json)): nomes, OAB, rotas, áreas, canais.
4. **Tokens** (`design-system/` e `tailwind.config.js`).
5. **Mockups homologados** (`prototipo.html`): layout, hierarquia, composição, estados.
6. **Componentes da #22**, quando existirem, para detalhes de variante e estado.

Se a implementação precisar divergir do mockup, a divergência é registrada na seção 10 deste documento (ou numa issue) antes do merge, e não decidida em silêncio no código.

## 4. O que é produção e o que é cenário

O protótipo mistura o site com um "palco" de revisão. **Não levar para produção:**

| No protótipo | Por quê |
| --- | --- |
| `.review`, `.seg`, `.notes`, `#screen-select` | Barra de revisão |
| `.stage`, `.frame`, `--palco`, `.frame[data-vp="mobile"]` (moldura arredondada) | Palco e moldura de celular |
| Navegação por hash (`#inicio`, `#area-…`), `route()`, `SCREENS`, `fit()`, `setVP()` | O site real usa as rotas do `Router` PHP |
| `.toast` e o botão "Copiar" que só mostra aviso | Revisar com a #22 se o botão "Copiar" entra no site |
| Envio do formulário que só troca de estado na tela | O envio real é da WDLC 5.2 |
| Textos marcados como provisórios (`.draft`) | A copy final vem da Fase 4 |

**Levar para produção** (layout e comportamento): header fixo com sombra ao rolar, menu mobile com *scrim*, breadcrumbs, filtros PF/PJ e por área, carrossel de artigos, validação do formulário com mensagens por campo, banner de cookies não bloqueante, `:focus-visible`, `prefers-reduced-motion`.

## 5. Mapa tela → rota → código

| Tela no protótipo (`data-screen`) | Rota | Wireframe | Controller | View |
| --- | --- | --- | --- | --- |
| `inicio` | `/` | WF-01 | `HomeController::index` | `src/Views/pages/home.php` |
| `sobre` | `/sobre` | WF-02 | `AboutController::index` | `about.php` |
| `areas-de-atuacao` | `/areas-de-atuacao` | WF-03 | `PracticeAreaController::index` | `practice-areas.php` |
| `area` (gerada por `renderDetail`) | `/areas-de-atuacao/{slug}` | WF-04 | `PracticeAreaController::detail` | `practice-area-detail.php` |
| `advogados` | `/advogados` | WF-05 | `LawyerController::index` | `lawyers.php` |
| `artigos` | `/artigos` | WF-06 | `ArticleController::index` | `articles.php` |
| `artigo` (gerada por `renderArticle`) | `/artigos/{slug}` | WF-06b | **não existe ainda** | **não existe ainda** |
| `contato` | `/contato` | WF-07 | `ContactController::index` | `contact.php` |
| `privacidade` | `/privacidade` | WF-08 | `PrivacyController::index` | `privacy.php` |
| `404` | qualquer rota inexistente | — | `ErrorController::notFound` | `404.php` |

Blocos comuns a todas as telas: `.hdr` + `.mnav` → `partials/header.php` e `partials/navbar.php`; `.cta` (bloco "Atendimento") e `.ftr` → `partials/footer.php`; `.cookie` → `partials/cookie-banner.php`.

**A rota `/artigos/{slug}` só pode ser implementada depois que os sócios a aprovarem.** Aprovada, ela entra no `sitemap-taxonomy.json` e no `public/index.php` antes da view.

## 6. Tradução do CSS do protótipo

### 6.1 Breakpoints

O protótipo responde a **container queries** no `.frame` (`container-name: site`), para mostrar as duas larguras lado a lado. Em produção, use media queries na viewport com os mesmos pontos:

| Protótipo | Produção | Uso |
| --- | --- | --- |
| base (390px) | base | mobile |
| `@container site (min-width:560px)` | `min-width: 560px` | ajuste pontual do Corpo Jurídico |
| `@container site (min-width:760px)` | `min-width: 760px` | tablet: tipo maior, grades de 2 colunas |
| `@container site (min-width:1100px)` | `min-width: 1100px` | desktop: navegação completa, composição do hero |

O `tailwind.config.js` hoje não define `screens`, então valem os padrões do Tailwind (640/768/1024/1280), que **não batem** com o protótipo. Ao iniciar a Fase 5, adicione ao config:

```js
screens: { sm: '560px', md: '760px', lg: '1100px' },
```

O conteúdo fica limitado a `max-width: 1200px` (`.wrap`), com margem lateral de 16px (mobile), 32px (≥760px) e 48px (≥1100px).

### 6.2 Tokens

O protótipo usa apelidos curtos dos mesmos valores. Em produção, use os nomes oficiais:

| Protótipo | `tokens.css` | Tailwind |
| --- | --- | --- |
| `--s1` … `--s16` | `--space-1` … `--space-16` | `p-1` … `p-16` (8 a 128px) |
| `--r-sm` | `--radius-sm` | `rounded-sm` |
| `--turquesa`, `--petroleo` etc. | iguais | `bg-petroleo`, `text-tinta` etc. |
| `--font-display`, `--font-titulo`, `--font-sans` | iguais | `font-display`, `font-titulo`, `font-sans` |

Nenhum valor hexadecimal pode ser escrito direto nas views ou no CSS. A exceção é `--erro`, enquanto não for incorporado (ver 6.4).

### 6.3 Tipografia: onde o protótipo difere dos tokens

Os tokens de tipo do WDLC 3.1 só têm o tamanho desktop. O protótipo define também o mobile e, em dois pontos, faz escolhas diferentes:

| Elemento | Protótipo (mobile → ≥760px) | Token atual | Decisão |
| --- | --- | --- | --- |
| H1 de página (`.display`) | Cinzel 30/38 → 44/52, `letter-spacing .03em` | `display` 44/52, `.04em` | Seguir o protótipo; ajustar o token na #22 |
| H1 do artigo (`.art-h1`) | Plus Jakarta 30/38 → 44/54 | `h1` 46/54 | Seguir o protótipo |
| Token `h1` (Plus Jakarta 46/54) | não é usado em nenhuma tela | — | Revisar na #22 (manter ou remover) |
| `.h2` | 26/34 → 34/42 | `h2` 34/42 | Token = desktop; mobile segue o protótipo |
| `.h3` | 20/28 → 22/30 | `h3` 22/30 | Idem |
| `.lead` | 19/29 → 20/30 | `lead` 20/30 | Idem |
| Wordmark do hero (`.wordmark`) | Cinzel 60 → 92 → 112px | — | Exclusivo da Home; não vira token |

Os tamanhos mobile entram como tokens na #22. Até lá, a Fase 5 usa os valores da tabela acima.

### 6.4 Cor nova: `--erro`

`--erro: #9B2C1F` (6,9:1 sobre branco) é a única cor fora dos tokens. Ela é usada só nas mensagens e bordas de erro do formulário. Precisa entrar em `tokens.json`, `tokens.css`, `tailwind.config.js` e na auditoria WCAG (pela #22) **antes** de ser usada em produção.

### 6.5 Elementos de marca

Estes padrões são a assinatura visual e devem ser reproduzidos com fidelidade, não aproximados:

- **Moldura aberta (`.of`)**: fio de 1px com uma interrupção no lado esquerdo, feito com `mask`. Os parâmetros `--of-inset`, `--of-a` e `--of-b` controlam o recuo e a posição do corte.
- **Selo (`.seal`)**: painel petróleo-profundo com moldura dupla (externa aberta, interna fechada).
- **Quadrado petróleo (`.sq`, marcador dos eyebrows)**.
- **Composição do hero (`.comp`)** e a versão compacta das páginas internas (`.phead__comp`), que no mobile mostram só uma ponta da composição.
- **Quinas retas**: raio 0 em cards e 2px em botões e campos. Não usar `rounded-full`. O botão flutuante de WhatsApp previsto no `tokens.json` foi descartado (seção 7).

Esses elementos são decorativos e levam `aria-hidden="true"`.

## 7. Regras que a implementação não pode quebrar

Derivadas da conformidade visual OAB da #9 e da auditoria WCAG da #8. Valem para cada view da Fase 5.

**OAB (Provimento 205/2021)**
- [ ] Sem pop-ups, modais de captação, badges flutuantes, botão flutuante de WhatsApp, rankings ou números de "casos ganhos".
- [ ] "Áreas de Atuação" / "Prática jurídica" em toda a navegação; a palavra "especialista" não aparece.
- [ ] Nome + OAB/SP dos sócios no hero da Home, no bloco Corpo Jurídico, em `/advogados` e no rodapé de todas as páginas.
- [ ] Disclaimer do Provimento 205/2021 no rodapé; aviso de natureza educativa em `/artigos` (Art. 4º); "não estabelece relação advogado-cliente" no formulário e no painel das áreas.
- [ ] Atendimento sempre passivo: o CTA convida ao contato, não promete resultado.

**Acessibilidade (WCAG 2.1 AA)**
- [ ] Turquesa nunca é texto nem fundo de texto. Ela só aparece como bloco ou fio decorativo, encostada no petróleo-profundo.
- [ ] Névoa só como superfície de bloco, com texto em tinta. Tags e filtros usam contorno, não fundo névoa.
- [ ] Latão só sobre fundo escuro; latão-escuro sobre papel.
- [ ] Foco visível (`outline` 2px petróleo; turquesa em seções escuras).
- [ ] `aria-current="page"` no item ativo da navegação e no último item do breadcrumb.
- [ ] Erros do formulário associados ao campo (mensagem por campo, não só a cor).
- [ ] Animações desligadas com `prefers-reduced-motion`.

## 8. Imagens e logos

- Use os arquivos de `wdlc-3.2-mockups/assets/` como estão: eles já foram tratados (fundo removido, escala igualada, saturação ajustada). Copie-os para `public/assets/img/` na Fase 5, sem reprocessar.
- Os logos SVG usam `currentColor`: a cor vem do CSS (tinta no header, papel no rodapé, turquesa no monograma do rodapé).
- Fotos dos artigos: Licença Unsplash, com os créditos registrados no [documento de entrega](wdlc-3.2-mockups-alta-fidelidade.md#fotos-dos-artigos-banco-de-imagens). Proporção 3:2, 900×600.
- A foto `socios-palacio.jpg` não pode ter legenda que sugira ser a sede do escritório.
- Todas as imagens com conteúdo levam `alt` descritivo. Retratos usam o nome completo do advogado.

## 9. Dados: não copiar do protótipo

O JS do protótipo tem cópias de diagramação dos dados (`AREAS`, `ARTICLES`, `CHANNEL_GROUPS`, `OAB`, `AUTHOR_ROLE`). **Na Fase 5, nenhum desses valores é copiado para as views.**

| No protótipo | Fonte em produção |
| --- | --- |
| `AREAS` (título, escopo, ritos, público PF/PJ, ícone) | `data/sitemap-taxonomy.json` |
| `OAB`, `AUTHOR_ROLE`, nomes e formação dos sócios | `data/firm-profile.json` |
| `CHANNEL_GROUPS` (telefone, e-mail, endereço) | `data/firm-profile.json` |
| `ARTICLES` | Fonte a definir na Fase 4/5 (os 6 artigos são exemplos de diagramação) |
| Textos das páginas | Copy da Fase 4 |

Os ícones das áreas (`ICONS`) e dos canais (`CH_ICONS`) são SVGs de traço 1,5px e podem ser reaproveitados como partials.

## 10. Como alterar os mockups depois da homologação

1. Abra uma issue (ou um comentário na #9) com o pedido e quem pediu.
2. Edite **só** `prototipo.html` (e `assets/`, se for o caso).
3. Gere a cópia autônoma: `python docs/fase-3-design-system/wdlc-3.2-mockups/build-standalone.py`
4. Recapture as telas: `python docs/fase-3-design-system/wdlc-3.2-mockups/apresentacao/capturar-telas.py` (precisa do Chrome instalado e do Pillow).
5. Republique a apresentação no mesmo link.
6. Registre a mudança na seção 3 ("Decisões de design") do [documento de entrega](wdlc-3.2-mockups-alta-fidelidade.md), com a data.
7. Se a mudança afetar uma tela já implementada, abra a correção correspondente na Fase 5.

Mudanças que tocam regra OAB, contraste ou rota passam de novo pelos sócios.

**Divergências registradas entre mockup e implementação:** *(nenhuma até agora)*

## 11. Pendências que bloqueiam o uso

| Pendência | Bloqueia |
| --- | --- |
| Aprovação formal dos mockups pelos sócios | Toda a Fase 5 (o mockup só vira referência depois disso) |
| Aprovação da rota `/artigos/{slug}` | View e rota do artigo |
| `--erro` incorporado aos tokens e auditado | Estados de erro do formulário |
| Tamanhos mobile de tipografia nos tokens (#22) | Nada: a Fase 5 usa a tabela 6.3 até lá |
| Prazo de resposta do formulário ("até 2 dias úteis") | Texto do formulário |
| Definição do encarregado de dados (DPO) | Seção 5 de `/privacidade` |

## 12. Critério de fidelidade na Fase 5

Uma view está fiel ao mockup quando, comparada lado a lado com o protótipo em 390px e 1440px:

- tem as mesmas seções, na mesma ordem e com as mesmas faixas de fundo;
- usa só tokens (ou o `--erro` já incorporado) e as fontes previstas;
- reproduz os elementos de marca da seção 6.5;
- mostra os estados de interface do protótipo (navegação ativa, menu aberto, filtros, hover/focus, erros, envio concluído, botão desabilitado);
- passa no checklist da seção 7;
- não tem barra de rolagem horizontal entre 320px e 1440px.

Diferenças de texto não contam, porque a copy da Fase 4 substitui os textos de diagramação.
