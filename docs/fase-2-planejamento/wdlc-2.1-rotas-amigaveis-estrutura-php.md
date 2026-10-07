# WDLC 2.1 — Definição de Rotas Amigáveis e Estrutura de Diretórios PHP 8.2+

> **Status:** Concluído e Homologado  
> **Fase:** 2 — Arquitetura da Informação, Sitemap e Rotas PHP  
> **Sub-issue:** [#5](https://github.com/souza-guedes/site-institucional/issues/5)  
> **Épico Relacionado:** [#4](https://github.com/souza-guedes/site-institucional/issues/4)  
> **Ponto de Entrada (Front Controller):** [`public/index.php`](../../public/index.php)  
> **Configuração Apache/LiteSpeed:** [`public/.htaccess`](../../public/.htaccess)  
> **Motor de Roteamento:** [`src/Http/Router.php`](../../src/Http/Router.php)  
> **Suíte de Testes:** [`tests/Unit/RouterTest.php`](../../tests/Unit/RouterTest.php), [`tests/Unit/HtaccessConfigTest.php`](../../tests/Unit/HtaccessConfigTest.php) e [`tests/Unit/ViewsStructureTest.php`](../../tests/Unit/ViewsStructureTest.php)  
> **Documento no Notion:** [Souza Guedes Advogados | Rotas Amigáveis e Estrutura Modular PHP (WDLC 2.1)](https://app.notion.com/p/Souza-Guedes-Advogados-Rotas-Amig-veis-e-Estrutura-Modular-PHP-WDLC-2-1-3f2ea742df39815481bbfa5e7ad37c2c)

---

## 1. Visão Geral da Arquitetura

O portal institucional do escritório **Souza Guedes Advogados** adota o padrão arquitetural **Front Controller** com roteamento transparente em PHP 8.2+ nativo, eliminando a exposição de extensões de arquivo (`.php`, `.html`) nas URLs públicas.

A arquitetura foi projetada para garantir:
1. **Segurança (Defense in Depth):** Apenas o diretório `public/` é exposto como raiz web (`DocumentRoot`), isolando o código-fonte (`src/`), regras canônicas (`data/`, `schemas/`) e testes (`tests/`).
2. **Performance e Desacoplamento:** Roteador nativo sem frameworks pesados ou overhead de dependências externas desnecessárias, assegurando tempos de resposta inferiores a 10ms.
3. **SEO & UX:** URLs limpas, sem barra final duplicada (*trailing slash normalization*) e forçando HTTPS canônico.
4. **Compliance Centralizado:** Injeção padronizada de metadados, identificação dos advogados com número de inscrição na OAB/SP e disclaimer regulamentar do **Provimento CFOAB nº 205/2021** através de partials modulares.

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
| *fallback* | `App\Controllers\ErrorController` | `src/Views/pages/404.php` | Handler customizado para erro 404 (status HTTP 404). |

---

## 3. Árvore de Diretórios Modular

```text
site-institucional/
├── .github/
│   └── workflows/
│       └── ci.yml               # Pipeline de CI/CD automatizado no GitHub Actions
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
│   └── assets/                  # Arquivos estáticos (CSS, imagens, scripts)
├── schemas/                     # JSON Schemas formais
├── src/
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
    └── run_tests.php            # Test runner determinístico
```

---

## 4. Regras do Servidor Web (`.htaccess` para Apache/LiteSpeed)

O arquivo [`public/.htaccess`](../../public/.htaccess) implementa cinco camadas essenciais de infraestrutura:

1. **Bloqueio de Arquivos Ocultos e Dotfiles:**
   `RewriteRule ^\..* - [F,L]` impede expressamente qualquer acesso a arquivos como `.git`, `.env`, `.gitignore` ou diretórios ocultos.
2. **Canonicalização de HTTPS:**
   Redireciona requisições HTTP para conexões seguras HTTPS (com status 301), preservando compatibilidade com ambientes locais de desenvolvimento (`localhost`).
3. **Normalização de Trailing Slash:**
   `RewriteRule ^(.*)/$ /$1 [L,R=301]` remove barras finais excedentes em rotas virtuais, evitando duplicidade de páginas perante mecanismos de busca (Googlebot).
4. **Front Controller Pattern:**
   Preserva arquivos estáticos e diretórios físicos existentes (`RewriteCond %{REQUEST_FILENAME} !-f` e `RewriteCond %{REQUEST_FILENAME} !-d`), encaminhando requisições dinâmicas para `index.php?route=$1`.
5. **Headers de Segurança:**
   Configuração de `X-Content-Type-Options: nosniff`, `X-Frame-Options: SAMEORIGIN`, `X-XSS-Protection` e `Referrer-Policy: strict-origin-when-cross-origin`.

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
- **Total de Testes da Aplicação:** 45 testes unitários (`45/45 PASS`)
  - `RouterTest`: 6 testes cobrindo rotas exatas, rotas com parâmetros `{slug}`, normalização de trailing slash, 404 e sanitização contra path traversal.
  - `HtaccessConfigTest`: 6 testes validando a existência do arquivo, RewriteEngine, Front Controller, HTTPS, normalização e robots.txt.
  - `ViewsStructureTest`: 4 testes assegurando a integridade de todos os partials, páginas, conformidade do footer com a OAB e ausência de links `.php` na navbar.
- **Pipeline de Integração Contínua:** Verificado no GitHub Actions via `.github/workflows/ci.yml`.
