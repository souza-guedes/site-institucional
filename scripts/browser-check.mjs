// Compatibilidade da WDLC 6.1 (issue #17): Chrome, Edge, Firefox e WebKit (motor do Safari) via Playwright,
// em 390 px e 1440 px. Em cada página: sem erro no console, sem rolagem horizontal, fontes e imagens
// carregadas; em 390 px, a gaveta abre e fecha com Esc.
//   npm run audit:browsers [-- --out=<arquivo de resumo>]
// Capturas em reports/browsers/ (fora do Git); resumo em docs/fase-6-testes-qa/.
import { mkdirSync, writeFileSync } from 'node:fs';
import { join } from 'node:path';
import { chromium, firefox, webkit } from '@playwright/test';
import { assertServerUp, baseUrl, pages, root } from './audit-pages.mjs';

const outArg = process.argv.find((arg) => arg.startsWith('--out='));
const summaryFile = join(root, outArg ? outArg.slice(6) : 'docs/fase-6-testes-qa/navegadores-resumo.json');
const shotsDir = join(root, 'reports/browsers');
mkdirSync(shotsDir, { recursive: true });

await assertServerUp();

const engines = [
  ['chrome', chromium, { channel: 'chrome' }],
  ['edge', chromium, { channel: 'msedge' }],
  ['firefox', firefox, {}],
  ['webkit', webkit, {}],
];
const widths = [390, 1440];
const fonts = ['500 16px Cinzel', '600 16px "Plus Jakarta Sans"', '400 16px "Source Sans 3"'];

const results = [];
let failures = 0;

for (const [name, type, options] of engines) {
  const browser = await type.launch(options);
  const version = browser.version();
  for (const width of widths) {
    const context = await browser.newContext({ viewport: { width, height: width < 768 ? 844 : 900 } });
    for (const path of pages) {
      const page = await context.newPage();
      page.setDefaultTimeout(30000);
      const errors = [];
      page.on('console', (msg) => { if (msg.type() === 'error') errors.push(msg.text()); });
      page.on('pageerror', (err) => errors.push(err.message));

      await page.goto(baseUrl + path, { waitUntil: 'load' });
      // Imagens com loading="lazy" só carregam perto da tela: rola até o fim antes de conferir.
      await page.evaluate(async () => {
        for (let y = 0; y < document.body.scrollHeight; y += innerHeight / 2) {
          scrollTo(0, y);
          await new Promise((r) => setTimeout(r, 60));
        }
        scrollTo(0, 0);
        // Só as imagens exibidas (as escondidas por filtro nunca carregam), com limite de 5 s.
        const shown = [...document.images].filter((img) => img.getClientRects().length > 0);
        await Promise.race([
          Promise.all(shown.map((img) => img.complete ? null : new Promise((r) => { img.onload = img.onerror = r; }))),
          new Promise((r) => setTimeout(r, 5000)),
        ]);
        await document.fonts.ready;
      });

      const check = await page.evaluate((fontList) => ({
        overflow: document.documentElement.scrollWidth - document.documentElement.clientWidth,
        missingFonts: fontList.filter((font) => !document.fonts.check(font)),
        brokenImages: [...document.images].filter((img) => img.getClientRects().length > 0 && img.naturalWidth === 0).map((img) => img.currentSrc || img.src),
      }), fonts);

      let drawer = null;
      if (width < 768) {
        const toggle = page.locator('#menu-btn').first();
        if (await toggle.count()) {
          await toggle.click();
          const opened = await toggle.getAttribute('aria-expanded') === 'true' && await page.locator('#mnav').isVisible();
          await page.keyboard.press('Escape');
          await page.waitForTimeout(400);
          const closed = await toggle.getAttribute('aria-expanded') === 'false';
          drawer = opened && closed;
        }
      }

      const file = `${name}-${width}${path === '/' ? '-inicio' : path.replaceAll('/', '-')}.png`;
      await page.screenshot({ path: join(shotsDir, file), fullPage: true });

      const ok = errors.length === 0 && check.overflow <= 0 && check.missingFonts.length === 0
        && check.brokenImages.length === 0 && drawer !== false;
      if (!ok) failures++;
      results.push({ browser: name, version, width, path, ok, errors, ...check, drawer });
      console.log(`${ok ? 'ok  ' : 'FALHA'} ${name.padEnd(8)} ${String(width).padEnd(5)} ${path}`
        + (ok ? '' : ` ${JSON.stringify({ errors, ...check, drawer })}`));
      await page.close();
    }
    await context.close();
  }
  await browser.close();
}

writeFileSync(summaryFile, JSON.stringify({ generatedAt: new Date().toISOString(), baseUrl, results }, null, 2) + '\n');
console.log(`resumo em ${summaryFile}: ${results.length - failures}/${results.length} sem falhas`);
process.exitCode = failures ? 1 : 0;
