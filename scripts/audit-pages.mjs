// Páginas auditadas pela WDLC 6.1 (Lighthouse e navegadores): uma de cada view, com a área e o
// artigo de exemplo tirados de data/. O servidor tem de estar no ar:
//   php -S localhost:8080 -t public scripts/dev-router.php
import { readFileSync } from 'node:fs';
import { dirname, join } from 'node:path';
import { fileURLToPath } from 'node:url';

export const root = join(dirname(fileURLToPath(import.meta.url)), '..');
export const baseUrl = process.env.AUDIT_BASE_URL ?? 'http://localhost:8080';

const json = (path) => JSON.parse(readFileSync(join(root, path), 'utf8'));
const area = json('data/firm-profile.json').practice_areas[0].slug;
const article = json('data/articles.json').articles[0].slug;

export const pages = [
  '/',
  '/sobre',
  '/areas-de-atuacao',
  `/areas-de-atuacao/${area}`,
  '/advogados',
  '/artigos',
  `/artigos/${article}`,
  '/contato',
  '/privacidade',
];

export async function assertServerUp() {
  try {
    await (await fetch(baseUrl)).text();
  } catch {
    console.error(`Servidor fora do ar em ${baseUrl}. Rode: php -S localhost:8080 -t public scripts/dev-router.php`);
    process.exit(1);
  }
}
