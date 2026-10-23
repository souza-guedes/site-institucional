# WDLC 4.1 — Textos institucionais: trajetória, perfis, áreas e artigos

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

A Souza Guedes Advogados foi fundada em 2026 pelos advogados Lays Regina de Souza e Rodrigo Guedes da Silva, com sede na Vila Ema, Zona Leste de São Paulo.

O escritório atua no Direito Civil, em quatro áreas: Direito Imobiliário, Direito de Saúde, Direito do Consumidor e Direito de Família e Sucessões. O trabalho tem duas frentes. Na consultiva, o escritório orienta antes que o problema se instale: analisa documentos, contratos, matrículas e editais e aponta os riscos de cada decisão. Na contenciosa, conduz as medidas extrajudiciais e judiciais necessárias quando o conflito já existe.

Cada caso é analisado e acompanhado diretamente pelos sócios. O atendimento é presencial na sede, com agendamento, ou pelos canais oficiais do escritório.

A atuação se apoia em três valores, solidez, credibilidade e excelência, e segue o Estatuto da Advocacia, o Código de Ética e Disciplina da OAB e o Provimento CFOAB nº 205/2021: informação clara, sem promessa de resultado e sem captação de clientela.

## 3. Perfis dos sócios

### Rodrigo Guedes da Silva · OAB/SP nº 538.416

Rodrigo Guedes da Silva é advogado inscrito na OAB/SP sob o nº 538.416 e sócio fundador da Souza Guedes Advogados. Graduou-se em Direito pela Universidade São Judas Tadeu (USJT) e concluiu o curso de extensão em Falências e Recuperações Judiciais da Escola Superior de Advocacia da OAB (ESA/OAB).

Atua no Direito Civil, nas áreas de atuação do escritório.

**Formação**

- Graduação: Direito — Universidade São Judas Tadeu (USJT)
- Extensão: Falências e Recuperações Judiciais — Escola Superior de Advocacia da OAB (ESA/OAB)

**Publicações:** nenhuma informada até agora.

### Lays Regina de Souza · OAB/SP nº 511.204

Lays Regina de Souza é advogada inscrita na OAB/SP sob o nº 511.204 e sócia fundadora da Souza Guedes Advogados. Graduou-se em Direito pela Universidade São Judas Tadeu (USJT) e é pós-graduada em Direito Imobiliário Notarial e Registral pela Pontifícia Universidade Católica (PUC).

Atua no Direito Civil, nas áreas de atuação do escritório. A formação em direito notarial e registral apoia o trabalho com matrículas, escrituras e procedimentos em cartório, como a usucapião extrajudicial e o inventário por escritura pública.

**Formação**

- Graduação: Direito — Universidade São Judas Tadeu (USJT)
- Pós-graduação: Direito Imobiliário Notarial e Registral — Pontifícia Universidade Católica (PUC)

**Publicações:** nenhuma informada até agora.

## 4. Áreas de atuação

### Direito Imobiliário

A área de Direito Imobiliário reúne as questões jurídicas ligadas à aquisição, à posse, à propriedade e ao uso de imóveis. O escritório atua em usucapião judicial e extrajudicial, em ações de despejo, reintegração e imissão na posse, na assessoria para arrematação de imóveis em leilão e em ações indenizatórias por atraso na entrega de imóveis.

O trabalho começa pela análise documental: matrícula, contratos, edital do leilão e comprovantes de posse. É essa análise que indica se o caminho é extrajudicial, como a usucapião perante o Cartório de Registro de Imóveis ou a notificação para desocupação, ou se a questão precisa ser levada ao Judiciário.

A área atende pessoas físicas proprietárias, possuidoras, adquirentes ou locatárias de imóveis, investidores e arrematantes, e também construtoras, incorporadoras, imobiliárias e empresas que possuem ou utilizam imóveis em suas atividades.

### Direito de Saúde

A área de Direito de Saúde trata da relação entre pacientes, planos de saúde e prestadores de serviços de saúde. O escritório atua em negativas de cobertura de medicamentos, tratamentos, exames, cirurgias e internações, na manutenção e no restabelecimento de planos de saúde, na revisão de reajustes e cobranças indevidas e em casos de responsabilidade civil por erro médico.

