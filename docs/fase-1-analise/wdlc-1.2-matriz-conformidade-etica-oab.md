# WDLC 1.2 — Matriz de Conformidade Ética com o Provimento OAB nº 205/2021

> **Status:** Aprovado e Vigente (Retificado em estrita conformidade com o Provimento CFOAB nº 205/2021; dispositivos revistos contra o texto oficial em 2026-10-24)  
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

1. **Estatuto da Advocacia e da OAB** (Lei Federal nº 8.906/1994, arts. 31 a 34, e art. 3º-A);
2. **Código de Ética e Disciplina da OAB - CED** (Resolução CFOAB nº 02/2015, arts. 39 a 47-A);
3. **Provimento CFOAB nº 205/2021** (Marco regulatório da publicidade e marketing jurídico na era digital);
4. **Jurisprudência dos Tribunais de Ética e Disciplina (TED/OAB)**, em especial da OAB/SP.

O princípio basilar que rege toda a comunicação da banca é a **publicidade meramente informativa e educativa**, primando pela **discrição, moderação e sobriedade**, repelindo qualquer mercantilização da advocacia ou promessa de resultado.

---

## 2. Auditoria Sistemática: Artigos 1º a 6º, Art. 8º e Anexo Único do Provimento CFOAB nº 205/2021

