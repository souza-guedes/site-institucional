# WDLC 1.2 — Matriz de Conformidade Ética com o Provimento OAB nº 205/2021

> **Status:** Aprovado e Vigente (Retificado em conformidade fidedigna com o Provimento CFOAB nº 205/2021)  
> **Fase:** 1 — Levantamento de Requisitos, Identidade Institucional e Compliance OAB  
> **Sub-issue:** [#3](https://github.com/souza-guedes/site-institucional/issues/3)  
> **Épico Relacionado:** [#1](https://github.com/souza-guedes/site-institucional/issues/1)  
> **Dataset Oficial:** [`data/ethical-compliance-rules.json`](../../data/ethical-compliance-rules.json) validado por [`schemas/ethical-compliance.schema.json`](../../schemas/ethical-compliance.schema.json)  
> **Validador Automatizado:** [`src/Domain/Compliance/EthicalContentValidator.php`](../../src/Domain/Compliance/EthicalContentValidator.php)  
> **Workflow de CI:** [`.github/workflows/ci.yml`](../../.github/workflows/ci.yml)  
> **Documento no Notion:** [Souza Guedes Advogados | Matriz de Conformidade Ética OAB (WDLC 1.2)](https://app.notion.com/p/Souza-Guedes-Advogados-Matriz-de-Conformidade-tica-OAB-WDLC-1-2-3f2ea742df39816798e8c7cb4efcaaa4)

---

## 1. Visão Geral e Fundamentação Regulatória

A presença digital e a arquitetura de informação do portal institucional do escritório **Souza Guedes Advogados** obedecem estritamente às normas regulatórias da Ordem dos Advogados do Brasil, estruturadas sob a seguinte hierarquia normativa:

1. **Estatuto da Advocacia e da OAB** (Lei Federal nº 8.906/1994, arts. 31 a 34);
2. **Código de Ética e Disciplina da OAB - CED** (Resolução CFOAB nº 02/2015, arts. 39 a 47-A);
3. **Provimento CFOAB nº 205/2021** (Marco regulatório da publicidade e marketing jurídico na era digital);
4. **Jurisprudência dos Tribunais de Ética e Disciplina (TED/OAB)**, em especial da OAB/SP.

O princípio basilar que rege toda a comunicação da banca é a **publicidade meramente informativa e educativa**, primando pela **discrição, moderação e sobriedade**, repelindo qualquer mercantilização da advocacia ou promessa de resultado.

---

## 2. Auditoria Sistemática: Artigos 1º a 6º e Anexo Único do Provimento CFOAB nº 205/2021

Abaixo sintetiza-se a interpretação técnica e a aplicação prática de cada artigo do Provimento no desenvolvimento do site institucional:

| Dispositivo Legal | Conteúdo Normativo Real | Aplicação Direta no Portal Souza Guedes | Severidade |
| --- | --- | --- | --- |
| **Art. 1º, caput e § 1º** | Permite o marketing jurídico desde que exercido de forma compatível com os preceitos éticos. O § 1º exige informações objetivas e verdadeiras e estabelece a exclusiva responsabilidade das pessoas físicas e jurídicas identificadas. | Todo conteúdo institucional e informativo veiculado no portal é estritamente técnico, objetivo e verídico, sendo atribuído aos advogados responsáveis. | **CRÍTICA** |
| **Art. 2º, incisos VI, VII e VIII** | Define os conceitos normativos:<br>• **VI - Publicidade ativa:** Divulgação que atinge público indeterminado sem busca prévia;<br>• **VII - Publicidade passiva:** Divulgação que atinge apenas quem buscou a informação ou consentiu previamente;<br>• **VIII - Captação de clientela:** Mecanismos de persuasão/aliciamento com o objetivo de atrair clientes, inclusive mediante **estímulo ao litígio**. | A arquitetura do website adota integralmente o modelo passivo: navegação orgânica, sem pop-ups invasivos de captura forçada ou scripts intrusivos de insistência comercial. Proibição de gatilhos de estímulo à litigiosidade predatória. | **CRÍTICA** |
| **Art. 3º, caput, incisos I a V e §§ 1º e 2º** | **Vedações expressas em qualquer publicidade:**<br>• *Inc. I:* Menção direta ou indireta a valores de honorários, forma de pagamento, gratuidade ou descontos;<br>• *Inc. II:* Publicidade conjunta com outra atividade não jurídica;<br>• *Inc. III:* Anúncio de especialidade jurídica sem título reconhecido ou notória especialização;<br>• *Inc. IV:* Expressões persuasivas, autoengrandecimento ou comparação;<br>• *§ 1º:* Incitação direta ou indireta ao litígio;<br>• *§ 2º:* Divulgação de lista de clientes ou demandas. | Vedações bloqueadas no código (`EthicalContentValidator`) e nos manuais: proibição de tabelas de preços, consultas grátis, adjetivos de autoengrandecimento ("o melhor", "líder"), incitação a processos ("processe sua empresa agora") e exibição de clientes. | **CRÍTICA** |
| **Art. 4º, caput e §§ 1º a 5º** | Autoriza publicidade passiva ou ativa no marketing de conteúdos e a contratação de anúncios patrocinados na internet e redes sociais, desde que observados os limites éticos e os meios de veiculação não vedados pelo art. 40 do CED. | Produção de artigos informativos e postagens explicativas sobre direitos. Não utilização de meios de veiculação vetados pelo CED (ex.: malas-diretas indiscriminadas, outdoors ou veículos de som). | **ALTA** |
| **Art. 5º, caput, §§ 1º e 2º e Anexo Único** | Exige critérios de sobriedade e moderação em plataformas digitais. Veda pagamento para constar em rankings comerciais de "melhores advogados" (§ 1º) e símbolos oficiais da OAB ou da República (§ 2º).<br>• *Anexo Único:* Regula **WhatsApp** (comunicação restrita a clientes e contatos que autorizaram) e **Chatbot** (admitido para primeiro contato/triagem passiva, vedado substituir o aconselhamento do advogado). | O botão de WhatsApp opera como canal passivo sob demanda do usuário com mensagem de apresentação cordial. O site não adota chatbots autônomos que emitam consultoria jurídica sem supervisão do advogado. | **ALTA** |
| **Art. 6º, caput, parágrafo único e Anexo Único** | Na publicidade ativa, veda menção a dimensões, estrutura física do escritório, promessas de resultado e casos concretos. O **parágrafo único veda expressamente a ostentação de bens patrimoniais** em qualquer publicidade.<br>• *Anexo Único:* Compra de palavras-chave no **Google Ads** é permitida quando responde a busca ativa do interessado, sem usar nomes de concorrentes nem termos superlativos. | O portal não faz promessas de resultado processual ("resultado garantido", "causa ganha"), não exibe fotos de ostentação (veículos de luxo, bens materiais) e veda termos comparativos em anúncios de busca. | **CRÍTICA** |

---

## 3. Diretriz Específica da Banca Souza Guedes: Artigo 3º, III (Especialidades sem Título Certificado)

O **Art. 3º, inciso III do Provimento CFOAB nº 205/2021** veda expressamente:
> *"anúncio ou divulgação de especialidades jurídicas para as quais o advogado não possua título de especialização reconhecido ou notória especialização;"*

### Diagnóstico no Contexto do Escritório Souza Guedes Advogados:
O perfil canônico do escritório ([`data/firm-profile.json`](../../data/firm-profile.json)) estabelece 4 áreas nucleares de prática jurídica:
1. **Direito Imobiliário**
2. **Direito de Saúde**
3. **Direito do Consumidor**
4. **Direito de Família e Sucessões**

Nas biografias dos sócios fundadores:
- O sócio **Rodrigo Guedes da Silva (OAB/SP nº 538.416)** possui graduação em Direito pela USJT e extensão em Falências e Recuperações Judiciais pela ESA/OAB;
- A sócia **Lays Regina de Souza (OAB/SP nº 511.204)** possui graduação em Direito pela USJT e pós-graduação lato sensu em *Direito Imobiliário Notarial e Registral* pela PUC.

### Regra Obrigatória para Conteúdo e UI:
- **Nomenclatura Permitida:** O portal, cards de especialidade e menus devem utilizar exclusivamente o termo institucional **"Áreas de Atuação"** ou a expressão descritiva **"Atuação em Direito Imobiliário, Saúde, Consumidor, Família e Sucessões"**.
- **Conduta Vedada:** É terminantemente proibido redigir chamadas que autointitulem o escritório ou os profissionais como *"Especialistas em Direito de Saúde"*, *"Especialistas em Direito do Consumidor"* ou *"Especialistas em Direito de Família"*, uma vez que não há título de pós-graduação formal registrado nessas matérias específicas.
- **Ressalva Legítima:** A sócia Lays Regina de Souza pode referenciar formalmente sua especialização acadêmica em Direito Imobiliário Notarial e Registral em sua biografia profissional, por se tratar de título de pós-graduação certificado e regular.

---

## 4. Matriz de Expressões Proibidas (Blacklist Ética Reenquadrada)

O validador automatizado [`EthicalContentValidator`](../../src/Domain/Compliance/EthicalContentValidator.php) utiliza correspondência estrita por limites de caracteres alfabéticos Unicode (`/(?<!\p{L})TERMO(?!\p{L})/iu`), assegurando que termos neutros e legítimos (como *"liderança acadêmica"* ou *"melhorar processos"*) não gerem falsos positivos.

| Expressão / Padrão | Categoria | Severidade | Dispositivo Legal de Referência | Motivo Regulatório Real |
| --- | --- | --- | --- | --- |
| `"o melhor"` / `"os melhores"` | `superlativo` | CRÍTICA | **Art. 3º, IV** | Vedação a expressões persuasivas, de autoengrandecimento ou de comparação. |
| `"líder"` / `"líderes"` | `superlativo` | CRÍTICA | **Art. 3º, IV** | Expressão de autoengrandecimento mercantilista e concorrência comercial indevida. |
| `"o mais experiente"` / `"imbatível"` | `superlativo` | CRÍTICA / ALTA | **Art. 3º, IV** | Publicidade comparativa e autoelogio vedados pelo Código de Ética e Provimento 205/2021. |
| `"tabela de preços"` / `"preço popular"` | `preco_honorarios` | CRÍTICA | **Art. 3º, I** | Proibição de menção direta ou indireta a valores de honorários ou tabelas públicas. |
| `"honorários promocionais"` / `"desconto exclusivo"` | `preco_honorarios` | CRÍTICA | **Art. 3º, I** | Mercantilização e aviltamento da profissão mediante concessão pública de vantagens comerciais. |
| `"consulta grátis"` / `"consulte grátis"` | `mercantilizacao` | CRÍTICA | **Art. 3º, I c/c Anexo Único** | Oferta de gratuidade indiscriminada como mecanismo de captação indevida de causas. |
| `"especialista em saúde"` / `"especialista em consumidor"` | `especialidade_sem_titulo` | CRÍTICA | **Art. 3º, III** | Vedação ao anúncio de especialidade jurídica sem certificação acadêmica reconhecida. |
| `"resultado garantido"` / `"causa ganha"` | `promessa_resultado` | CRÍTICA | **Art. 6º, caput c/c Art. 3º, I** | A advocacia é atividade de meio. Vedada qualquer garantia ou promessa de êxito processual. |
| `"sem risco"` / `"risco zero"` | `promessa_resultado` | CRÍTICA | **Art. 6º, caput** | Violação ao dever de prestar informação objetiva e verdadeira, induzindo o cliente a erro. |
| `"processe sua empresa agora"` / `"processe o banco"` | `captacao_litigio` | CRÍTICA / ALTA | **Art. 2º, VIII c/c Art. 3º, § 1º** | Captação indevida e incitação explícita ao litígio e conflitos judiciais artificiais. |
| Ostentação de carros, relógios, bens e patrimônio | `ostentacao` | CRÍTICA | **Art. 6º, parágrafo único** | Vedação expressa à ostentação de bens patrimoniais em qualquer meio de publicidade da advocacia. |

---

## 5. Checklist de Compliance Operacional

### 5.1 Para Redatores e Conteudistas (Copywriting Jurídico)
- [ ] **Tom de Voz Moderado:** O texto mantém tom sereno, técnico, pedagógico e acolhedor (Art. 1º e Art. 3º, caput)?
- [ ] **Sobriedade Semântica:** O conteúdo foi verificado pelo validador automático, livre de termos superlativos, promessas de resultado ou referências a preços (Art. 3º, I e IV)?
- [ ] **Enquadramento de Especialidades (Art. 3º, III):** As disciplinas são identificadas como *"Áreas de Atuação"*, sem autointitulação indevida de *"especialista"* sem diploma registrado?
- [ ] **Caráter Informativo:** O artigo explica a lei e a jurisprudência sem incentivar ações judiciais predatórias (Art. 2º, VIII e Art. 3º, § 1º)?
- [ ] **Chamadas para Ação (CTAs) Moderadas:**
  - *Permitido:* "Conheça nossas áreas de atuação", "Entre em contato com nossa equipe", "Agende um atendimento".
  - *Proibido:* "Contrate agora os melhores advogados", "Processe já sua operadora", "Garantia de ressarcimento".
- [ ] **Sigilo:** Nomes de clientes ou números de processos sigilosos foram totalmente omitidos ou anonimizados (Art. 3º, § 2º).

### 5.2 Para Designers e Desenvolvedores (UI/UX e Arquitetura)
- [ ] **Identificação Completa e Conforme (Art. 1º, § 1º, Art. 3º e Anexo Único):** Em rodapés, cabeçalhos de bio e seções institucionais constam com exatidão:
  - *Rodrigo Guedes da Silva — OAB/SP nº 538.416*
  - *Lays Regina de Souza — OAB/SP nº 511.204*
- [ ] **Disclaimer Ético no Rodapé:** Aviso regulamentar visível:
  > *"Este portal tem finalidade exclusivamente institucional, informativa e educativa, em rigorosa conformidade com o Provimento CFOAB nº 205/2021 e o Código de Ética e Disciplina da OAB. As informações divulgadas não configuram captação de clientela, promessa de resultados ou prestação de consulta jurídica direta, a qual depende de contratação formal e análise individualizada de cada caso."*
- [ ] **Arquitetura Passiva Estrita (Art. 2º, VII):** Ausência de pop-ups intrusivos de insistência comercial, contadores regressivos ou gatilhos predatórios de escassez.
- [ ] **Canais de Atendimento Passivo (Art. 5º e Anexo Único):** Botão de WhatsApp direcionando para conversa com mensagem institucional de apresentação, sem disparo automático e sem resposta de consultoria por chatbot sem intervenção humana.
- [ ] **Pipeline de CI/CD Integrado:** Execução automatizada e determinística da suíte de testes (`php tests/run_tests.php`) com 100% de aprovação.

---

## 6. Termo Interno de Validação Jurídica Pré-Publicação

Nenhuma página, seção ou conteúdo do portal pode ser veiculado sem o cumprimento dos 5 itens obrigatórios do protocolo de governança:

### Modelo Formal de Sign-off
```
========================================================================
TERMO INTERNO DE VALIDAÇÃO JURÍDICA PRÉ-PUBLICAÇÃO — SOUZA GUEDES ADVOGADOS
========================================================================
Identificador da Peça / Conteúdo: [ex: page-home-v1]
Título do Conteúdo: [ex: Página Inicial Institucional]
Data da Homologação: [AAAA-MM-DD HH:MM:SS]
Sócio Fundador Autorizado:
( ) Rodrigo Guedes da Silva (OAB/SP nº 538.416)
( ) Lays Regina de Souza (OAB/SP nº 511.204)

CHECKLIST MANDATÓRIO DE CONFORMIDADE ÉTICA (5 ITENS):
[X] identificacao_completa_advogados (Nome completo e OAB/SP de ambos os sócios)
[X] ausencia_termos_superlativos_e_mercantis (Sem superlativos, preços ou promoções)
[X] sobriedade_visual_e_botoes_contato (Sem pop-ups intrusivos ou contadores)
[X] carater_informativo_sem_promessa_resultado (Sem promessas ou incitação ao litígio)
[X] canais_atendimento_passivo (WhatsApp e canais passivos sem consulta automatizada)

PARECER DO SÓCIO FUNDADOR:
(X) APROVADO PARA PUBLICAÇÃO
( ) REPROVADO PARA CORREÇÃO

Observações: Homologado em estrita observância aos Arts. 1º a 6º do Provimento CFOAB nº 205/2021.
Assinatura Digital / Sign-off: [Nome do Sócio Fundador]
========================================================================
```

---

## 7. Verificação Técnica, Testes Determinísticos e CI

A integridade deste catálogo regulatório é assegurada continuamente no repositório:
- **Test Runner Local:** `php tests/run_tests.php`
- **Automação Contínua (CI):** [`.github/workflows/ci.yml`](../../.github/workflows/ci.yml) executa a validação em todo `push` e `pull request`.
- **Cobertura de Testes (28 testes unitários - 100% PASS):**
  - Validação da estrutura do schema JSON e do catálogo normativo;
  - Validação estrita por regex cobrindo todos os Artigos de 1º a 6º do Provimento 205/2021;
  - Validação da regra do Art. 3º, III para especialidades sem título formal;
  - Validação de correspondência de texto por limites de palavras Unicode (evitando falso positivo de palavras como "liderança" ou "melhorar");
  - Validação estrita do formato formal de inscrição na OAB (rejeitando strings arbitrárias como "abc 123");
  - Validação do sign-off com reprovação de aprovadores não autorizados e checklists incompletos;
  - Homologação retroativa de conformidade do perfil da banca ([`data/firm-profile.json`](../../data/firm-profile.json)).