Quando há negativa de cobertura, o primeiro passo é reunir a prescrição e o relatório médico, a negativa da operadora e o contrato do plano. Com esses documentos, avalia-se o pedido administrativo à operadora e a reclamação à ANS ou, quando a situação exige, a ação judicial com pedido de tutela de urgência.

A área atende pacientes, familiares e beneficiários de planos de saúde, e também profissionais, clínicas, hospitais e empresas do setor de saúde.

### Direito do Consumidor

A área de Direito do Consumidor cuida dos conflitos nas relações de consumo. O escritório atua em negativação e cobranças indevidas, na interrupção de serviços essenciais, como energia elétrica, água, telefonia e internet, em falhas na prestação de serviços e em problemas na compra de produtos e serviços, como vícios, defeitos, não entrega, descumprimento da oferta e dificuldade de cancelamento ou de restituição de valores.

A atuação parte dos documentos da relação de consumo: contratos, faturas, comprovantes de pagamento, protocolos de atendimento e registros de reclamação. Conforme o caso, busca-se a solução diretamente com o fornecedor ou pelos canais administrativos, ou propõe-se a ação para corrigir o problema e pedir a reparação dos danos materiais e morais.

A área atende pessoas físicas em suas relações de consumo e pequenas e médias empresas em demandas decorrentes dessas relações.

### Direito de Família e Sucessões

A área de Direito de Família e Sucessões acompanha os momentos de reorganização familiar e patrimonial. O escritório atua em divórcios consensuais e litigiosos, com a definição da partilha de bens, em ações sobre alimentos, guarda e convivência dos filhos, e em inventários judiciais e extrajudiciais.

Quando há acordo entre as partes, avalia-se a possibilidade de resolver a questão em cartório, por escritura pública, como no divórcio consensual e no inventário extrajudicial. Quando não há acordo, ou quando a lei exige, o caso segue pela via judicial. Nos dois caminhos, a assistência de advogado é obrigatória.

A área atende pessoas físicas e famílias em divórcio, sucessão, partilha de bens e transmissão de patrimônio.

## 5. Artigos inaugurais

Os três estão com `status: "rascunho"` e sem data de publicação. A autoria é uma proposta: cada artigo só é publicado depois que o sócio indicado revisar o texto e assumir a autoria.

| Artigo | Área | Autoria proposta | Palavras | Leitura |
| --- | --- | --- | --- | --- |
| 1. Usucapião extrajudicial: requisitos e documentos | Direito Imobiliário | Lays Regina de Souza | 639 | 4 min |
| 2. Negativa de cobertura de medicamento pelo plano de saúde | Direito de Saúde | Rodrigo Guedes da Silva | 545 | 3 min |
| 3. Inventário em cartório: quando é possível | Direito de Família e Sucessões | Lays Regina de Souza | 486 | 3 min |

Os artigos têm entre 490 e 640 palavras, abaixo das 800 a 1.200 previstas no plano. Eles não foram alongados com afirmações que não pudessem ser conferidas na fonte oficial. Se os sócios quiserem textos maiores, o caminho é acrescentar a experiência prática deles em cada tema.

### 5.1 Usucapião extrajudicial: requisitos e documentos

*Direito Imobiliário · Por Lays Regina de Souza, OAB/SP nº 511.204 · 4 min de leitura*

> Como funciona o reconhecimento da usucapião diretamente no Cartório de Registro de Imóveis, previsto no art. 216-A da Lei de Registros Públicos, e quais documentos costumam ser exigidos.

#### O que é a usucapião

A usucapião é a forma de adquirir a propriedade de um imóvel pela posse prolongada. Quem ocupa o imóvel como se fosse dono, de forma contínua e sem oposição, pelo prazo que a lei prevê, pode pedir que essa situação seja reconhecida e levada ao registro.

O prazo depende da modalidade. O Código Civil prevê, entre outras:

- Usucapião extraordinária: 15 anos de posse, independentemente de título e boa-fé, prazo que cai para 10 anos se o possuidor morar no imóvel ou nele tiver realizado obras ou serviços de caráter produtivo (art. 1.238).
- Usucapião ordinária: 10 anos de posse com justo título e boa-fé (art. 1.242).
- Usucapião especial urbana: 5 anos de posse de área urbana de até 250 m², usada para moradia, por quem não é proprietário de outro imóvel urbano ou rural (art. 1.240).

