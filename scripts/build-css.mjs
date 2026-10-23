// Monta a entrada do Tailwind (resources/css/app.generated.css) a partir do design system.
//
// - design-system/tokens.css: variáveis de cor, tipo, espaçamento e raio;
// - design-system/componentes.css: CSS dos componentes, o mesmo do protótipo e do catálogo.
//   As container queries do contêiner "site" viram media queries nos mesmos pontos
//   (sm 560 · md 760 · lg 1100), porque no site a largura de referência é a da janela.
//
// O CSS dos componentes fica fora de @layer para não ser podado pelo Tailwind (várias classes
// só aparecem por JS, como .is-open e .is-stuck). As utilities vêm por último e podem sobrescrevê-lo.
import { readFileSync, writeFileSync, mkdirSync } from 'node:fs';
import { dirname, join } from 'node:path';
import { fileURLToPath } from 'node:url';

const root = join(dirname(fileURLToPath(import.meta.url)), '..');
const read = (path) => readFileSync(join(root, path), 'utf8').replace(/\r\n/g, '\n');

const tokens = read('design-system/tokens.css');
const components = read('design-system/componentes.css')
  .replace(/@import url\("tokens\.css"\);\n?/, '')
  .replace(/@container site \(/g, '@media (');

if (/@container|@import/.test(components)) {
  throw new Error('componentes.css ainda tem @container ou @import depois da conversão; ajuste scripts/build-css.mjs.');
}

const out = join(root, 'resources/css/app.generated.css');
mkdirSync(dirname(out), { recursive: true });
writeFileSync(out, [
  '/* Gerado por scripts/build-css.mjs. Não editar: edite design-system/tokens.css e design-system/componentes.css. */',
  '@tailwind base;',
  tokens,
  components,
  '@tailwind utilities;',
  '',
].join('\n'));
console.log(`entrada do Tailwind gerada em ${out}`);
