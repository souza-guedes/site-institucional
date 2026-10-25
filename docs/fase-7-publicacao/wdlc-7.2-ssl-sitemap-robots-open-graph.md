# WDLC 7.2 — SSL, sitemap.xml, robots.txt e Open Graph

> **Status:** Implementada. Falta conferir em produção após o deploy e enviar o sitemap ao Google (seção 6)
> **Fase:** 7 — Deploy e lançamento
> **Sub-issue:** [#21](https://github.com/souza-guedes/site-institucional/issues/21)
> **Épico Relacionado:** [#19](https://github.com/souza-guedes/site-institucional/issues/19)
> **Domínio:** `https://souzaguedes.adv.br`

---

## 1. Resultado

| Item da issue | Situação |
| --- | --- |
| Certificado SSL gratuito | Já ativo pelo hPanel: Let's Encrypt, para `souzaguedes.adv.br` e `www.souzaguedes.adv.br`, válido até 22/01/2027 com renovação automática da Hostinger |
| Redirecionamento para HTTPS no `.htaccess` | Já existia (WDLC 2.1). Agora também leva `www` para o domínio sem `www` e envia HSTS |
| `sitemap.xml` com rotas e prioridades | Rota `/sitemap.xml`, gerada a partir de `data/` |
| `robots.txt` apontando para o sitemap | Corrigido: apontava para `souzaguedesadv.com.br`, domínio que não existe |
| Open Graph para WhatsApp e LinkedIn | `og:image` 1200×630 em JPEG, `og:image:alt` e tags `twitter:*` em todas as páginas |

## 2. HTTPS e domínio canônico (`public/.htaccess`)

- **SSL:** nada a fazer no repositório. O certificado é emitido e renovado pela Hostinger. Conferência em 24/10/2026:
  `openssl s_client -connect souzaguedes.adv.br:443` → emissor Let's Encrypt (YE2), SAN `souzaguedes.adv.br, www.souzaguedes.adv.br`.
- **HTTP → HTTPS:** a regra da WDLC 2.1 continua igual, com a exceção para `localhost`.
- **www → sem www:** antes, `https://www.souzaguedes.adv.br` respondia 200 com o canonical apontando para o próprio `www`, gerando páginas duplicadas para o Google. Agora responde 301 para `https://souzaguedes.adv.br`, mantendo o caminho.
- **HSTS:** `Strict-Transport-Security: max-age=31536000`, só em respostas HTTPS. Fica sem `preload`, para não prender o domínio à lista dos navegadores.

## 3. sitemap.xml

- **Por que é rota, e não arquivo:**
  - o deploy usa `rsync --delete`, então um arquivo gerado no servidor seria apagado;
  - um arquivo commitado teria de ser atualizado à mão.
  A rota `/sitemap.xml` (`src/Controllers/SitemapController.php`) monta o mapa a cada acesso.
- **Fontes:**
  - as rotas de `data/sitemap-taxonomy.json`;
  - as áreas de `data/firm-profile.json`;
  - os artigos de `data/articles.json`.
- **Rascunhos ficam de fora.** Hoje os três artigos estão como `rascunho`. Cada um passa a constar no mapa quando o `status` mudar.
- **URLs:** usam o mesmo `AppConfig::getCanonicalUrl()` do `<link rel="canonical">`.
- **Endereço temporário:** no `*.hostingersite.com`, o sitemap sai com `X-Robots-Tag: noindex`, como as páginas.
- **Prioridades:**

  | Página | Prioridade |
  | --- | --- |
  | Início | 1.0 |
  | Áreas de atuação e cada área | 0.9 |
  | Sobre, Advogados, Artigos, Contato e artigos publicados | 0.7 |
  | Privacidade | 0.3 |

- **`lastmod`:** `updated_at` do artigo ou `last_updated` da taxonomia.

## 4. robots.txt

```
User-agent: *
Allow: /
Disallow: /tests/
Disallow: /schemas/
Disallow: /data/
Disallow: /src/

Sitemap: https://souzaguedes.adv.br/sitemap.xml
```

Essas pastas nem são acessíveis pela web (o deploy publica só `public/`). O `Disallow` é uma segunda barreira, para o caso de a estrutura mudar.

## 5. Open Graph e Twitter (`src/Views/partials/head.php`)

- **Imagem:** `public/assets/img/og-default.jpg`, com 1200×630 e cerca de 70 KB. É um recorte da foto dos sócios, gerado por `npm run images` (`scripts/optimize-images.mjs`). Usa JPEG, e não WebP, porque o WhatsApp e o LinkedIn nem sempre leem WebP.
- **Tags:**
  - `og:image` (URL absoluta), `og:image:type`, `og:image:width`, `og:image:height` e `og:image:alt`;
  - `twitter:card=summary_large_image`, `twitter:title`, `twitter:description`, `twitter:image` e `twitter:image:alt`.
- **`og:type`:** `article` nos artigos e `website` nas demais páginas.

## 6. Conferência após o deploy

O teste de fumaça do deploy (`.github/workflows/deploy.yml`) agora também exige:
- `/robots.txt` respondendo 200 como `text/plain`;
- `/sitemap.xml` respondendo 200 como `application/xml`;
- `/assets/img/og-default.jpg` respondendo 200 como `image/jpeg`.

À mão:

```bash
curl -sI http://souzaguedes.adv.br/sobre            # 301 → https://souzaguedes.adv.br/sobre
curl -sI https://www.souzaguedes.adv.br/sobre       # 301 → https://souzaguedes.adv.br/sobre
curl -sI https://souzaguedes.adv.br/ | grep -i strict-transport-security
curl -s  https://souzaguedes.adv.br/sitemap.xml
```

Validação externa, a fazer depois do deploy:
1. **Google Search Console:** adicionar a propriedade `souzaguedes.adv.br` e enviar `https://souzaguedes.adv.br/sitemap.xml`.
2. **Facebook Sharing Debugger** (vale também para o WhatsApp): conferir a home e um artigo, e usar "Scrape Again" para limpar o cache antigo sem imagem.
3. **LinkedIn Post Inspector:** conferir a home.

## 7. Testes

- **`tests/Unit/SitemapTest.php`:**
  - o XML é válido e sai com o Content-Type certo;
  - todas as páginas públicas aparecem em URL absoluta;
  - os rascunhos ficam de fora;
  - o endereço temporário sai com `noindex`.
- **`tests/Unit/HtaccessConfigTest.php`:** a regra `www` → sem `www`, o HSTS e o `Sitemap:` do `robots.txt` com o domínio certo.
- **`tests/Unit/PageRenderingTest.php`:** em cada página, as tags Open Graph e Twitter, o `og:type` e o tamanho da imagem.
