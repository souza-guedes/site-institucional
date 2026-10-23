"""Gera wdlc-4.1-textos-institucionais.md a partir de data/firm-profile.json e data/articles.json.

Uso (na raiz do projeto):  python docs/fase-4-conteudo/gerar-documento.py
"""
import json
from pathlib import Path

fp = json.loads(Path('data/firm-profile.json').read_text(encoding='utf-8'))
ar = json.loads(Path('data/articles.json').read_text(encoding='utf-8'))
areas = {a['slug']: a for a in fp['practice_areas']}
oab = {p['name']: p['oab_number'] for p in fp['partners']}

L = []
w = L.append

w('''# WDLC 4.1 — Textos institucionais: trajetória, perfis, áreas e artigos

> **Status:** Rascunho para revisão e aprovação dos sócios
> **Fase:** 4 — Produção de conteúdo e copy jurídica
> **Sub-issue:** [#11](https://github.com/souza-guedes/site-institucional/issues/11)
> **Épico Relacionado:** [#10](https://github.com/souza-guedes/site-institucional/issues/10)
> **Dados:** [`data/firm-profile.json`](../../data/firm-profile.json) (trajetória, perfis e áreas) e [`data/articles.json`](../../data/articles.json) (artigos)

---

## 1. Como revisar

Este documento reúne, em leitura corrida, os textos gravados nos arquivos de dados. **A fonte de verdade são os arquivos JSON**: correções aprovadas aqui são feitas lá, e este documento é gerado de novo com `python docs/fase-4-conteudo/gerar-documento.py`.

Três regras guiaram a redação:

- **Só fatos confirmados.** Os textos usam apenas o que já estava nos dados do projeto: nomes, inscrições na OAB, formação, missão, visão, valores, sede e ano de fundação. Nada foi inventado. O que falta está listado na seção 6.
- **Conformidade com o Provimento CFOAB nº 205/2021.** Sem promessa de resultado, sem superlativos, sem chamada de captação e sem a palavra "especialista". Os testes automáticos conferem isso (seção 7).
- **Normas conferidas na fonte oficial.** Cada lei, resolução e decisão citada nos artigos foi conferida no texto oficial (seção 5).

**Mudança em relação à issue.** A #11 pedia artigos sobre Direito Tributário, Gestão Trabalhista Preventiva e Governança Contratual. Nenhum desses temas é área de atuação da banca, e publicar sobre eles sugeriria uma atuação que o escritório não tem. Os três artigos tratam das áreas reais: Imobiliário, Saúde e Família e Sucessões. A área de Consumidor fica para uma próxima leva.

## 2. Trajetória da banca
''')
for p in fp['firm']['history']:
    w(p + '\n')

w('## 3. Perfis dos sócios\n')
for p in fp['partners']:
    w(f"### {p['name']} · {p['oab_number']}\n")
    for para in p['bio_long']:
        w(para + '\n')
    w('**Formação**\n')
    for e in p['education']:
        w(f"- {e['level']}: {e['course']} — {e['institution']}")
    w('')
    w('**Publicações:** nenhuma informada até agora.\n')

w('## 4. Áreas de atuação\n')
for a in fp['practice_areas']:
    w(f"### {a['title']}\n")
    for para in a['description']:
        w(para + '\n')

w('## 5. Artigos inaugurais\n')
w('Os três estão com `status: "rascunho"` e sem data de publicação. A autoria é uma proposta: cada artigo só '
  'é publicado depois que o sócio indicado revisar o texto e assumir a autoria.\n')
w('| Artigo | Área | Autoria proposta | Palavras | Leitura |')
w('| --- | --- | --- | --- | --- |')
for i, a in enumerate(ar['articles'], 1):
    w(f"| {i}. {a['title']} | {areas[a['area_slug']]['title']} | {a['author']} | {a['word_count']} | "
      f"{a['reading_time_minutes']} min |")
w('')
w('Os artigos têm entre 490 e 640 palavras, abaixo das 800 a 1.200 previstas no plano. Eles não foram alongados '
  'com afirmações que não pudessem ser conferidas na fonte oficial. Se os sócios quiserem textos maiores, o '
  'caminho é acrescentar a experiência prática deles em cada tema.\n')

for i, a in enumerate(ar['articles'], 1):
    w(f"### 5.{i} {a['title']}\n")
    w(f"*{areas[a['area_slug']]['title']} · Por {a['author']}, {oab[a['author']]} · "
      f"{a['reading_time_minutes']} min de leitura*\n")
    w(f"> {a['excerpt']}\n")
    for s in a['sections']:
        w(f"#### {s['heading']}\n")
        for para in s['paragraphs']:
            w(para + '\n')
        for item in s['list']:
            w(f'- {item}')
        if s['list']:
            w('')
        for para in s['closing_paragraphs']:
            w(para + '\n')
    w('**Referências**\n')
    for r in a['references']:
        w(f'- {r}')
    w('')
    w(f"*{ar['educational_notice']}*\n")

