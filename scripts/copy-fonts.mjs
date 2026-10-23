// Copia as fontes do site (WOFF2, subconjunto latin, só os pesos usados) dos pacotes @fontsource
// para public/assets/fonts/, com a licença OFL de cada família. Os arquivos ficam versionados, como o
// main.css, porque a hospedagem não roda Node. Os @font-face estão em resources/css/fonts.css.
//   npm run fonts
import { copyFileSync, mkdirSync } from 'node:fs';
import { join } from 'node:path';
import { root } from './audit-pages.mjs';

const families = {
  cinzel: [400, 500],
  'plus-jakarta-sans': [500, 600, 700],
  'source-sans-3': [400, 600],
};

const out = join(root, 'public/assets/fonts');
mkdirSync(out, { recursive: true });

for (const [family, weights] of Object.entries(families)) {
  const pkg = join(root, 'node_modules/@fontsource', family);
  for (const weight of weights) {
    const file = `${family}-latin-${weight}-normal.woff2`;
    copyFileSync(join(pkg, 'files', file), join(out, file));
  }
  copyFileSync(join(pkg, 'LICENSE'), join(out, `${family}-OFL.txt`));
}
console.log(`fontes copiadas para ${out}`);
