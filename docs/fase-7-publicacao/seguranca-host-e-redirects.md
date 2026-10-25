# Segurança: cabeçalho Host, redirects e X-Powered-By

> **Status:** Implementada. Falta conferir em produção após o deploy (seção 4)
> **Fase:** 7 — Deploy e lançamento
> **Issue:** [#31](https://github.com/souza-guedes/site-institucional/issues/31)
> **Épico Relacionado:** [#19](https://github.com/souza-guedes/site-institucional/issues/19)

---

## 1. Situação em produção antes da correção (24/10/2026)

| Achado | Em `souzaguedes.adv.br` |
| --- | --- |
| `Host` forjado no canonical e no `og:url` | Não chegava ao site. O CDN da Hostinger (`hcdn`) roteia pelo `Host`: em HTTPS derruba a conexão, e em HTTP devolve a página "Parked Domain". O código, porém, confiava no `Host`, e a regra de `www` da WDLC 7.2 (`^www\.(.+)$` → `https://%1`) seria um redirect aberto na origem |
| `//evil.example/` na regra de barra final | O LiteSpeed junta as barras e responde 301 para `https://souzaguedes.adv.br/evil.example`. Seguro, mas só por causa desse comportamento do servidor |
| `X-Powered-By: PHP/8.3.35` | Exposto. `expose_php` está ligado na hospedagem e não pode ser mudado por `.user.ini` |

A correção é defesa em profundidade: o site deixa de depender do CDN e do servidor para ficar seguro.

## 2. O que mudou

- **Domínio fixo** (`src/Config/AppConfig.php`):
  - `getBaseUrl()` devolve `AppConfig::PRODUCTION_URL` (`https://souzaguedes.adv.br`) para qualquer host que não seja local.
  - Só `localhost`, `127.0.0.1`, `[::1]` e `*.localhost`, com qualquer porta, continuam usando o host da requisição.
  - O canonical, o `og:url`, o `og:image` e o sitemap passam a usar a URL fixa.
  - No endereço temporário `*.hostingersite.com`, o canonical aponta para o domínio definitivo, e o `noindex` continua.
- **`www`** (`public/.htaccess`): só `www.souzaguedes.adv.br` é redirecionado, e o destino é fixo (`https://souzaguedes.adv.br/...`).
- **Barra final:** a regra passa a ser `^([^/].*)/$`. Um caminho que começa com `/` (o que acontece com `//outro-site/`) não é redirecionado.
- **`X-Powered-By`:** é removido de duas formas:
  - `header_remove('X-Powered-By')` em `public/index.php`;
  - `Header always unset X-Powered-By` no `.htaccess`.

## 3. Testes

- **`AppConfigTest`:** hosts forjados (`evil.example`, `www.evil.example`, `souzaguedes.adv.br.evil.example`, `localhost.evil.example`) e o host temporário caem no domínio fixo. Os hosts locais continuam dinâmicos.
- **`PageRenderingTest`:** com `Host: evil.example`, `/sobre` sai com canonical, `og:url` e `og:image` em `https://souzaguedes.adv.br`, sem `evil.example` no HTML.
- **`SitemapTest`:** o `Host` forjado não aparece no sitemap.
- **`HtaccessConfigTest`:**
  - o `www` tem destino fixo e nenhum redirect usa `%1`;
  - o padrão da barra final não casa com `/evil.example/` nem com `//evil.example/`, mas casa com `sobre/`;
  - o `X-Powered-By` é removido no `.htaccess` e no `index.php`.

## 4. Conferência após o deploy

```bash
curl -s -D - -o /dev/null https://souzaguedes.adv.br/ | grep -i x-powered-by                    # nada
curl -s -o /dev/null -w '%{http_code} %{redirect_url}\n' 'https://souzaguedes.adv.br//evil.example/'  # nunca Location para evil.example
curl -s -o /dev/null -w '%{http_code} %{redirect_url}\n' https://www.souzaguedes.adv.br/sobre        # 301 https://souzaguedes.adv.br/sobre
curl -s -o /dev/null -w '%{http_code} %{redirect_url}\n' https://souzaguedes.adv.br/sobre/           # 301 https://souzaguedes.adv.br/sobre
```

Na origem, pelo SSH, sem passar pelo CDN:

```bash
curl -s -o /dev/null -w '%{http_code} %{redirect_url}\n' -H 'Host: www.evil.example' http://127.0.0.1/sobre
```
