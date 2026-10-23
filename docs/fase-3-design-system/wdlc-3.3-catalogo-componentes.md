# WDLC 3.3 — Catálogo de componentes UI

> **Status:** Aguardando homologação dos sócios  
> **Fase:** 3 — Design System, UI/UX mobile-first e acessibilidade  
> **Sub-issue:** [#22](https://github.com/souza-guedes/site-institucional/issues/22)  
> **Épico Relacionado:** [#7](https://github.com/souza-guedes/site-institucional/issues/7)  
> **Dependências:** tokens do WDLC 3.1 ([`design-system/tokens.css`](../../design-system/tokens.css)) e mockups do WDLC 3.2 ([`wdlc-3.2-mockups-alta-fidelidade.md`](wdlc-3.2-mockups-alta-fidelidade.md))  
> **Catálogo navegável:** [`wdlc-3.3-componentes/catalogo.html`](wdlc-3.3-componentes/catalogo.html)  
> **Apresentação dos componentes:** https://claude.ai/artifact/3AoERiPyhMgnBbDdQmR3xc

---

## 1. Entregável

Um catálogo navegável em HTML com os 11 grupos de componentes do site. A barra de revisão no topo, a mesma do protótipo da 3.2, permite:

- escolher o componente;
- alternar entre **Mobile 390px** e **Desktop 1440px**;
- ver o partial alvo da Fase 5 e a nota de conformidade de cada componente.

Cada página do catálogo traz o componente em todas as variantes e estados, as classes, os tokens e os dados que ele usa, o partial da Fase 5 e uma lista de "Fazer" e "Não fazer".

**Para abrir:**

- **Versão autônoma:** `catalogo-componentes-souza-guedes.html`, na raiz do repositório. Abre com dois cliques e mostra tudo.
- **Pela pasta:** na pasta `docs/fase-3-design-system/`, rode `python -m http.server` e acesse `/wdlc-3.3-componentes/catalogo.html`.
- **Direto pelo arquivo** (`file://`): o navegador bloqueia a leitura do CSS, e os estados de hover e foco **parados** não aparecem. A barra de notas avisa quando isso acontece.

**Estados parados:** para mostrar hover e foco sem passar o mouse, o catálogo copia, ao carregar, cada regra `:hover` e `:focus-visible` do `componentes.css` para as classes `.is-hover` e `.is-focus`. Assim o estado mostrado é sempre o que o CSS real define.

**Apresentação:** as 11 páginas, em desktop e celular, estão capturadas numa apresentação no mesmo formato da 3.2: https://claude.ai/artifact/3AoERiPyhMgnBbDdQmR3xc. Atalhos: ←/→ trocam de componente, D alterna desktop/celular, N mostra as notas, F abre em tela cheia. A fonte está em [`wdlc-3.3-componentes/apresentacao/`](wdlc-3.3-componentes/apresentacao/). Para atualizar, rode `build-standalone.py` (WDLC 3.2) e depois `apresentacao/capturar-componentes.py`.

> O catálogo é artefato de design. **Não** é a implementação da Fase 5: nenhuma view, partial PHP ou `public/assets/css/main.css` foi alterado nesta issue.

## 2. Componentes

| # | Componente | Variantes | Estados | Partial alvo (Fase 5) |
| --- | --- | --- | --- | --- |
| 1 | Cor e tipografia | 14 cores; 8 estilos de texto | mobile / desktop | — (tokens) |
| 2 | Botões, links e filtros | primário, ghost, light (escuro), link, link com seta, chip | default, hover, focus, disabled, ativo | classes utilitárias |
| 3 | Elementos de marca | moldura aberta, selo, quadrado, barra, composição do hero | — | classes utilitárias |
| 4 | Header e navbar | desktop, menu mobile | item ativo, hover, focus, fixo ao rolar, menu aberto | `partials/header.php`, `partials/navbar.php` |
| 5 | Hero e cabeçalho de página | hero da Home; cabeçalho com lead, sem lead, com aviso, com tags | — | `partials/hero.php`, `partials/page-header.php` |
| 6 | Card de Área de Atuação | compacto, largo (subáreas + tags), lista "outras áreas" | default, hover, focus | `partials/area-card.php` |
| 7 | Card de Advogado | compacto (seção escura), perfil (claro e escuro), byline, card de autor | — | `partials/lawyer-card.php`, `partials/byline.php` |
| 8 | Bloco de Artigo | card, destaque, carrossel | default, hover, focus, primeira posição do carrossel, **lista vazia (proposta)** | `partials/article-card.php`, `partials/article-carousel.php` |
| 9 | Formulário de contato | texto, e-mail, telefone opcional, select, textarea com dica, checkbox LGPD | vazio, focus, preenchido, erro, enviado, botão desabilitado | `partials/contact-form.php`, `partials/form-field.php` |
| 10 | Atendimento e avisos | CTA final, cards de canal, painel lateral, aviso, passos | — | `partials/cta.php`, `partials/channels.php`, `partials/notice.php` |
| 11 | Rodapé e banner de cookies | rodapé completo, banner | hover, focus | `partials/footer.php`, `partials/cookie-banner.php` |

Os partials que já existem (`header.php`, `navbar.php`, `footer.php`, `cookie-banner.php`) são reescritos na Fase 5. Os demais são nomes propostos.

### Checklist da issue

| Item da #22 | Onde está no catálogo |
| --- | --- |
| Card de Advogado: foto, nome, titulação, áreas, OAB/SP | 7 · Card de Advogado |
| Card de Área: ícone, título "Áreas de Atuação", resumo, link | 6 · Card de Área de Atuação |
| Bloco de Artigo: tag da área, data, autor, leitura | 8 · Bloco de Artigo |
| Hero institucional sóbrio com CTA de contato | 5 · Hero e cabeçalho de página |
| Navbar e Footer com OAB dos sócios e disclaimer | 4 · Header e navbar; 11 · Rodapé |
| Elementos de formulário (campos, labels, erros, disclaimer) | 9 · Formulário de contato |
| Estados default, hover, focus, disabled, vazio e erro | 2, 4, 6, 8, 9, 11 |
| Variantes mobile e desktop | Todas as páginas (Mobile 390 / Desktop 1440) |

## 3. Mudanças no design system

Esta issue resolveu as pendências de token que a [spec de uso dos mockups](wdlc-3.2-spec-uso-mockups.md) tinha delegado a ela:

| Mudança | Arquivos |
| --- | --- |
| Cor `erro #9B2C1F`: só estados de erro de formulário. 7,57:1 sobre branco, 6,78:1 sobre papel; proibida em seção escura (1,62:1). O valor de 6,9:1 citado na 3.2 estava errado. | `tokens.json`, `tokens.css`, `tailwind.config.js`, `wdlc-3.1-auditoria-wcag.md` |
| Tipografia mobile-first: `display-mobile`, `h1-mobile`, `h2-mobile`, `h3-mobile`, `lead-mobile`. `display` passa a `.03em`; `h1` passa a 44/54 (título do artigo). As classes `.t-*` de `tokens.css` agora são mobile-first. | idem |
| Breakpoints `sm` 560px, `md` 760px, `lg` 1100px, iguais aos do protótipo | `tokens.json`, `tailwind.config.js` |
| `radius-full` sem uso aprovado (o botão flutuante de WhatsApp foi descartado na 3.2) | `tokens.json` |
| CSS do site extraído do protótipo para `componentes.css` (hoje em `design-system/`, desde a WDLC 5.1), que importa `tokens.css` e usa os nomes oficiais (`--space-*`, `--radius-sm`). O CSS da barra de revisão foi para `revisao.css`. As 21 capturas do protótipo ficaram idênticas às de antes da extração. | `wdlc-3.3-componentes/`, `wdlc-3.2-mockups/prototipo.html`, `build-standalone.py` |

## 4. Conformidade OAB e WCAG por componente

Cada página do catálogo traz a lista "Fazer / Não fazer" completa. O resumo:

- [x] **Sobriedade:** sem pop-ups, badges, botão flutuante, contadores ou selos. Banner de cookies não bloqueante. Carrossel sem autoplay.
- [x] **Terminologia:** "Áreas de Atuação" e "Prática jurídica". "Especialista" só aparece nas listas de "Não fazer" e no aviso de que área de atuação não é título de especialista.
- [x] **Identificação:** nome + OAB/SP no hero, nos cards de advogado, na autoria dos artigos e no rodapé.
- [x] **Disclaimers:** Provimento 205/2021 no rodapé; natureza educativa nos artigos (Art. 4º); "não estabelece relação advogado-cliente" no formulário e nos blocos de atendimento.
- [x] **Contraste:** só combinações da auditoria 3.1, incluindo as novas linhas da cor `erro`.
- [x] **Acessibilidade de interação:** foco visível em todos os controles; `aria-current`, `aria-pressed`, `aria-expanded`, `aria-invalid` e `aria-describedby` documentados nos componentes que os usam.

## 5. Pendências para homologação

| Item | Responsável | Observação |
| --- | --- | --- |
| **Aprovação formal do catálogo** | Sócios | Critério de aceite da issue #22, junto com a homologação dos mockups (#9). |
| Estado de lista vazia em Artigos | Sócios | Proposta nova, não estava nos mockups da 3.2. |
| Rota `/artigos/{slug}` | Sócios / Fase 2 | O bloco de artigo e a byline assumem a página do artigo, ainda pendente de aprovação na #9. |
| Mapa da sede | Fase 5 | `.map` é ilustração provisória; o mapa real entra como link externo, sem iframe de terceiros. |
