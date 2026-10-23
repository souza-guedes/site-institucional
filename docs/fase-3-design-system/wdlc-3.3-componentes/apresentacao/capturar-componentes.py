"""Captura cada componente do catálogo em página inteira (desktop 1440px e mobile 390px) para a apresentação.

Usa o Chrome instalado em modo headless sobre o catálogo autônomo (catalogo-componentes-souza-guedes.html),
escondendo a barra de revisão. Rode antes o build-standalone.py da WDLC 3.2. Uso (na raiz do projeto):
    python docs/fase-3-design-system/wdlc-3.3-componentes/apresentacao/capturar-componentes.py [desktop|mobile]
"""
import subprocess
import sys
import tempfile
from pathlib import Path

from PIL import Image

HERE = Path(__file__).parent
ROOT = HERE.parents[3]
SRC = ROOT / 'catalogo-componentes-souza-guedes.html'
OUT = HERE / 'telas'
CHROME = r'C:\Program Files\Google\Chrome\Application\chrome.exe'
KEY = (255, 0, 255)  # cor do palco, usada para recortar o fim da página

COMPONENTS = ['cor-tipografia', 'acoes', 'marca', 'navbar', 'hero', 'area', 'advogado', 'artigo',
              'formulario', 'atendimento', 'rodape']
VP = {'desktop': (1440, 1, 12000), 'mobile': (390, 2, 16000)}  # largura, escala, altura máxima do viewport (px CSS)

STYLE = ('<style>.review,.notes{display:none!important}html,body,.stage{background:#ff00ff!important;margin:0!important}'
         '.stage{padding:0!important;display:block!important}'
         '.frame{box-shadow:none!important;border:0!important;border-radius:0!important;margin:0!important}</style>')


def capture(html, comp, vp):
    width, scale, height = VP[vp]
    js = f'window.fit=function(){{frame.style.zoom=""}};setVP("{vp}");location.hash="{comp}";route();'
    page = html.replace('</head>', STYLE + '</head>', 1).replace('</body>', f'<script>{js}</script></body>')
    with tempfile.TemporaryDirectory() as tmp:
        f = Path(tmp) / 'p.html'
        png = Path(tmp) / 's.png'
        f.write_text(page, encoding='utf-8')
        subprocess.run([CHROME, '--headless=new', '--disable-gpu', '--hide-scrollbars', f'--user-data-dir={tmp}',
                        f'--force-device-scale-factor={scale}', f'--window-size={width + 40},{height}',
                        '--virtual-time-budget=5000', f'--screenshot={png}', f.as_uri()],
                       check=True, capture_output=True, timeout=180)
        im = Image.open(png).convert('RGB')
    px = im.load()
    y = im.height - 1
    while y > 0 and px[im.width // 2, y] == KEY:
        y -= 1
    return im.crop((0, 0, min(im.width, width * scale), y + 1))


OUT.mkdir(exist_ok=True)
html = SRC.read_text(encoding='utf-8')
for i, comp in enumerate(COMPONENTS, 1):
    for vp in sys.argv[1:] or list(VP):
        im = capture(html, comp, vp)
        path = OUT / f'{i:02d}-{comp}-{vp}.jpg'
        im.save(path, quality=82, optimize=True, progressive=True)
        print(f'{path.name:32} {im.width}x{im.height}  {path.stat().st_size // 1024} KB')
