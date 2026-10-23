"""Gera cópias autônomas do protótipo (WDLC 3.2) e do catálogo de componentes (WDLC 3.3):
um único .html cada, com o CSS e as imagens embutidos.

Abrem com dois cliques no navegador, sem servidor, sem as pastas assets/ e sem os .css locais
(componentes.css, revisao.css, design-system/tokens.css).
Uso (na raiz do projeto):  python docs/fase-3-design-system/wdlc-3.2-mockups/build-standalone.py
"""
import base64
import re
from pathlib import Path

HERE = Path(__file__).parent
ROOT = HERE.parents[2]
MIME = {'.webp': 'image/webp', '.jpg': 'image/jpeg', '.jpeg': 'image/jpeg', '.png': 'image/png', '.svg': 'image/svg+xml'}
IMG = re.compile(r'src="([^"$:]+\.(?:webp|jpe?g|png|svg))"')


def data_uri(path):
    return f'data:{MIME[path.suffix.lower()]};base64,' + base64.b64encode(path.read_bytes()).decode()


def inline_css(path):
    """Conteúdo de uma folha de estilo, com os @import locais resolvidos (o das Google Fonts fica)."""
    css = path.read_text(encoding='utf-8')
    local = re.compile(r'@import url\("([^":]+)"\);\n?')
    return local.sub(lambda m: inline_css((path.parent / m.group(1)).resolve()) + '\n', css)


def build(src, out):
    base = src.parent
    html = src.read_text(encoding='utf-8')

    # Folhas de estilo locais (<link rel="stylesheet" href="../...css">)
    html = re.sub(r'<link rel="stylesheet" href="([^":]+\.css)">',
                  lambda m: f'<style>\n{inline_css(base / m.group(1))}</style>', html)
    assert '.css"' not in html, 'ainda há folha de estilo local não embutida'

    # Fotos dos artigos do protótipo, montadas pelo JS a partir do nome do arquivo
    template = 'src="assets/artigos/${r.img}.jpg"'
    if template in html:
        articles = {p.stem: data_uri(p) for p in sorted((base / 'assets/artigos').glob('*.jpg'))}
        html = html.replace(template, 'src="${ARTICLE_IMG[r.img]}"')
        html = html.replace('<script>', '<script>\nconst ARTICLE_IMG = ' + repr(articles).replace("'", '"') + ';', 1)

    # Demais imagens referenciadas no HTML (src="assets/..." ou src="../wdlc-3.2-mockups/assets/...")
    html = IMG.sub(lambda m: f'src="{data_uri(base / m.group(1))}"', html)
    assert 'assets/' not in re.sub(r'<!--.*?-->', '', html, flags=re.S), 'ainda há referência a assets/'

    if not html.lstrip().lower().startswith('<!doctype'):
        # Fragmento (formato do protótipo): o que vem antes da barra de revisão vai para o <head>
        marker = '<!-- ======= Barra de revisão ======= -->'
        head, body = html.split(marker, 1)
        head = head.replace('<meta charset="utf-8">', '').strip()
        html = (
            '<!doctype html>\n<html lang="pt-BR">\n<head>\n<meta charset="utf-8">\n'
            '<meta name="viewport" content="width=device-width, initial-scale=1">\n'
            f'{head}\n</head>\n<body>\n{marker}{body}\n</body>\n</html>\n'
        )
    out.write_text(html, encoding='utf-8')
    print(f'{out} ({out.stat().st_size // 1024} KB)')


build(HERE / 'prototipo.html', ROOT / 'prototipo-souza-guedes.html')
build(HERE.parent / 'wdlc-3.3-componentes/catalogo.html', ROOT / 'catalogo-componentes-souza-guedes.html')