#### A via extrajudicial

Desde o Código de Processo Civil de 2015, a Lei de Registros Públicos admite o pedido de reconhecimento da usucapião diretamente no Cartório de Registro de Imóveis da comarca onde o imóvel está, sem processo judicial (art. 216-A da Lei nº 6.015/1973).

A via extrajudicial é uma opção, e não uma obrigação: a lei diz que ela existe "sem prejuízo da via jurisdicional". O pedido é feito pelo interessado, representado por advogado, e segue também as regras do Conselho Nacional de Justiça, hoje reunidas no Provimento CNJ nº 149/2023, e as normas da Corregedoria do tribunal do estado.

#### Documentos exigidos

O art. 216-A lista os documentos que acompanham o pedido:

- Ata notarial lavrada por tabelião, que atesta o tempo de posse do requerente e de seus antecessores.
- Planta e memorial descritivo assinados por profissional habilitado, com a anotação de responsabilidade técnica, e pelos titulares de direitos registrados na matrícula do imóvel e na dos imóveis vizinhos (confinantes).
- Certidões negativas dos distribuidores da comarca do imóvel e do domicílio do requerente.
- Justo título ou outros documentos que mostrem a origem, a continuidade, a natureza e o tempo da posse, como comprovantes de pagamento de impostos e taxas do imóvel.

Quando os documentos sobre a posse são insuficientes, a lei permite comprová-la em um procedimento de justificação administrativa no próprio cartório (art. 216-A, § 15).

#### Como o pedido anda no cartório

Recebido o pedido, o registrador notifica os titulares de direitos sobre o imóvel e sobre os confinantes que não assinaram a planta. Eles têm 15 dias para se manifestar, e o silêncio é interpretado como concordância. Em condomínio edilício, basta notificar o síndico.

O cartório também dá ciência do pedido à União, ao Estado, ao Distrito Federal e ao Município, e publica edital para que terceiros interessados possam se manifestar, também em 15 dias. Se a documentação estiver em ordem e não houver diligências pendentes, o registrador registra a aquisição do imóvel, abrindo matrícula quando for o caso.

#### Quando o pedido não segue no cartório

Se houver impugnação justificada, o cartório envia o procedimento ao juízo da comarca do imóvel, e o pedido passa a seguir o procedimento comum. Se a documentação não estiver em ordem ao final das diligências, o pedido é rejeitado. A rejeição no cartório não impede que a usucapião seja pedida depois na Justiça (art. 216-A, §§ 8º a 10).

#### O papel do advogado

A representação por advogado é exigida no pedido extrajudicial. Cabe ao advogado analisar a documentação, identificar a modalidade de usucapião aplicável, verificar a situação da matrícula e dos imóveis vizinhos e acompanhar as exigências do cartório até o registro. Cada situação de posse tem particularidades, e a viabilidade do pedido depende dessa análise individual.

**Referências**

- Código Civil (Lei nº 10.406/2002), arts. 1.238, 1.240 e 1.242.
- Lei de Registros Públicos (Lei nº 6.015/1973), art. 216-A, incluído pela Lei nº 13.105/2015, com redação das Leis nº 13.465/2017 e nº 14.382/2022.
- Provimento CNJ nº 149/2023 (Código Nacional de Normas da Corregedoria Nacional de Justiça, Foro Extrajudicial).

*Este conteúdo tem caráter exclusivamente informativo e educativo (Art. 4º do Provimento CFOAB nº 205/2021) e não substitui a análise individual de cada caso por advogado.*

### 5.2 Negativa de cobertura de medicamento pelo plano de saúde

*Direito de Saúde · Por Rodrigo Guedes da Silva, OAB/SP nº 538.416 · 3 min de leitura*

> O que observar quando o plano de saúde nega um medicamento prescrito: o rol da ANS, a Lei nº 14.454/2022, os critérios fixados pelo STF em 2025 e os documentos que ajudam a analisar o caso.

#### O rol da ANS

A Agência Nacional de Saúde Suplementar (ANS) mantém o rol de procedimentos e eventos em saúde, a lista de coberturas que os planos devem oferecer. Pela Lei dos Planos de Saúde, esse rol é a referência básica para os planos contratados a partir de 1º de janeiro de 1999 e para os contratos adaptados à lei (art. 10, § 12, da Lei nº 9.656/1998).

