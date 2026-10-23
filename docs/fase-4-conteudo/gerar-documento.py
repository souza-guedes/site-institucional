"""Gera os documentos de revisão da Fase 4 a partir dos dados:
- wdlc-4.1-textos-institucionais.md, de data/firm-profile.json e data/articles.json;
- wdlc-4.2-privacidade-termos-cookies.md, de data/privacy-policy.json.

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


# ---------------------------------------------------------------- WDLC 4.2
pp = json.loads(Path('data/privacy-policy.json').read_text(encoding='utf-8'))
dpo = pp['data_protection_officer']
L = []
w = L.append


def write_sections(sections):
    for s in sections:
        w(f"#### {s['heading']}\n")
        for para in s['paragraphs']:
            w(para + '\n')
        if 'table' in s:
            w('| ' + ' | '.join(s['table']['columns']) + ' |')
            w('|' + ' --- |' * len(s['table']['columns']))
            for row in s['table']['rows']:
                w('| ' + ' | '.join(row) + ' |')
            w('')
        for item in s['list']:
            w(f'- {item}')
        if s['list']:
            w('')
        for para in s['closing_paragraphs']:
            w(para + '\n')


w(f'''# WDLC 4.2 — Política de Privacidade, Termos de Uso e cookies (LGPD)

> **Status:** Rascunho para revisão e aprovação dos sócios
> **Fase:** 4 — Produção de conteúdo e copy jurídica
> **Sub-issue:** [#12](https://github.com/souza-guedes/site-institucional/issues/12)
> **Épico Relacionado:** [#10](https://github.com/souza-guedes/site-institucional/issues/10)
> **Dados:** [`data/privacy-policy.json`](../../data/privacy-policy.json) · **Rota:** `{pp['route']}` ("{pp['page_title']}")

---

## 1. Como revisar

Este documento reúne os textos da rota `{pp['route']}`, gravados em `data/privacy-policy.json`. **A fonte de verdade é o arquivo JSON**: correções aprovadas aqui são feitas lá, e este documento é gerado de novo com `python docs/fase-4-conteudo/gerar-documento.py`.

A redação usa só fatos do projeto e as decisões tomadas para esta issue. Cada artigo de lei citado foi conferido no texto oficial (seção 6). Como são documentos jurídicos do próprio escritório, **a revisão final é dos sócios**.

**Decisões adotadas**

| Tema | Decisão |
| --- | --- |
| Encarregada de dados (art. 41 da LGPD) | {dpo['name']}, {dpo['oab_number']}, pelo e-mail {dpo['email']} com o assunto "{dpo['email_subject']}" |
| Cookies | Só os estritamente necessários; aviso informativo, sem pedido de consentimento |
| Retenção de mensagens | {pp['retention']['contact_without_engagement_months']} meses para contatos que não viram contratação; registros de acesso por {pp['retention']['access_logs_months']} meses |
| Termos de Uso | Seção da própria página `{pp['route']}`, sem rota nova |
| Base legal do formulário | Procedimentos preliminares relacionados a contrato, a pedido do titular (art. 7º, V), e não o consentimento |

**Itens da issue**

| Item da #12 | Onde está |
| --- | --- |
| Finalidade da coleta no formulário (nome, e-mail, telefone) | Política de Privacidade, seções 2 e 3 |
| Hipóteses legais do art. 7º | Política de Privacidade, seção 3 (tabela) |
| Direitos dos titulares (art. 18) | Política de Privacidade, seções 9 e 10 |
| Identificação e e-mail do encarregado | Política de Privacidade, seções 1 e 10 |
| Termos de Uso e política de retenção de mensagens | Termos de Uso; Política de Privacidade, seção 6 |
| Texto do banner de cookies essenciais e analíticos | Seção 5: texto em uso (essenciais) e variante futura com consentimento (analíticos) |

## 2. {pp['privacy_policy']['title']}
''')
write_sections(pp['privacy_policy']['sections'])

w(f"## 3. {pp['cookie_policy']['title']}\n")
write_sections(pp['cookie_policy']['sections'])

w(f"## 4. {pp['terms_of_use']['title']}\n")
write_sections(pp['terms_of_use']['sections'])

banner = pp['cookie_banner']
variant = banner['future_consent_variant']
w(f'''## 5. Banner de cookies

**Texto em uso** (modo {banner['mode']}):

> {banner['text']}
>
> [{banner['link_label']}]({banner['link_href']}) · botão **{banner['button_label']}**

O aviso é uma faixa no pé da página, que não bloqueia a navegação (componente `.cookie` do catálogo da WDLC 3.3).

**Variante futura, com consentimento** (não publicada):

> {variant['text']}
>
> Botões: {' · '.join(f"**{b}**" for b in variant['buttons'])}

{variant['note']}

## 6. Conferência das normas citadas

| Norma | Dispositivos | Onde foi conferida |
| --- | --- | --- |
| LGPD (Lei nº 13.709/2018) | arts. 7º, 11, 16, 18, 19, 41 e 48 | [Planalto](https://www.planalto.gov.br/ccivil_03/_ato2015-2018/2018/lei/l13709compilado.htm) |
| Resolução CD/ANPD nº 2/2022 | art. 11 (encarregado de agente de pequeno porte) | [ANPD](https://www.gov.br/anpd/pt-br/acesso-a-informacao/institucional/atos-normativos/regulamentacoes_anpd/resolucao-cd-anpd-no-2-de-27-de-janeiro-de-2022) |
| Marco Civil da Internet (Lei nº 12.965/2014) | art. 15 | [Planalto](https://www.planalto.gov.br/ccivil_03/_ato2011-2014/2014/lei/l12965.htm). O artigo obriga provedores de aplicação com fins econômicos; a política usa o mesmo prazo de 6 meses como referência, sem afirmar que o escritório está obrigado a ele. |
| Estatuto da Advocacia (Lei nº 8.906/1994) | art. 7º, II | [Planalto](https://www.planalto.gov.br/ccivil_03/leis/l8906.htm) |
| Código de Ética e Disciplina da OAB (Res. CFOAB nº 02/2015) | arts. 35 e 36 | [Texto publicado pela OAB/RN](https://oabrn.org.br/storage/gLIXPjEOV7kEvpTBOxDlW2KYJrWJClKTGG4w3AwC.pdf) |
| Provimento CFOAB nº 205/2021 | finalidade informativa do site | Matriz de conformidade da WDLC 1.2 |

Conferência feita em 23/10/2026. Antes de publicar, vale confirmar que não houve alteração posterior nessas normas.

## 7. Pendências
''')
for x in pp['pending']:
    w(f'- [ ] {x}')
w('')

w('''## 8. Auditoria automática

`php tests/run_tests.php` roda dois testes sobre estes textos:

- **`PrivacyPolicyComplianceTest`** confere que:
  - as bases legais citadas são hipóteses do art. 7º, e toda finalidade tem uma;
  - os nove direitos do art. 18 estão na lista;
  - a encarregada é sócia cadastrada, com OAB válida e o e-mail institucional, e aparece no texto (art. 41, § 1º);
  - os prazos de retenção estão nos textos;
  - o aviso informativo só é usado com cookies estritamente necessários;
  - os Termos dizem que o site não presta consulta jurídica e que o contato não forma relação advogado-cliente;
  - não há expressão vedada pelo Provimento 205/2021.
- **`PrivacyPolicySchemaTest`** confere a estrutura de `data/privacy-policy.json`.

Num teste negativo feito à parte, três alterações fizeram a suíte falhar nos três pontos, como esperado: retirar a portabilidade da lista de direitos, trocar a OAB da encarregada e declarar cookies analíticos com o aviso informativo.

## 9. Aprovação

| Sócio | Política de Privacidade | Política de Cookies | Termos de Uso | Banner | Data |
| --- | --- | --- | --- | --- | --- |
| Lays Regina de Souza (encarregada) | [ ] | [ ] | [ ] | [ ] | |
| Rodrigo Guedes da Silva | [ ] | [ ] | [ ] | [ ] | |

Com a aprovação, `status` passa para `"aprovado"` e as pendências resolvidas são removidas dos dados. O teste `testApprovedVersionHasNoPlaceholders` impede aprovar com marcadores `[A DEFINIR]` restantes.
''')

out = Path('docs/fase-4-conteudo/wdlc-4.2-privacidade-termos-cookies.md')
out.write_text('\n'.join(L), encoding='utf-8')
print(out, len('\n'.join(L)))
