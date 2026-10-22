"""Gera uma cópia autônoma do protótipo: um único .html com o CSS e as imagens embutidos.

Abre com dois cliques no navegador, sem servidor, sem a pasta assets/ e sem o componentes.css
(WDLC 3.3) nem o design-system/tokens.css.
Uso (na raiz do projeto):  python docs/fase-3-design-system/wdlc-3.2-mockups/build-standalone.py
"""
import base64
import re
from pathlib import Path

HERE = Path(__file__).parent
SRC = HERE / 'prototipo.html'
OUT = HERE.parents[2] / 'prototipo-souza-guedes.html'
MIME = {'.webp': 'image/webp', '.jpg': 'image/jpeg', '.jpeg': 'image/jpeg', '.png': 'image/png', '.svg': 'image/svg+xml'}


def data_uri(rel):
    path = HERE / rel
    return f'data:{MIME[path.suffix.lower()]};base64,' + base64.b64encode(path.read_bytes()).decode()


def inline_css(path):
    """Conteúdo de uma folha de estilo, com os @import locais resolvidos (o das Google Fonts fica)."""
    css = path.read_text(encoding='utf-8')
    local = re.compile(r'@import url\("([^":]+)"\);\n?')
    return local.sub(lambda m: inline_css((path.parent / m.group(1)).resolve()) + '\n', css)


html = SRC.read_text(encoding='utf-8')

# Folhas de estilo locais (<link rel="stylesheet" href="../...css">)
html = re.sub(r'<link rel="stylesheet" href="([^":]+\.css)">',
              lambda m: f'<style>\n{inline_css(HERE / m.group(1))}</style>', html)
assert '.css"' not in html, 'ainda há folha de estilo local não embutida'

# Imagens referenciadas diretamente no HTML (src="assets/...")
html = re.sub(r'src="(assets/[^"$]+)"', lambda m: f'src="{data_uri(m.group(1))}"', html)

# Fotos dos artigos, montadas pelo JS a partir do nome do arquivo
articles = {p.stem: data_uri(p.relative_to(HERE)) for p in sorted((HERE / 'assets/artigos').glob('*.jpg'))}
template = 'src="assets/artigos/${r.img}.jpg"'
assert template in html, 'template das fotos dos artigos mudou; atualize este script'
html = html.replace(template, 'src="${ARTICLE_IMG[r.img]}"')
html = html.replace('<script>', '<script>\nconst ARTICLE_IMG = ' + repr(articles).replace("'", '"') + ';', 1)

assert 'assets/' not in re.sub(r'<!--.*?-->', '', html, flags=re.S), 'ainda há referência a assets/'

# Documento completo: o que vem antes da barra de revisão vai para o <head>
marker = '<!-- ======= Barra de revisão ======= -->'
head, body = html.split(marker, 1)
head = head.replace('<meta charset="utf-8">', '').strip()
doc = (
    '<!doctype html>\n<html lang="pt-BR">\n<head>\n<meta charset="utf-8">\n'
    '<meta name="viewport" content="width=device-width, initial-scale=1">\n'
    f'{head}\n</head>\n<body>\n{marker}{body}\n</body>\n</html>\n'
)
OUT.write_text(doc, encoding='utf-8')
print(f'{OUT} ({OUT.stat().st_size // 1024} KB)')
