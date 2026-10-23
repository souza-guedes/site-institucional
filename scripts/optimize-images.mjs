// Gera as imagens do site em WebP, nos tamanhos usados pelos srcset das views (WDLC 6.1).
// Originais em resources/img/ (fora de public/); saída em public/assets/img/, versionada.
// O maior tamanho fica com o nome-base (ex.: lays.webp), que é o caminho gravado em data/;
// os menores ganham o sufixo da largura (lays-400.webp). Ver srcset() em src/Views/helpers.php.
//   npm run images
import { copyFileSync, mkdirSync, statSync } from 'node:fs';
import { dirname, join } from 'node:path';
import sharp from 'sharp';
import { root } from './audit-pages.mjs';

/** Original => larguras geradas (a primeira é a do nome-base). */
const images = {
  'lays.webp': [800, 400, 104],
  'rodrigo.webp': [800, 400, 104],
  'socios-palacio.jpg': [1000, 720, 480],
  'artigos/inventario.jpg': [900, 720, 480],
  'artigos/medicamento.jpg': [900, 720, 480],
  'artigos/usucapiao.jpg': [900, 720, 480],
};

const quality = 78;
let before = 0;
let after = 0;

for (const [source, widths] of Object.entries(images)) {
  const input = join(root, 'resources/img', source);
  const base = source.replace(/\.(jpe?g|png|webp)$/, '');
  before += statSync(input).size;
  for (const [index, width] of widths.entries()) {
    const output = join(root, 'public/assets/img', `${base}${index === 0 ? '' : `-${width}`}.webp`);
    mkdirSync(dirname(output), { recursive: true });
    const info = await sharp(input).resize({ width, withoutEnlargement: true }).webp({ quality, effort: 6 }).toFile(output);
    if (index === 0) after += info.size;
    console.log(`${output.slice(root.length + 1)}  ${info.width}x${info.height}  ${(info.size / 1024).toFixed(1)} KB`);
  }
}
console.log(`tamanho-base: ${(before / 1024).toFixed(0)} KB nos originais, ${(after / 1024).toFixed(0)} KB em WebP`);

// Favicon: o SVG vai como está; o PNG 180 px atende o ícone de tela inicial do iOS.
copyFileSync(join(root, 'resources/img/favicon.svg'), join(root, 'public/favicon.svg'));
await sharp(join(root, 'resources/img/favicon.svg'), { density: 300 }).resize(180).png().toFile(join(root, 'public/apple-touch-icon.png'));
console.log('public/favicon.svg e public/apple-touch-icon.png');
