# WDLC 1.1 — Mapeamento da Banca Souza Guedes, Áreas de Atuação e Personas de Clientes

> **Status:** Baseline aceita em 2026-10-06. O dataset segue as respostas do Notion; as lacunas abaixo não bloqueiam o uso.  
> **Fase:** 1 — Levantamento de Requisitos, Identidade Institucional e Compliance OAB  
> **Sub-issue:** [#2](https://github.com/souza-guedes/site-institucional/issues/2)  
> **Fonte:** [Briefing no Notion](https://app.notion.com/p/Souza-Guedes-Advogados-Briefing-Institucional-Especialidades-e-Personas-WDLC-1-1-3ebea742df39811496aec5ee8e5f39ab)  
> **Dataset:** [`data/firm-profile.json`](../../data/firm-profile.json) validado por [`schemas/firm-profile.schema.json`](../../schemas/firm-profile.schema.json)

---

## 1. O que entrou no dataset

Texto institucional, sócios, quatro especialidades e canais foram copiados das respostas dos sócios.

| Bloco | Origem no formulário |
| --- | --- |
| Identidade, missão, visão, três valores e sede | Seção 1 |
| Rodrigo Guedes da Silva (OAB/SP nº 538.416) e Lays Regina de Souza (OAB/SP nº 511.204) | Seção 2 |
| Direito Imobiliário, Direito de Saúde, Direito do Consumidor, Direito de Família e Sucessões | Seção 3 |
| E-mail, telefone, WhatsApp e horário das 10h às 18h | Seção 5 |

As quatro personas do JSON foram compostas somente com o público e as demandas da seção 3. A seção 4 ficou em branco. Em 2026-10-06 ficou decidido seguir com essas personas como baseline.

## 2. Normalizações

- Cidade e UF vieram num único campo (“São Paulo”). O dataset grava cidade `São Paulo` e UF `SP`, coerente com o CEP `03283-000`.
- Inscrições dos sócios foram gravadas como `OAB/SP nº 538.416` e `OAB/SP nº 511.204`.
- O campo de registro da sociedade repetiu essas duas inscrições individuais (`511.204/SP e 538.416/SP`). Não há número de sociedade distinto na resposta.
- Na biografia da sócia, “Pontífica” foi gravado como “Pontifícia”.
- O telefone informado no campo de telefonia fixa é o móvel `(11) 93619-1904`, o mesmo do WhatsApp. O rótulo no dataset é “Telefone comercial da sede”.

## 3. Lacunas que permanecem no dataset

- Quarto valor nuclear: o item 4 da lista ficou em branco. O dataset guarda os três princípios respondidos.
- E-mail publicado é `souzaguedes.adv@gmail.com`. A resposta pede consulta de domínio para um e-mail institucional.
- E-mail direto dos sócios, dias da semana do expediente e observações da seção 6 não foram preenchidos.
- Registro da sociedade na OAB repete as duas inscrições individuais. Não há número de sociedade distinto na resposta.
- A seção 4 de personas continua vazia no Notion. O site usa as quatro personas derivadas da seção 3.

## 4. Critério de aceite da issue #2

Especialidades e personas estão no dataset canônico, com a baseline aceita em 2026-10-06. A issue [#2](https://github.com/souza-guedes/site-institucional/issues/2) segue aberta até o fechamento explícito.