Quando o medicamento prescrito consta do rol e as condições de cobertura são atendidas, a operadora deve autorizá-lo nos termos do contrato e da regulação da ANS.

#### Tratamentos fora do rol

A Lei nº 14.454/2022 incluiu na Lei dos Planos de Saúde a possibilidade de cobertura de tratamento prescrito que não está no rol, desde que haja comprovação de eficácia baseada em evidências científicas ou recomendação da Conitec ou de órgão de avaliação de tecnologias em saúde de renome internacional (art. 10, § 13).

Em 18 de setembro de 2025, ao julgar a ADI 7265, o Supremo Tribunal Federal manteve essa possibilidade, mas fixou cinco critérios que devem ser cumpridos ao mesmo tempo:

- o tratamento deve ser prescrito por médico ou odontólogo assistente;
- o tratamento não pode ter sido expressamente negado pela ANS nem estar pendente de análise para inclusão no rol;
- não deve haver alternativa terapêutica adequada no rol da ANS;
- o tratamento deve ter comprovação científica de eficácia e segurança;
- o tratamento deve ter registro na Agência Nacional de Vigilância Sanitária (Anvisa).

Segundo a decisão, a Justiça só pode autorizar tratamento fora do rol quando esses critérios são atendidos e quando fica demonstrado que a operadora negou o tratamento ou demorou excessivamente, ou se omitiu, em autorizá-lo.

#### A negativa precisa ser comunicada

A Resolução Normativa ANS nº 623/2024, que trata do atendimento aos beneficiários, define o que a operadora deve informar quando nega uma cobertura e prevê a reanálise da negativa pela ouvidoria da operadora. Vale anotar os números de protocolo de cada contato e pedir que a negativa e o seu motivo fiquem registrados por escrito.

#### Documentos úteis

Para analisar uma negativa, costumam ser necessários:

- Prescrição médica do medicamento, com dose e duração do tratamento.
- Relatório médico detalhado: diagnóstico, tratamentos já realizados e motivo da indicação, inclusive por que as alternativas do rol não são adequadas ao caso, quando for essa a situação.
- A negativa da operadora, com o motivo informado, e os protocolos de atendimento.
- Contrato ou documento do plano e carteirinha do beneficiário.
- Informações sobre o registro do medicamento na Anvisa.

#### Caminhos possíveis

Antes da via judicial, é possível pedir a reanálise à operadora e registrar reclamação nos canais da ANS. Quando a demora puder causar dano à saúde do paciente, a ação judicial pode incluir pedido de tutela de urgência, que depende da demonstração da probabilidade do direito e do perigo de dano (art. 300 do Código de Processo Civil).

Cada caso depende do contrato, do quadro clínico e dos critérios acima, por isso a análise é sempre individual.

**Referências**

- Lei dos Planos de Saúde (Lei nº 9.656/1998), art. 10, §§ 12 e 13, incluídos pela Lei nº 14.454/2022.
- STF, Ação Direta de Inconstitucionalidade 7265, julgada em 18/9/2025.
- Resolução Normativa ANS nº 623/2024.
- Código de Processo Civil (Lei nº 13.105/2015), art. 300.

*Este conteúdo tem caráter exclusivamente informativo e educativo (Art. 4º do Provimento CFOAB nº 205/2021) e não substitui a análise individual de cada caso por advogado.*

### 5.3 Inventário em cartório: quando é possível

*Direito de Família e Sucessões · Por Lays Regina de Souza, OAB/SP nº 511.204 · 3 min de leitura*

> Quando o inventário pode ser feito por escritura pública, o que mudou com a Resolução CNJ nº 571/2024 e quais documentos o cartório exige.

#### Duas vias para o inventário

O inventário é o procedimento que apura os bens, os direitos e as dívidas de quem faleceu e divide o patrimônio entre os herdeiros. Ele pode ser feito na Justiça ou em cartório.

O Código de Processo Civil prevê que, se todos os herdeiros forem capazes e estiverem de acordo, o inventário e a partilha podem ser feitos por escritura pública. A escritura serve para o registro dos imóveis e para o levantamento de valores depositados em bancos (art. 610, § 1º).

#### O que mudou em 2024