Abaixo sintetiza-se a interpretação técnica e a aplicação prática de cada artigo do Provimento no desenvolvimento do site institucional. A coluna "Conteúdo Normativo Real" parafraseia o [texto oficial](https://www.oab.org.br/leisnormas/legislacao/provimentos/205-2021), conferido em 2026-10-24; em caso de dúvida, vale o texto oficial.

| Dispositivo Legal | Conteúdo Normativo Real | Aplicação Direta no Portal Souza Guedes | Severidade |
| --- | --- | --- | --- |
| **Art. 1º, caput, §§ 1º e 2º** | Permite o marketing jurídico desde que observados os preceitos éticos do Estatuto, do Regulamento Geral e do CED. O § 1º atribui a responsabilidade pelas informações aos advogados identificados (na sociedade, aos sócios administradores). O § 2º exige a comprovação da veracidade das informações quando a fiscalização solicitar. | Todo conteúdo institucional e informativo veiculado no portal é estritamente técnico, objetivo e verídico, sendo atribuído aos advogados responsáveis identificados. | **CRÍTICA** |
| **Art. 2º, incisos VI, VII e VIII** | Define os conceitos normativos:<br>• **VI - Publicidade ativa:** Divulgação que atinge número indeterminado de pessoas, mesmo sem busca prévia;<br>• **VII - Publicidade passiva:** Divulgação que atinge apenas público certo, que buscou a informação ou consentiu previamente;<br>• **VIII - Captação de clientela:** Uso de mecanismos de marketing para atrair clientes por **indução à contratação** ou **estímulo ao litígio**, independentemente do resultado. | A arquitetura do website adota integralmente o modelo passivo: navegação orgânica, sem pop-ups invasivos de captura forçada ou scripts intrusivos de insistência comercial. Proibição de gatilhos de estímulo à litigiosidade predatória. | **CRÍTICA** |
| **Art. 3º, caput, incisos I a V e § 1º** | **Caput:** publicidade meramente informativa, discreta e sóbria, sem captação de clientela ou mercantilização.<br>**Vedações:**<br>• *Inc. I:* Referência a valores de honorários, forma de pagamento, gratuidade ou descontos **como forma de captação de clientes**;<br>• *Inc. II:* Informações que possam induzir a erro ou causar dano;<br>• *Inc. III:* Anúncio de especialidades para as quais não se possua título certificado ou notória especialização, nos termos do art. 3º-A do Estatuto da Advocacia;<br>• *Inc. IV:* Orações persuasivas, de autoengrandecimento ou de comparação;<br>• *Inc. V:* Distribuição indiscriminada de brindes, cartões, material impresso ou digital e apresentações de serviços em locais públicos, presenciais ou virtuais, salvo em eventos de interesse jurídico;<br>• *§ 1º:* A publicidade sóbria divulga o perfil profissional sem ostentação, **sem incitar diretamente ao litígio** judicial ou administrativo **nem à contratação**, vedada a promoção pessoal (*nota: o § 2º trata de consultores em direito estrangeiro*). | Vedações bloqueadas no código (`EthicalContentValidator`) e nos manuais: proibição de tabelas de preços, consultas grátis, adjetivos de autoengrandecimento ("o melhor", "líder"), incitação a processos ("processe sua empresa agora") e anúncios sem qualificação jurídica formal. O portal aplica o inciso I de forma ampla: nenhuma referência a valores, mesmo sem intenção de captação. | **CRÍTICA** |
| **Art. 4º, caput e §§ 1º a 5º** | **Caput:** admite publicidade ativa ou passiva de conteúdos jurídicos e anúncios, pagos ou não, sem mercantilização, captação de clientela ou emprego excessivo de recursos financeiros, observados o art. 40 do CED e o Anexo Único.<br>• *§ 1º:* Identificação com qualificações e títulos verdadeiros e comprováveis;<br>• *§ 2º:* Em imagem, vídeo ou áudio de atuação profissional, respeito ao sigilo e vedação de mencionar decisões judiciais e resultados obtidos;<br>• *§ 3º:* Sites, redes sociais e aplicativos de mensagem (como o WhatsApp) equiparam-se a e-mail como dados de contato;<br>• *§ 4º:* Publicidade ativa admitida na venda de livros, cursos e eventos para advogados e estudantes;<br>• *§ 5º:* Vedados meios que influam de forma fraudulenta no impulsionamento ou no alcance. | Produção de artigos informativos e postagens explicativas sobre direitos. Formação dos sócios citada só quando verdadeira e comprovável. Não utilização de meios de veiculação vetados pelo CED (ex.: malas-diretas indiscriminadas, outdoors ou veículos de som). Sem menção a decisões ou resultados obtidos. | **ALTA** |
| **Art. 5º, caput e §§ 1º a 3º** | **Caput:** admite anúncios, pagos ou não, nos meios de comunicação não vedados pelo art. 40 do CED.<br>• *§ 1º:* Vedado pagar ou patrocinar aparição em rankings, prêmios ou honrarias que destaquem profissionais;<br>• *§ 2º:* Permitidas logomarca e fotos dos advogados e do escritório, vedado o uso de símbolos oficiais da OAB;<br>• *§ 3º:* Permitida a participação em vídeos, debates e palestras virtuais, vedada a referência a casos concretos e a apresentação de resultados. | O portal não exibe selos de rankings pagos nem o logotipo ou símbolos da OAB. Fotos dos sócios são institucionais. | **ALTA** |
| **Art. 6º, caput e parágrafo único** | Na publicidade ativa, veda informar dimensões, qualidades ou estrutura física do escritório, **prometer resultados** ("resultado garantido", "causa ganha", "sem risco") ou usar casos concretos para oferecer atuação. O **parágrafo único veda, em qualquer publicidade, a ostentação de bens** (veículos, viagens, hospedagens, bens de consumo) e reitera a vedação de promessas de resultado e de casos concretos. | O portal não faz promessas de resultado processual nem exibe fotos de ostentação (veículos de luxo, bens materiais). | **CRÍTICA** |
| **Art. 8º, caput e parágrafo único** | Veda vincular os serviços advocatícios a outras atividades ou divulgá-los em conjunto com elas, salvo o magistério. Em coworking, veda divulgar a advocacia junto com as demais atividades ou empresas do espaço. | O portal divulga apenas a advocacia, sem parceiros comerciais, corretores, imobiliárias ou outras atividades. | **ALTA** |
| **Anexo Único** | Critérios por meio de divulgação:<br>• **Palavra-chave paga (ex.: Google Ads):** permitida quando responde a busca iniciada pelo potencial cliente e com termos eticamente adequados; vedados anúncios ostensivos em plataformas de vídeo;<br>• **Patrocínio e impulsionamento em redes sociais:** permitidos desde que o conteúdo não ofereça serviços jurídicos;<br>• **Chatbot:** permitido para facilitar a comunicação (primeiras dúvidas, coleta de dados), sem afastar a pessoalidade nem suprimir a responsabilidade do advogado;<br>• **Aplicativos de consulta jurídica:** vedadas respostas automáticas indiscriminadas a não clientes;<br>• **Grupos de WhatsApp:** apenas pessoas determinadas, ligadas ao advogado ou ao escritório;<br>• **Mala direta:** vedada a coletividades, admitida a clientes, relacionamentos pessoais ou a quem autorizou previamente, sem caráter mercantil. | O botão de WhatsApp opera como canal de contato sob demanda do usuário (Art. 4º, § 3º), com mensagem de apresentação cordial e sem grupos ou listas de transmissão. O site não adota chatbots autônomos que emitam parecer ou consultoria jurídica sem supervisão do advogado. Anúncios pagos seguem o protocolo do repositório [`hotpages-advocacia`](https://github.com/souza-guedes/hotpages-advocacia/blob/main/docs/fase-1-analise/wdlc-1.2-protocolo-anuncios-pagos.md). | **ALTA** |

---

## 3. Diretriz Específica da Banca Souza Guedes: Artigo 3º, III (Especialidades sem Título Certificado)

O **Art. 3º, inciso III do Provimento CFOAB nº 205/2021** estabelece como conduta expressamente vedada:
> *"III - anúncio de especialidades para as quais não se possua título certificado ou notória especialização, nos termos do art. 3º-A do Estatuto da Advocacia;"*

### Diagnóstico no Contexto do Escritório Souza Guedes Advogados:
O perfil canônico do escritório ([`data/firm-profile.json`](../../data/firm-profile.json)) estabelece 4 áreas nucleares de prática jurídica:
1. **Direito Imobiliário**
2. **Direito de Saúde**
3. **Direito do Consumidor**
4. **Direito de Família e Sucessões**

Nas biografias dos sócios fundadores:
- O sócio **Rodrigo Guedes da Silva (OAB/SP nº 538.416)** possui graduação em Direito pela USJT e extensão em Falências e Recuperações Judiciais pela ESA/OAB;
- A sócia **Lays Regina de Souza (OAB/SP nº 511.204)** possui graduação em Direito pela USJT e pós-graduação lato sensu em *Direito Imobiliário Notarial e Registral* pela PUC.

### Distinção Normativa e Enquadramento:
- **Pós-Graduação Lato Sensu vs. Título Certificado:** A realização de curso de pós-graduação lato sensu não constitui, por si, o título certificado nos termos do art. 3º-A do Estatuto da Advocacia (Lei Federal nº 8.906/1994, que regula a notória especialização em serviços técnicos profissionais). Contudo, a menção à pós-graduação da sócia Lays Regina de Souza é plenamente legítima como **qualificação profissional verdadeira** (nos termos do Art. 4º, § 1º do Provimento 205/2021).
- **Nomenclatura Obrigatória para o Portal:** O portal, menus de navegação e chamadas de conteúdo devem utilizar exclusivamente a expressão descritiva institucional **"Áreas de Atuação"** ou a redação **"Atuação em Direito Imobiliário, Saúde, Consumidor, Família e Sucessões"**.
- **Conduta Vedada:** É terminantemente proibido redigir chamadas que autointitulem a banca ou os profissionais como *"Especialistas em Direito de Saúde"*, *"Especialistas em Direito do Consumidor"*, *"Especialistas em Direito de Família"* ou *"Especialistas em Direito Imobiliário"*, protegendo a comunicação contra a infração do Art. 3º, III.

---

## 4. Matriz de Expressões Proibidas (Blacklist Ética Reenquadrada)

O validador automatizado [`EthicalContentValidator`](../../src/Domain/Compliance/EthicalContentValidator.php) opera com limites estritos de caracteres alfabéticos Unicode (`/(?<!\p{L})TERMO(?!\p{L})/iu`). As expressões abaixo foram registradas no catálogo canônico [`data/ethical-compliance-rules.json`](../../data/ethical-compliance-rules.json):

| Expressão / Padrão | Categoria | Severidade | Dispositivo Legal de Referência | Motivo Regulatório Real |
| --- | --- | --- | --- | --- |
| `"o melhor"` / `"os melhores"` | `superlativo` | CRÍTICA | **Art. 3º, IV** | Vedação expressa a expressões persuasivas, de autoengrandecimento ou de comparação. |
| `"líder"` / `"líderes"` | `superlativo` | CRÍTICA | **Art. 3º, IV** | Expressão de autoengrandecimento mercantilista e concorrência comercial indevida. |
| `"o mais experiente"` / `"imbatível"` | `superlativo` | CRÍTICA / ALTA | **Art. 3º, IV** | Publicidade comparativa e autoelogio vedados pelo Código de Ética e Provimento 205/2021. |
| `"tabela de preços"` / `"preço popular"` | `preco_honorarios` | CRÍTICA | **Art. 3º, I** | Proibição de menção direta ou indireta a valores de honorários, preços ou condições de pagamento. |
| `"honorários promocionais"` / `"desconto exclusivo"` | `preco_honorarios` | CRÍTICA | **Art. 3º, I** | Mercantilização e aviltamento da profissão mediante concessão pública de vantagens comerciais. |
| `"consulta grátis"` / `"consulte grátis"` | `mercantilizacao` | CRÍTICA | **Art. 3º, I c/c Anexo Único** | Oferta de gratuidade indiscriminada como mecanismo de captação indevida de causas. |
| `"especialistas em direito de saúde"` / `"especialista em saúde"` | `especialidade_sem_titulo` | CRÍTICA | **Art. 3º, III** | Vedação ao anúncio de especialidade jurídica sem título certificado nos termos do art. 3º-A do Estatuto. |
| `"especialistas em direito do consumidor"` / `"especialista em consumidor"` | `especialidade_sem_titulo` | CRÍTICA | **Art. 3º, III** | Vedação ao anúncio de especialidade jurídica sem título certificado nos termos do art. 3º-A do Estatuto. |
| `"especialistas em direito de família"` / `"especialista em família"` | `especialidade_sem_titulo` | CRÍTICA | **Art. 3º, III** | Vedação ao anúncio de especialidade jurídica sem título certificado nos termos do art. 3º-A do Estatuto. |
| `"especialistas em direito imobiliário"` / `"especialista em direito imobiliário"` | `especialidade_sem_titulo` | HIGH | **Art. 3º, III** | Vedação de autointitulação ampla de especialidade nos termos do art. 3º-A, ressalvada qualificação acadêmica (art. 4º, § 1º). |
| `"resultado garantido"` / `"causa ganha"` | `promessa_resultado` | CRÍTICA | **Art. 6º, caput** | A advocacia é atividade de meio. Vedada qualquer promessa ou garantia de resultado processual. |
| `"sem risco"` / `"risco zero"` | `promessa_resultado` | CRÍTICA | **Art. 6º, caput** | Violação ao dever de prestar informação objetiva e verdadeira, induzindo o cliente a erro. |
| `"processe sua empresa agora"` / `"processe o banco"` | `captacao_litigio` | CRÍTICA / ALTA | **Art. 2º, VIII c/c Art. 3º, § 1º** | Captação indevida e incitação explícita ao litígio e conflitos judiciais artificiais. |
| Ostentação de carros, relógios, bens e patrimônio | `ostentacao` | CRÍTICA | **Art. 6º, parágrafo único** | Vedação expressa à ostentação de bens patrimoniais em qualquer meio de publicidade da advocacia. |

---

## 5. Checklist de Compliance Operacional

### 5.1 Para Redatores e Conteudistas (Copywriting Jurídico)
- [ ] **Tom de Voz Moderado:** O texto mantém tom sereno, técnico, pedagógico e acolhedor (Art. 1º e Art. 3º, caput)?
- [ ] **Sobriedade Semântica:** O conteúdo foi verificado pelo validador automático, livre de termos superlativos, promessas de resultado ou referências a preços (Art. 3º, I e IV; Art. 6º, caput)?
- [ ] **Enquadramento de Especialidades (Art. 3º, III):** As disciplinas são identificadas como *"Áreas de Atuação"*, sem autointitulação indevida de *"especialistas"* nos termos do art. 3º-A do Estatuto?
- [ ] **Caráter Informativo:** O artigo explica a lei e a jurisprudência sem incentivar ações judiciais predatórias (Art. 2º, VIII e Art. 3º, § 1º)?
- [ ] **Chamadas para Ação (CTAs) Moderadas:**
  - *Permitido:* "Conheça nossas áreas de atuação", "Entre em contato com nossa equipe", "Agende um atendimento".
  - *Proibido:* "Contrate agora os melhores advogados", "Processe já sua operadora", "Garantia de ressarcimento".
- [ ] **Sigilo e Não Divulgação de Clientes:** Nomes de clientes ou casos concretos foram integralmente resguardados, em conformidade com o Art. 4º, § 2º, Art. 5º, § 3º e Art. 6º do Provimento 205/2021?

### 5.2 Para Designers e Desenvolvedores (UI/UX e Arquitetura)
- [ ] **Identificação Completa e Conforme (Art. 1º, § 1º e Anexo Único):** Em rodapés, cabeçalhos de bio e seções institucionais constam com exatidão:
  - *Rodrigo Guedes da Silva — OAB/SP nº 538.416*
  - *Lays Regina de Souza — OAB/SP nº 511.204*
- [ ] **Disclaimer Ético no Rodapé:** Aviso regulamentar visível:
  > *"Este portal tem finalidade exclusivamente institucional, informativa e educativa, em rigorosa conformidade com o Provimento CFOAB nº 205/2021 e o Código de Ética e Disciplina da OAB. As informações divulgadas não configuram captação de clientela, promessa de resultados ou prestação de consulta jurídica direta, a qual depende de contratação formal e análise individualizada de cada caso."*
- [ ] **Arquitetura Passiva Estrita (Art. 2º, VII):** Ausência de pop-ups intrusivos de insistência comercial, contadores regressivos ou gatilhos predatórios de escassez.
- [ ] **Canais de Atendimento Passivo (Art. 4º, § 3º e Anexo Único):** Botão de WhatsApp direcionando para conversa com mensagem institucional de apresentação, sem disparo automático e sem resposta de consultoria por chatbot sem intervenção humana.
- [ ] **Pipeline de CI/CD Integrado:** Execução automatizada e determinística da suíte de testes (`php tests/run_tests.php`) com 100% de aprovação no GitHub Actions.

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
[X] ausencia_termos_superlativos_e_mercantis (Sem superlativos, preços ou promoções - Art. 3º, I e IV)
[X] sobriedade_visual_e_botoes_contato (Sem pop-ups intrusivos ou contadores - Art. 2º, VII)
[X] carater_informativo_sem_promessa_resultado (Sem promessas ou incitação ao litígio - Art. 6º e Art. 3º, § 1º)
[X] canais_atendimento_passivo (WhatsApp e canais passivos sem consulta automatizada - Art. 4º, § 3º e Anexo Único)

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
- **Cobertura de Testes (29 testes unitários - 100% PASS):**
  - Validação da estrutura do schema JSON e do catálogo normativo;
  - Validação estrita por regex cobrindo todos os Artigos de 1º a 6º do Provimento 205/2021;
  - Validação da regra do Art. 3º, III para especialidades sem título formal;
  - Validação de correspondência de texto por limites de palavras Unicode (evitando falsos positivos de palavras legítimas);
  - Validação de detecção de expressões compostas de especialidade e termos vedados (`especialistas em direito de saúde`, `os melhores`, `risco zero`, `preço popular`);
  - Validação estrita do formato formal de inscrição na OAB (rejeitando strings arbitrárias como "abc 123");
  - Validação do sign-off com reprovação de aprovadores não autorizados e checklists incompletos;
  - Homologação retroativa de conformidade do perfil da banca ([`data/firm-profile.json`](../../data/firm-profile.json)).