w('''### Conferência das normas citadas

| Norma | Dispositivo | Onde foi conferida |
| --- | --- | --- |
| Código Civil (Lei nº 10.406/2002) | arts. 1.238, 1.240 e 1.242 | [Planalto](https://www.planalto.gov.br/ccivil_03/leis/2002/l10406compilada.htm) |
| Lei de Registros Públicos (Lei nº 6.015/1973) | art. 216-A, §§ 2º a 15 | [Planalto](https://www.planalto.gov.br/ccivil_03/leis/l6015compilada.htm) |
| Provimento CNJ nº 149/2023 | regras da usucapião extrajudicial | Fontes secundárias. O artigo cita o provimento sem números de artigo, porque eles não foram conferidos no texto oficial. |
| Lei dos Planos de Saúde (Lei nº 9.656/1998) | art. 10, §§ 12 e 13 | [Lei nº 14.454/2022 no Planalto](https://www.planalto.gov.br/ccivil_03/_ato2019-2022/2022/lei/l14454.htm) |
| STF, ADI 7265 | critérios para cobertura fora do rol (18/9/2025) | [Notícia oficial do STF](https://noticias.stf.jus.br/postsnoticias/stf-fixa-criterios-para-que-planos-de-saude-cubram-tratamentos-fora-da-lista-da-ans/) |
| Resolução Normativa ANS nº 623/2024 | comunicação da negativa e reanálise (art. 16) | [FAQ oficial da ANS sobre a RN 623](https://www.gov.br/ans/pt-br/arquivos/assuntos/espaco-da-operadora-de-plano-de-saude/atendimento-ao-beneficiario/FAQ_RN_623__17.09.25.pdf/@@download/file). O artigo não cita prazos de resposta, porque eles não foram confirmados no texto da norma. |
| Código de Processo Civil (Lei nº 13.105/2015) | arts. 300, 610, 611 e 733 | [Planalto](https://www.planalto.gov.br/ccivil_03/_ato2015-2018/2015/lei/l13105.htm) |
| Resolução CNJ nº 35/2007 | arts. 8º, 12-A, 12-B e 22, com redação da Resolução CNJ nº 571/2024 | [Portal de atos do CNJ](https://atos.cnj.jus.br/atos/detalhar/179) |

Conferência feita em 22/10/2026. Antes de publicar, vale confirmar que não houve alteração posterior nessas normas.

## 6. Pendências para os sócios
''')
w('**Trajetória**\n')
for x in fp['firm']['pending']:
    w(f'- [ ] {x}')
w('')
for p in fp['partners']:
    w(f"**{p['name']}**\n")
    for x in p['pending']:
        w(f'- [ ] {x}')
    w('')
w('**Artigos**\n')
for a in ar['articles']:
    w(f"- [ ] {a['title']}: revisão técnica e aprovação do texto e da autoria por {a['author']}.")
w('')

w('''## 7. Auditoria automática

`php tests/run_tests.php` roda dois testes sobre estes textos:

- **`ContentComplianceTest`** passa todo texto publicável de `firm-profile.json` e `articles.json` pelo `EthicalContentValidator`. Ele usa as 34 expressões vedadas de `data/ethical-compliance-rules.json`, os termos vedados de cada área em `data/sitemap-taxonomy.json` e a palavra "especialista" e suas variações. O teste também confere que:
  - as minibios citam o nome completo e a inscrição na OAB;
  - o autor de cada artigo é sócio cadastrado, com OAB válida;
  - cada artigo pertence a uma área de atuação da banca;
  - nenhum artigo aprovado tem pendências.
- **`ArticlesSchemaTest`** confere a estrutura de `articles.json` contra `schemas/articles.schema.json`.

Resultado atual: 80 testes, todos passando. Num teste negativo feito à parte, inserir "resultado garantido" num artigo e trocar a área de outro para "direito-tributario" fez a suíte falhar nos dois pontos, como esperado.

A revisão ortográfica foi feita na leitura, sem enviar os textos a serviços externos.

## 8. Aprovação

| Sócio | Trajetória | Própria minibio | Áreas | Artigos de sua autoria | Data |
| --- | --- | --- | --- | --- | --- |
| Lays Regina de Souza | [ ] | [ ] | [ ] | [ ] 5.1 · [ ] 5.3 | |
| Rodrigo Guedes da Silva | [ ] | [ ] | [ ] | [ ] 5.2 | |

Com a aprovação, o status de cada artigo passa para `"aprovado"`, `published_at` recebe a data de publicação e as pendências resolvidas são removidas dos dados.
''')

out = Path('docs/fase-4-conteudo/wdlc-4.1-textos-institucionais.md')
out.parent.mkdir(parents=True, exist_ok=True)
out.write_text('\n'.join(L), encoding='utf-8')
print(out, len('\n'.join(L)))