O texto do Código prevê o inventário judicial quando há testamento ou herdeiro incapaz (art. 610). Em agosto de 2024, a Resolução CNJ nº 571 alterou a Resolução CNJ nº 35/2007, que regula esses atos em cartório, e passou a admitir a escritura também nessas duas situações, com condições próprias.

- Herdeiro menor ou incapaz: a escritura é possível se a parte dele for paga em fração ideal de cada um dos bens e houver manifestação favorável do Ministério Público. Os bens do menor ou incapaz não podem ser objeto de atos de disposição, e, havendo impugnação, o caso vai ao juiz (art. 12-A).
- Testamento: a escritura é possível se houver autorização expressa do juízo, em sentença transitada em julgado na ação de abertura e cumprimento do testamento, e se todos estiverem representados por advogado e de acordo (art. 12-B).

#### Requisitos em resumo

- Acordo entre todos os herdeiros sobre a partilha.
- Assistência de advogado ou defensor público, cujo nome e registro constam da escritura.
- Nos casos de herdeiro menor ou incapaz e de testamento, as condições da Resolução CNJ nº 35/2007 descritas acima.

#### Documentos exigidos

A Resolução CNJ nº 35/2007 lista os documentos apresentados na lavratura da escritura (art. 22):

- Certidão de óbito.
- Documento de identidade e CPF das partes e de quem faleceu.
- Certidão que comprove o parentesco dos herdeiros.
- Certidão de casamento do cônjuge sobrevivente e dos herdeiros casados, e pacto antenupcial, se houver.
- Certidão de propriedade dos imóveis e documentos que comprovem a titularidade dos bens móveis e direitos.
- Certidão negativa de tributos.
- Certificado de Cadastro de Imóvel Rural (CCIR), se houver imóvel rural.

#### Prazo

O Código de Processo Civil prevê que o inventário seja aberto em até 2 meses do falecimento (art. 611). A legislação de cada estado pode prever multa sobre o imposto de transmissão (ITCMD) quando esse prazo não é respeitado.

#### O papel do advogado

O cartório só lavra a escritura com a participação de advogado. Cabe a ele orientar sobre a partilha e os direitos de cada herdeiro, reunir a documentação, acompanhar o cálculo e o recolhimento dos tributos e verificar se o caso pode seguir em cartório ou precisa ir à Justiça.

**Referências**

- Código de Processo Civil (Lei nº 13.105/2015), arts. 610 e 611.
- Resolução CNJ nº 35/2007, arts. 8º, 12-A, 12-B e 22, com redação da Resolução CNJ nº 571/2024.

*Este conteúdo tem caráter exclusivamente informativo e educativo (Art. 4º do Provimento CFOAB nº 205/2021) e não substitui a análise individual de cada caso por advogado.*

### Conferência das normas citadas

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

**Trajetória**

- [ ] Fatos da fundação que os sócios queiram registrar (motivação, escolha da Vila Ema).
- [ ] Confirmar que todos os casos são conduzidos diretamente pelos sócios, como diz o terceiro parágrafo.

**Rodrigo Guedes da Silva**

- [ ] Ano de conclusão da graduação, da extensão e de inscrição na OAB/SP.
- [ ] Publicações, se houver.
- [ ] Experiência profissional anterior à fundação do escritório, se for para constar.
- [ ] Áreas do escritório em que atua com mais frequência, se for para constar.

**Lays Regina de Souza**

- [ ] Unidade da PUC e ano de conclusão da pós-graduação.
- [ ] Ano de conclusão da graduação e de inscrição na OAB/SP.
- [ ] Publicações, se houver.
- [ ] Experiência profissional anterior à fundação do escritório, se for para constar.
- [ ] Confirmar a relação entre a pós-graduação e o trabalho em cartório descrita no segundo parágrafo.

**Artigos**

- [ ] Usucapião extrajudicial: requisitos e documentos: revisão técnica e aprovação do texto e da autoria por Lays Regina de Souza.
- [ ] Negativa de cobertura de medicamento pelo plano de saúde: revisão técnica e aprovação do texto e da autoria por Rodrigo Guedes da Silva.
- [ ] Inventário em cartório: quando é possível: revisão técnica e aprovação do texto e da autoria por Lays Regina de Souza.

## 7. Auditoria automática

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
