# WDLC 2.1 — Definição de Rotas Amigáveis e Estrutura de Diretórios PHP 8.2+

> **Status:** Concluído e Homologado  
> **Fase:** 2 — Arquitetura da Informação, Sitemap e Rotas PHP  
> **Sub-issue:** [#5](https://github.com/souza-guedes/site-institucional/issues/5)  
> **Épico Relacionado:** [#4](https://github.com/souza-guedes/site-institucional/issues/4)  
> **Ponto de Entrada (Front Controller):** [`public/index.php`](../../public/index.php)  
> **Configuração Apache/LiteSpeed:** [`public/.htaccess`](../../public/.htaccess) e [`./.htaccess`](../../.htaccess)  
> **Gestão de Configuração e Domínio:** [`src/Config/AppConfig.php`](../../src/Config/AppConfig.php)  
> **Motor de Roteamento:** [`src/Http/Router.php`](../../src/Http/Router.php)  
> **Estilo Institucional:** [`public/assets/css/main.css`](../../public/assets/css/main.css)  
> **Suíte de Testes:** 56 testes unitários no runner determinístico [`tests/run_tests.php`](../../tests/run_tests.php)  
> **Documento no Notion:** [Souza Guedes Advogados | Rotas Amigáveis e Estrutura Modular PHP (WDLC 2.1)](https://app.notion.com/p/Souza-Guedes-Advogados-Rotas-Amig-veis-e-Estrutura-Modular-PHP-WDLC-2-1-3f2ea742df39815481bbfa5e7ad37c2c)

---

## 1. Visão Geral da Arquitetura

O portal institucional do escritório **Souza Guedes Advogados** adota o padrão arquitetural **Front Controller** com roteamento transparente em PHP 8.2+ nativo, eliminando a exposição de extensões de arquivo (`.php`, `.html`) nas URLs públicas.

A arquitetura foi projetada para garantir:
1. **Segurança (Defense in Depth):** Dupla camada de proteção com `.htaccess` na raiz do projeto (impedindo acesso direto a `src/`, `data/`, `schemas/`, `tests/` mesmo se o servidor apontar para a raiz) e em `public/` (isolando o `DocumentRoot`).
2. **Desacoplamento de Domínio e Ambiente:** Classe centralizada `App\Config\AppConfig` que infere o protocolo (HTTP/HTTPS), portas e host dinamicamente para geração de URLs canônicas limpas, evitando hardcode de domínios antes da homologação final.
3. **Performance e Desacoplamento:** Roteador nativo sem frameworks pesados ou overhead de dependências externas desnecessárias, assegurando tempos de resposta inferiores a 10ms.
4. **SEO & UX:** URLs limpas, sem barra final duplicada (*trailing slash normalization* com flag `QSA` para preservação integral de parâmetros) e redirecionamento HTTPS canônico flexível para portas locais.
5. **Blindagem contra Route Spoofing:** O método `Request::createFromGlobals()` prioriza o `parse_url(REQUEST_URI, PHP_URL_PATH)` sanitizado e só recorre ao parâmetro de reescrita `?route=` caso o script executado seja explicitamente o `index.php`.
6. **Compliance Centralizado:** Injeção padronizada de metadados, identificação dos advogados com número de inscrição na OAB/SP e disclaimer regulamentar do **Provimento CFOAB nº 205/2021** através de partials modulares.

---

## 2. Mapa Canônico de Rotas

Todas as rotas operam sob o método `GET` e direcionam para controladores dedicados:

| Rota Amigável | Controlador | Ação / View Renderizada | Finalidade Institucional |
| --- | --- | --- | --- |
| `/` | `App\Controllers\HomeController` | `src/Views/pages/home.php` | Página inicial da banca, visão geral e destaques. |
| `/sobre` | `App\Controllers\AboutController` | `src/Views/pages/about.php` | Apresentação institucional, sócios fundadores e valores. |
| `/areas-de-atuacao` | `App\Controllers\PracticeAreaController` | `src/Views/pages/practice-areas.php` | Visão geral das quatro disciplinas de prática jurídica. |
| `/areas-de-atuacao/{slug}` | `App\Controllers\PracticeAreaController` | `src/Views/pages/practice-area-detail.php` | Detalhamento técnico de disciplina (ex.: `direito-imobiliario`). |
| `/contato` | `App\Controllers\ContactController` | `src/Views/pages/contact.php` | Canais formais de atendimento passivo e localização da sede. |
| *fallback* | `App\Controllers\ErrorController` | `src/Views/pages/404.php` | Handler customizado para erro 404 (status HTTP 404 via array de controlador). |

---

## 3. Árvore de Diretórios Modular

```text
site-institucional/
├── .github/
│   └── workflows/
│       └── ci.yml               # Pipeline de CI/CD automatizado no GitHub Actions
├── .htaccess                    # Proteção de raiz e reescrita para public/
├── data/
│   ├── firm-profile.json        # Dataset oficial da banca (WDLC 1.1)
│   └── ethical-compliance-rules.json # Catálogo de regras éticas (WDLC 1.2)
├── docs/
│   ├── fase-1-analise/          # Documentação da Fase 1
│   └── fase-2-planejamento/     # Documentação da Fase 2
│       └── wdlc-2.1-rotas-amigaveis-estrutura-php.md
├── public/                      # DocumentRoot do servidor web
│   ├── .htaccess                # Reescrita Apache/LiteSpeed e headers de segurança
│   ├── index.php                # Front Controller da aplicação
│   ├── robots.txt               # Diretrizes para indexadores e buscadores
│   └── assets/                  # Arquivos estáticos
│       └── css/
│           └── main.css         # Estilização institucional completa e sóbria
├── schemas/                     # JSON Schemas formais
├── src/
│   ├── Config/                  # Configurações de ambiente e domínio
│   │   └── AppConfig.php        # Resolução dinâmica de domínio e URLs canônicas
│   ├── Controllers/             # Controladores de requisição e despacho
│   │   ├── BaseController.php
│   │   ├── HomeController.php
│   │   ├── AboutController.php
│   │   ├── PracticeAreaController.php
│   │   ├── ContactController.php
│   │   └── ErrorController.php
│   ├── Domain/                  # Modelos e regras de negócio/compliance
│   │   ├── Compliance/
│   │   └── Entities/
│   ├── Http/                    # Motor de requisição e roteamento
│   │   ├── Request.php
│   │   ├── Response.php
│   │   └── Router.php
│   └── Views/                   # Templates e componentes de interface
│       ├── pages/               # Páginas completas (home, about, contact, etc.)
│       └── partials/            # Componentes modulares
│           ├── header.php
│           ├── navbar.php
│           ├── footer.php
│           └── cookie-banner.php
└── tests/                       # Testes determinísticos
    ├── Unit/
    │   ├── FirmProfileSchemaTest.php
    │   ├── FirmProfileEntityTest.php
    │   ├── EthicalComplianceSchemaTest.php
    │   ├── EthicalContentValidatorTest.php
    │   ├── RouterTest.php
    │   ├── FrontControllerExecutionTest.php
    │   ├── AppConfigTest.php
    │   ├── HtaccessConfigTest.php
    │   └── ViewsStructureTest.php
    └── run_tests.php            # Test runner determinístico (56 testes)
```

---

## 4. Regras do Servidor Web (`.htaccess`)

### 4.1. Camada de Proteção da Raiz (`.htaccess`)
Se a hospedagem configurar o `DocumentRoot` apontando para a raiz do repositório em vez de `public/`, o arquivo `.htaccess` na raiz:
1. Bloqueia sumariamente o acesso direto com `Require all denied` a `src/`, `data/`, `schemas/`, `tests/` e arquivos dotfiles.
2. Encaminha todas as requisições públicas para o subdiretório `public/`.
3. Define `DirectoryIndex public/index.php`.

### 4.2. Camada Pública (`public/.htaccess`)
O arquivo [`public/.htaccess`](../../public/.htaccess) implementa:
1. **Bloqueio de Arquivos Ocultos e Dotfiles:** `RewriteRule ^\..* - [F,L]` impede acesso a arquivos sensíveis.
2. **Canonicalização de HTTPS Flexível:** Redireciona HTTP para HTTPS preservando compatibilidade com `localhost` e portas de desenvolvimento (ex.: `localhost:8080`).
3. **Normalização de Trailing Slash com QSA:** `RewriteRule ^(.*)/$ /$1 [L,R=301,QSA]` remove barras finais sem descartar parâmetros de consulta (`$_GET`).
4. **Front Controller Pattern:** Despacha requisições dinâmicas para `index.php?route=$1 [QSA,L]`.
5. **Headers de Segurança:** `X-Content-Type-Options: nosniff`, `X-Frame-Options: SAMEORIGIN`, `X-XSS-Protection` e `Referrer-Policy: strict-origin-when-cross-origin`.

---

## 5. Garantia de Compliance OAB nos Componentes de View

O partial modular [`src/Views/partials/footer.php`](../../src/Views/partials/footer.php) centraliza os requisitos normativos do **Provimento CFOAB nº 205/2021**:
- **Identificação dos Advogados (Art. 1º, § 1º e Anexo Único):**
  - **Rodrigo Guedes da Silva** — OAB/SP nº 538.416
  - **Lays Regina de Souza** — OAB/SP nº 511.204
- **Disclaimer Regulamentar Obrigatório:**
  Aviso legal explícito declarando o caráter institucional, informativo e educativo do portal, afastando promessas de resultado ou captação de causas.
- **Nomenclatura Institucional (Art. 3º, III):**
  Todos os menus e cabeçalhos adotam a designação **"Áreas de Atuação"**, em conformidade com as regras éticas da advocacia.

---

## 6. Verificação e Evidências Técnicas

A funcionalidade foi homologada e testada de forma 100% determinística:
- **Test Runner Local:** `php tests/run_tests.php`
- **Total de Testes da Aplicação:** 56 testes unitários (`56/56 PASS`)
  - `RouterTest` (6 testes): rotas exatas, rotas com parâmetros `{slug}`, normalização de trailing slash, 404 e sanitização contra path traversal.
  - `FrontControllerExecutionTest` (3 testes): assinatura `callable|array` no `setNotFoundHandler`, fluxo de bootstrap completo e proteção contra spoofing por `?route=`.
  - `AppConfigTest` (4 testes): existência da classe, resolução de ambiente, base URL com portas/HTTPS e URLs canônicas.
  - `HtaccessConfigTest` (9 testes): arquivos públicos, proteção da raiz contra vazamento de código, RewriteEngine, Front Controller, HTTPS com porta, trailing slash com flag QSA, headers de segurança e robots.txt.
  - `ViewsStructureTest` (5 testes): existência de partials, páginas, conformidade do footer com OAB, rotas limpas na navbar e existência do CSS `public/assets/css/main.css`.
  - `FirmProfileSchemaTest` (7 testes), `FirmProfileEntityTest` (5 testes), `EthicalComplianceSchemaTest` (8 testes), `EthicalContentValidatorTest` (9 testes).
- **Pipeline de Integração Contínua:** Verificado no GitHub Actions via `.github/workflows/ci.yml`.
