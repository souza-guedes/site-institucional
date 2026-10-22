# Auditoria de contraste (WCAG 2.1 AA)

Fórmula de luminância relativa do WCAG 2.x. Limites: texto normal 4,5:1; texto grande (≥24px, ou ≥18,66px em negrito) 3:1; componentes de interface 3:1.

## Combinações aprovadas

| Texto / elemento | Fundo | Razão | Resultado | Uso |
| --- | --- | --- | --- | --- |
| tinta | papel | 15,38 | AAA | Títulos e corpo |
| tinta | nevoa | 14,52 | AAA | Corpo em cards |
| texto-secundario | papel | 6,91 | AA | Legendas, notas |
| texto-secundario | nevoa | 6,53 | AA | Legendas em cards |
| branco | petroleo | 7,92 | AAA | Texto de botão |
| papel | petroleo | 7,09 | AAA | Texto de botão alternativo |
| papel | petroleo-profundo | 10,99 | AAA | Hero, rodapé |
| texto-claro | petroleo-profundo | 7,52 | AAA | Apoio em seção escura |
| petroleo | papel | 7,09 | AAA | Links, ícones |
| petroleo | nevoa | 6,69 | AA | Links em cards |
| latao | petroleo-profundo | 5,49 | AA | Eyebrow, numerais em seção escura |
| latao | tinta | 7,69 | AAA | Idem, fundo tinta |
| latao-escuro | papel | 5,35 | AA | Eyebrow em fundo claro |
| turquesa | tinta | 6,12 | AA | Texto/ornamento sobre tinta |
| turquesa | petroleo-profundo | 4,37 | só texto grande | Texto ≥24px apenas |
| borda-campo | papel | 3,89 | AA (UI ≥3:1) | Borda de input |
| borda-campo | branco | 4,35 | AA (UI ≥3:1) | Borda de input em formulário branco |
| erro | branco | 7,57 | AAA | Mensagem e borda de campo inválido (WDLC 3.3) |
| erro | papel | 6,78 | AA | Idem, formulário sobre papel |

## Combinações proibidas

| Combinação | Razão | Motivo |
| --- | --- | --- |
| branco sobre turquesa | 2,81 | Reprova mesmo em texto grande |
| turquesa sobre papel | 2,51 | Reprova |
| turquesa sobre petroleo | 2,82 | Reprova |
| latao sobre papel/nevoa | < 3 | Use latao-escuro em fundo claro |
| erro sobre petroleo-profundo / tinta | 1,62 / 2,27 | Erro só em formulário claro |

`linha` é decorativa (não carrega informação), por isso fica fora da exigência de contraste.

## Tailwind

`tailwind.config.js` substitui a paleta padrão pelos tokens acima. Classes disponíveis: `text-tinta`, `bg-petroleo`, `border-linha`, `font-titulo`, `text-h1`, `p-4`, `rounded-sm` etc.

Desde a WDLC 3.3, os breakpoints são `sm` 560px, `md` 760px e `lg` 1100px, e os títulos são mobile-first: `text-h2-mobile md:text-h2` (idem `display`, `h1`, `h3`, `lead`).
