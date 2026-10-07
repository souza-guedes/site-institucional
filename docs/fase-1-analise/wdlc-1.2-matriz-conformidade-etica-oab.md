# WDLC 1.2 — Matriz de Conformidade Ética com o Provimento OAB nº 205/2021

> **Status:** Aprovado e Vigente  
> **Fase:** 1 — Levantamento de Requisitos, Identidade Institucional e Compliance OAB  
> **Sub-issue:** [#3](https://github.com/souza-guedes/site-institucional/issues/3)  
> **Épico Relacionado:** [#1](https://github.com/souza-guedes/site-institucional/issues/1)  
> **Dataset Oficial:** [`data/ethical-compliance-rules.json`](../../data/ethical-compliance-rules.json) validado por [`schemas/ethical-compliance.schema.json`](../../schemas/ethical-compliance.schema.json)  
> **Validador Automatizado:** [`src/Domain/Compliance/EthicalContentValidator.php`](../../src/Domain/Compliance/EthicalContentValidator.php)  
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

## 2. Auditoria Sistemática: Artigos 1º a 6º do Provimento CFOAB nº 205/2021

Abaixo sintetiza-se a interpretação técnica e a aplicação prática de cada artigo do Provimento no desenvolvimento do site institucional:

| Dispositivo Legal | Conteúdo Normativo Central | Aplicação Direta no Portal Souza Guedes | Severidade |
| --- | --- | --- | --- |
| **Art. 1º** | Permissão da publicidade informativa, pautada na moderação e sobriedade. Vedação a publicidade ativa indiscriminada e incitação ao litígio. | O portal atua como canal institucional sob demanda do usuário. Os textos explicam direitos e conceitos jurídicos sem fomentar ações predatórias ou conflitos judiciais artificiais. | **CRÍTICA** |
| **Art. 2º** | Distinção entre **Publicidade Passiva** (o cidadão busca espontaneamente a informação) e **Publicidade Ativa** (envio ou exibição sem iniciativa prévia do destinatário). | A arquitetura do website adota integralmente o modelo passivo: navegação orgânica, sem pop-ups invasivos de captura forçada ou scripts intrusivos de insistência comercial. | **ALTA** |
| **Art. 3º** | Exigência mandatória de indicação do nome completo e número de inscrição na OAB de cada advogado. É permitida a indicação de títulos e especialidades; vedada a menção a cargos públicos pretéritos/atuais que sugiram influência. | Em todos os pontos de contato, rodapés e seções institucionais constam explicitamente: **Rodrigo Guedes da Silva (OAB/SP nº 538.416)** e **Lays Regina de Souza (OAB/SP nº 511.204)**. Nenhuma alusão a influências políticas ou judiciárias é admitida. | **CRÍTICA** |
| **Art. 4º** | **Vedações Expressas:**<br>I - Tabelas de preços, valores ou condições promocionais;<br>II - Autoengrandecimento e termos superlativos ("o melhor", "líder", "imbatível");<br>III - Promessa ou garantia de resultado ("causa ganha", "sem risco");<br>IV - Captação indevida e aliciamento de causas. | Implementação de filtros automáticos no pipeline (`EthicalContentValidator`) que barram vocábulos promocionais, promessas de êxito e tabelas de valores. | **CRÍTICA** |
| **Art. 5º** | Autorização de websites, blogs, podcasts e redes sociais. Limites ao uso de ferramentas de mensageria (WhatsApp) e chatbots: vedada resposta automatizada massificada a consultas jurídicas privativas da advocacia. | O botão de WhatsApp direciona para canal passivo com mensagem formal inicial de apresentação e acolhimento humano. Chatbots automáticos não fornecem consultoria jurídica substitutiva do advogado. | **ALTA** |
| **Art. 6º** | Regras para links patrocinados e impulsionamento nas ferramentas de busca (Google Ads). | Campanhas futuras deverão se restringir a termos informativos e institucionais pesquisados pelo próprio interessado, sem slogans comerciais agressivos. | **MÉDIA** |

---

## 3. Matriz de Expressões Proibidas (Blacklist Ética)

O validador automatizado [`EthicalContentValidator`](../../src/Domain/Compliance/EthicalContentValidator.php) e os revisores devem reprovar sumariamente conteúdos contendo os seguintes termos ou equivalentes semânticos:

| Expressão / Padrão | Categoria | Severidade | Motivo Regulatório |
| --- | --- | --- | --- |
| `"o melhor"` / `"os melhores"` | Superlativo | CRÍTICA | Vedação expressa a autoelogio e comparação (Art. 4º, II). |
| `"líder de mercado"` / `"líder"` | Superlativo | CRÍTICA | Prática mercantilista de concorrência comercial desleal. |
| `"o mais experiente"` / `"imbatível"` | Superlativo | ALTA | Publicidade comparativa proibida pelo CED. |
| `"resultado garantido"` / `"causa ganha"` | Promessa de Resultado | CRÍTICA | A advocacia é atividade de meio. Vedada qualquer garantia de êxito processual (Art. 4º, III). |
| `"sem risco"` / `"risco zero"` | Promessa de Resultado | CRÍTICA | Falsa indução do cliente e quebra da verdade jurídica. |
| `"tabela de preços"` / `"preço popular"` | Preço / Honorários | CRÍTICA | Proibição categórica de tabelas públicas de honorários (Art. 4º, I). |
| `"honorários promocionais"` / `"desconto exclusivo"` | Preço / Honorários | CRÍTICA | Mercantilização e aviltamento da profissão. |
| `"consulta grátis"` / `"consulte grátis"` | Mercantilização | CRÍTICA | Captação indevida e concorrência desleal (Anexo Único do Provimento 205/2021). |
| `"processe sua empresa agora"` | Incitação ao Litígio | CRÍTICA | Estimulação explícita à litigiosidade predatória (Art. 1º, § 1º). |
| `"processe o banco"` / `"processe o plano"` | Incitação ao Litígio | ALTA | Uso de linguagem de confronto belicoso em vez de orientação pedagógica. |
| Ostentação de carros, relógios ou patrimônio | Ostentação | CRÍTICA | Proibição de associação de imagem a bens de luxo como atrativo profissional (Art. 4º, VI). |

---

## 4. Checklist de Compliance Operacional

### 4.1 Para Redatores e Conteudistas (Copywriting Jurídico)
- [ ] **Tom de Voz:** O texto mantém tom sereno, técnico, pedagógico e acolhedor?
- [ ] **Sobriedade Semântica:** O conteúdo foi verificado contra a lista de termos proibidos (sem adjetivos superlativos ou promessas)?
- [ ] **Caráter Informativo:** O artigo/texto explica a legislação, o entendimento jurisprudencial ou os requisitos de um direito, sem prometer desfechos judiciais específicos?
- [ ] **Chamadas para Ação (CTAs) Moderadas:**
  - *Permitido:* "Conheça mais sobre as diretrizes do Direito Imobiliário", "Agende uma conversa com nossa equipe", "Entre em contato pelos nossos canais institucionais".
  - *Proibido:* "Contrate agora o melhor especialista", "Clique e garanta sua indenização", "Não perca tempo, processe já".
- [ ] **Sigilo e Anonimização:** Nomes de clientes ou números de processos confidenciais foram integralmente omitidos ou anonimizados?

### 4.2 Para Designers e Desenvolvedores (UI/UX e Arquitetura)
- [ ] **Identificação Completa:** Em todos os cards de advogados e no rodapé institucional constam os nomes completos e inscrições da OAB:
  - *Rodrigo Guedes da Silva — OAB/SP nº 538.416*
  - *Lays Regina de Souza — OAB/SP nº 511.204*
- [ ] **Disclaimer Ético no Footer:** O rodapé do site exibe o aviso regulamentar:
  > *"Este portal tem finalidade exclusivamente institucional, informativa e educativa, em conformidade com o Provimento CFOAB nº 205/2021 e o Código de Ética e Disciplina da OAB. As informações aqui contidas não constituem consultoria jurídica direta nem garantia de resultado."*
- [ ] **Ambiente Passivo e Não Invasivo:** Ausência de janelas pop-up com cronômetros de contagem regressiva, banners piscantes ou avisos de "ofertas por tempo limitado".
- [ ] **Canais de Contato:** O botão de WhatsApp abre conversa com mensagem cordial e institucional, sem mensagem pré-redigida que incite ação judicial ou declare contratação instantânea.
- [ ] **Pipeline Automatizado:** Os testes de validação ética (`php tests/run_tests.php`) estão passando com 100% de sucesso.

---

## 5. Termo Interno de Validação Jurídica Pré-Publicação

Nenhuma página, seção ou artigo pode ser colocado em produção sem a conclusão do processo formal de governança pré-publicação:

### Fluxo de Aprovação (Workflow)
1. **Redação:** O autor cria o conteúdo observando o Checklist 4.1.
2. **Desenvolvimento:** O layout e metadados são montados observando o Checklist 4.2.
3. **CI/CD Automático:** O sistema executa o [`EthicalContentValidator`](../../src/Domain/Compliance/EthicalContentValidator.php).
4. **Revisão Humana e Sign-off:** Pelo menos um dos sócios fundadores revisa a peça e formaliza o sign-off através da entidade [`PrePublicationSignoff`](../../src/Domain/Compliance/PrePublicationSignoff.php).
5. **Deploy:** Publicação liberada no portal com histórico registrado.

### Modelo do Termo de Validação
```
TERMO DE AVALIAÇÃO E SIGNOFF JURÍDICO PRÉ-PUBLICAÇÃO
Identificador do Conteúdo: [ex: page-home-v1]
Título / Peça: [ex: Página Inicial Institucional]
Data da Validação: [AAAA-MM-DD]
Sócio Responsável: ( ) Rodrigo Guedes da Silva (OAB/SP 538.416)
                   ( ) Lays Regina de Souza (OAB/SP 511.204)

Checklist de Conformidade Verificado:
[X] Identificação completa e visível dos advogados e registros OAB
[X] Ausência estrita de expressões superlativas, promocionais e mercantilização
[X] Moderação visual e ausência de elementos coercitivos/invasivos
[X] Caráter estritamente informativo sem promessa de resultado processual
[X] Aprovação dos canais de atendimento passivo

PARECER: (X) APROVADO PARA PUBLICAÇÃO   ( ) REPROVADO PARA AJUSTES
Observações: Peça compatível com o Provimento CFOAB nº 205/2021.
```

---

## 6. Verificação e Evidências Técnicas

Os contratos de dados e validadores foram testados e homologados pela suíte de testes determinística do projeto:
- **Test Runner:** `php tests/run_tests.php`
- **Casos de Teste de Compliance:** 14 asserções cobrindo integridade do schema, verificação de todas as regras dos Artigos 1º a 6º, detecção insensível a maiúsculas de termos vedados, rejeição de cadastros sem OAB completa e validação de conformidade 100% verde para o perfil canônico da banca (`data/firm-profile.json`).
