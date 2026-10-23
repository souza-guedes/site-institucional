// Auditoria Lighthouse da WDLC 6.1 (issue #17): mobile (padrão, com throttling simulado) e desktop.
//   npm run audit:lighthouse [-- --out=<arquivo de resumo>]
// Relatórios HTML completos em reports/lighthouse/ (fora do Git); resumo em docs/fase-6-testes-qa/.
import { mkdirSync, writeFileSync } from 'node:fs';
import { join } from 'node:path';
import * as chromeLauncher from 'chrome-launcher';
import lighthouse from 'lighthouse';
import desktopConfig from 'lighthouse/core/config/desktop-config.js';
import { assertServerUp, baseUrl, pages, root } from './audit-pages.mjs';

const outArg = process.argv.find((arg) => arg.startsWith('--out='));
const summaryFile = join(root, outArg ? outArg.slice(6) : 'docs/fase-6-testes-qa/lighthouse-resumo.json');
const reportsDir = join(root, 'reports/lighthouse');
mkdirSync(reportsDir, { recursive: true });

await assertServerUp();

const categories = ['performance', 'accessibility', 'best-practices', 'seo'];
const metrics = {
  lcp: 'largest-contentful-paint',
  fcp: 'first-contentful-paint',
  cls: 'cumulative-layout-shift',
  tbt: 'total-blocking-time',
};

const chrome = await chromeLauncher.launch({ chromeFlags: ['--headless=new'] });
const results = [];
try {
  for (const formFactor of ['mobile', 'desktop']) {
    for (const page of pages) {
      const config = formFactor === 'desktop' ? desktopConfig : undefined;
      const { lhr, report } = await lighthouse(baseUrl + page, {
        port: chrome.port,
        output: 'html',
        logLevel: 'error',
        onlyCategories: categories,
      }, config);

      const name = `${formFactor}${page === '/' ? '-inicio' : page.replaceAll('/', '-')}`;
      writeFileSync(join(reportsDir, `${name}.html`), report);

      const row = { page, formFactor };
      for (const category of categories) row[category] = Math.round(lhr.categories[category].score * 100);
      for (const [key, audit] of Object.entries(metrics)) row[key] = lhr.audits[audit].numericValue;
      row.lcpElement = lhr.audits['lcp-breakdown-insight']?.details?.items?.find((item) => item.type === 'node')?.selector ?? null;
      // Auditorias abaixo de 0,9 que pesam na nota de alguma categoria: o que falta corrigir.
      const weighted = new Set(categories.flatMap((c) => lhr.categories[c].auditRefs.filter((r) => r.weight > 0).map((r) => r.id)));
      row.failed = Object.values(lhr.audits)
        .filter((a) => weighted.has(a.id) && a.score !== null && a.score < 0.9)
        .map((a) => a.id);
      results.push(row);

      console.log(`${formFactor.padEnd(7)} ${page.padEnd(62)} P${row.performance} A${row.accessibility} BP${row['best-practices']} SEO${row.seo} LCP ${(row.lcp / 1000).toFixed(2)}s CLS ${row.cls.toFixed(3)}`);
    }
  }
} finally {
  await chrome.kill();
}

writeFileSync(summaryFile, JSON.stringify({
  generatedAt: new Date().toISOString(),
  lighthouse: (await import('lighthouse/package.json', { with: { type: 'json' } })).default.version,
  baseUrl,
  results,
}, null, 2) + '\n');
console.log(`resumo em ${summaryFile}`);
