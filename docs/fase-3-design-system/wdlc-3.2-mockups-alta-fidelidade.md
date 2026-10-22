# WDLC 3.2 — Mockups de Alta Fidelidade (Sitemap Completo)

> **Status:** Aguardando homologação dos sócios  
> **Fase:** 3 — Design System, UI/UX mobile-first e acessibilidade  
> **Sub-issue:** [#9](https://github.com/souza-guedes/site-institucional/issues/9)  
> **Épico Relacionado:** [#7](https://github.com/souza-guedes/site-institucional/issues/7)  
> **Dependências:** tokens do WDLC 3.1 ([`design-system/tokens.css`](../../design-system/tokens.css)) e wireframes do WDLC 2.2 ([`wdlc-2.2-wireframes-taxonomia.md`](../fase-2-planejamento/wdlc-2.2-wireframes-taxonomia.md))  
> **Protótipo navegável:** [`wdlc-3.2-mockups/prototipo.html`](wdlc-3.2-mockups/prototipo.html)  
> **Apresentação das telas (link público):** https://claude.ai/artifact/9KUxcLx4LV4qbWviWRQqvg  
> **Spec de uso (handoff Fase 5 / #22):** [`wdlc-3.2-spec-uso-mockups.md`](wdlc-3.2-spec-uso-mockups.md)

---

## 1. Entregável

Um protótipo navegável em HTML único com as 9 telas do sitemap. Uma barra de revisão no topo permite:

- escolher a tela (WF-01 a WF-08 e 404);
- alternar entre **Mobile 390px** e **Desktop 1440px** — o mesmo layout responde por *container queries*, então as duas versões são sempre coerentes;
- ver a rota canônica e a nota de conformidade OAB da tela atual.

Para abrir localmente: abra o arquivo no navegador (ou `python -m http.server` na pasta). Os links internos do site navegam entre as telas.

### Apresentação para homologação

As 10 telas também estão capturadas em mockups de dispositivo (desktop e celular), numa apresentação no estilo do modo Preview do Stitch: https://claude.ai/artifact/9KUxcLx4LV4qbWviWRQqvg. O link abre sem conta, e quem tiver o link sempre vê a versão mais recente. Atalhos: ←/→ trocam de tela, D alterna desktop/celular, N mostra as notas, F abre em tela cheia.

A fonte da apresentação está em [`wdlc-3.2-mockups/apresentacao/`](wdlc-3.2-mockups/apresentacao/). Para atualizar as capturas depois de mudar o protótipo, rode `build-standalone.py` e depois `apresentacao/capturar-telas.py`.

> O protótipo é artefato de design. **Não** é a implementação da Fase 5: nenhuma view PHP foi alterada nesta issue.

## 2. Cobertura de telas

| Tela | Rota | Wireframe | Mobile | Desktop |
| --- | --- | --- | --- | --- |
| Início | `/` | WF-01 | ✅ | ✅ |
| O Escritório | `/sobre` | WF-02 | ✅ | ✅ |
| Áreas de Atuação | `/areas-de-atuacao` | WF-03 | ✅ | ✅ |
| Detalhe de área (os 4 slugs) | `/areas-de-atuacao/{slug}` | WF-04 | ✅ | ✅ |
| Corpo Jurídico | `/advogados` | WF-05 | ✅ | ✅ |
| Artigos e Informações | `/artigos` | WF-06 | ✅ | ✅ |
| Artigo (os 6 exemplos) — **proposta** | `/artigos/{slug}` | WF-06b | ✅ | ✅ |
| Contato | `/contato` | WF-07 | ✅ | ✅ |
| Privacidade e LGPD | `/privacidade` | WF-08 | ✅ | ✅ |
| Página 404 | *fallback* | — | ✅ | ✅ |

Estados de UI demonstrados: navegação ativa (`aria-current`), menu mobile aberto/fechado, filtros PF/PJ e por área, hover/focus de cards e botões, erros de validação do formulário (enviar vazio), envio concluído e botão desabilitado.

## 3. Decisões de design

- **Narrativa com os elementos da marca.** O hero reproduz a peça de identidade: wordmark em Cinzel e a composição de blocos (névoa com moldura aberta, quadrado petróleo, barra turquesa e painel petróleo-profundo com selo). Cada seção seguinte da Home retoma um desses elementos:

  | Seção | Elemento retomado |
  | --- | --- |
  | Valores (faixa branca própria, separada do hero) | quadrado petróleo (marcador de cada valor e de todos os eyebrows) |
  | Áreas de Atuação | moldura aberta (borda dos cards) e quadrado petróleo (ícone) |
  | Corpo Jurídico | painel petróleo-profundo com moldura dupla aberta |
  | Artigos | névoa com moldura aberta (aviso educativo) |
  | Atendimento (CTA) | barra turquesa ancorada no petróleo-profundo; canais agrupados em "Fale com o escritório" e "Visite a sede" |

  As páginas internas abrem com uma versão compacta da composição (barra turquesa + selo) à direita, no desktop.
- **Regras de cor (WCAG 2.1 AA):**
  - turquesa nunca é texto nem fundo de texto; aparece só como bloco ou fio decorativo, sempre encostada no petróleo-profundo;
  - névoa só como superfície de bloco, com texto em tinta (14,52:1); tags e filtros deixaram de usar fundo névoa e passaram a ser contorno;
  - latão só sobre fundo escuro (OAB dos sócios na seção escura: 5,49:1); latão-escuro sobre papel (5,35:1);
  - ícones brancos sobre quadrado petróleo (7,92:1).
- **Tipografia:** Cinzel para wordmark, títulos de página (H1) e eyebrows, para dar o tom institucional; Plus Jakarta Sans nos títulos de seção; Source Sans 3 no corpo (17/27px).
- **Quinas retas** (radius 0 nos cards, 2px em botões e campos), fios de 1px como assinatura gráfica herdada do monograma.
- **Conteúdo real** de `data/firm-profile.json` e `data/sitemap-taxonomy.json`: nomes, OAB, formação, endereço, canais, escopo e ritos de cada área.
- Uma cor nova fora dos tokens: `--erro #9B2C1F` (6,9:1 sobre branco) para mensagens de erro do formulário. Proposta para incorporar ao design system em #22.

- **O Escritório sem a seção "Sede"** (removida a pedido em 21/10/2026). A página segue com Trajetória (branco) e Ética (papel), e o endereço da sede continua no Contato, no rodapé e no bloco de atendimento.

## 4. Conformidade visual OAB

- [x] **Sobriedade:** sem pop-ups, sem badges flutuantes, sem rankings ou números de "casos ganhos". O botão flutuante de WhatsApp previsto no tokens.json (`radius-full`) **não foi usado**, por conflitar com a regra de badges flutuantes desta issue. O banner de cookies é uma faixa não bloqueante.
- [x] **Terminologia:** "Áreas de Atuação" e "Prática jurídica" em toda a navegação; "especialista" não aparece. Aviso explícito no Corpo Jurídico de que área de atuação não é título de especialista.
- [x] **Identificação profissional:** nome + OAB/SP no hero da Home, no bloco Corpo Jurídico, na página `/advogados` e no rodapé de todas as páginas.
- [x] **Disclaimers:** Provimento 205/2021 no rodapé; natureza educativa em `/artigos` (Art. 4º); "não estabelece relação advogado-cliente" no formulário e no painel das áreas.
- [x] **Contraste:** só combinações aprovadas na auditoria WCAG do WDLC 3.1.

## 5. Logos e fotografias

Origem: pasta `SITE-Conteudo de marca/SITE` enviada pelos sócios.

| Arquivo | Origem | Tratamento |
| --- | --- | --- |
| `assets/logo-wordmark.svg` | `Souza Guedes.pdf`, pág. 1 | Vetor extraído, sem fundo turquesa, cor via `currentColor` (tinta no header, papel no rodapé). "Advogados" recebeu contorno de reforço, porque a fonte original é fina demais abaixo de ~300px de largura. Tamanhos: 136–156px no header, 150px no rodapé. |
| `assets/logo-monograma.svg` | `Souza Guedes.pdf`, pág. 2 | Idem; usado em turquesa no rodapé (6,12:1 sobre tinta). |
| `assets/rodrigo.webp` | Retrato individual enviado em 21/10/2026 (substitui o recorte da foto em dupla) | Fundo removido (inclusive restos de céu no cabelo), rosto clareado até o nível do retrato da Lays sem aumentar a saturação da pele, altas luzes do sol comprimidas, azul do terno suavizado; enquadrado na mesma escala de cabeça do retrato da Lays; 800×1000, WebP com transparência. |
| `assets/lays.webp` | `IMG_7795.JPG` | Mesmo tratamento e mesmo enquadramento (centralizado pela cabeça, mesma escala). |
| `assets/socios-palacio.jpg` | `IMG_7815.JPG` | Fundo mantido, recorte 4:5, 1000×1250. |

**Por que remover o fundo dos retratos:** as fotos individuais têm fundos muito diferentes (cerca viva, chafariz, jardim do Museu do Ipiranga). Recortados, os dois ficam com o mesmo enquadramento sobre o painel petróleo, à frente da moldura da marca. A foto da dupla no palácio foi mantida com fundo na página `/sobre`, sem legenda que sugira ser a sede do escritório.

### Fotos dos artigos (banco de imagens)

Fotos do Unsplash, sob a [Licença Unsplash](https://unsplash.com/license) (uso comercial livre, sem atribuição obrigatória; o crédito fica registrado aqui). Critério: meio-termo entre objeto isolado e cena carregada — um assunto claro com algum contexto (mãos, mesa, fachada), luz natural, sem rostos em destaque e sem ostentação. Tratamento: recorte 3:2, 900×600, saturação −18% e leve aquecimento para conversar com o papel.

| Arquivo | Artigo | Foto original |
| --- | --- | --- |
| `assets/artigos/usucapiao.jpg` | Usucapião extrajudicial | [Kauê Martins Bergamasco](https://unsplash.com/photos/6UKHiOMdurs) |
| `assets/artigos/medicamento.jpg` | Negativa de cobertura de medicamento | [Laurynas Me](https://unsplash.com/photos/1TL8AoEDj_c) |
| `assets/artigos/negativacao.jpg` | Negativação indevida | [Kelly Sikkema](https://unsplash.com/photos/Nv-87ynmdHA) |
| `assets/artigos/inventario.jpg` | Inventário em cartório | [Cytonn Photography](https://unsplash.com/photos/GJao3ZTX9gU) |
| `assets/artigos/arrematacao.jpg` | Arrematação de imóveis | [Jakub Żerdzicki](https://unsplash.com/photos/bqUZEAeWuok) |
| `assets/artigos/reajuste.jpg` | Reajuste por faixa etária | [Roberto Sorin](https://unsplash.com/photos/RS0-h_pyByk) |

## 6. Pendências para homologação

| Item | Responsável | Observação |
| --- | --- | --- |
| Copy final, artigos e política de privacidade | Fase 4 | Textos atuais são de diagramação, marcados como provisórios. |
| Encarregado de dados (DPO) e e-mail dedicado | Sócios / Fase 4 | Hoje aponta para o e-mail institucional. |
| Prazo de resposta do formulário ("até 2 dias úteis") | Sócios | Confirmar ou remover. |
| Rota `/artigos/{slug}` (página do artigo) | Sócios / Fase 2 | Não existia no sitemap do WDLC 2.2. Foi proposta para que o clique num artigo leve direto ao texto, sem passar pela lista. Precisa entrar no `sitemap-taxonomy.json` e no roteador se aprovada. |
| **Aprovação formal dos mockups** | Sócios | Critério de aceite da issue #9. |
