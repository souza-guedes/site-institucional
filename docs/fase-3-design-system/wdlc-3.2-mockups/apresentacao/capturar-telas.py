"""Captura cada tela do protótipo em página inteira (desktop 1440px e mobile 390px) para a apresentação.

Usa o Chrome instalado em modo headless sobre o protótipo autônomo (prototipo-souza-guedes.html),
escondendo a barra de revisão. Uso (na raiz do projeto):
    python docs/fase-3-design-system/wdlc-3.2-mockups/apresentacao/capturar-telas.py
"""
import subprocess
import tempfile
from pathlib import Path

from PIL import Image

HERE = Path(__file__).parent
ROOT = HERE.parents[3]
SRC = ROOT / 'prototipo-souza-guedes.html'
OUT = HERE / 'telas'
CHROME = r'C:\Program Files\Google\Chrome\Application\chrome.exe'
KEY = (255, 0, 255)  # cor do palco, usada para recortar o fim da página

# (id da tela no protótipo, nome do arquivo)
SCREENS = [
    ('inicio', 'inicio'),
    ('sobre', 'sobre'),
    ('areas-de-atuacao', 'areas'),
    ('area-direito-imobiliario', 'area-imobiliario'),
    ('advogados', 'advogados'),
    ('artigos', 'artigos'),
    ('artigo-usucapiao-extrajudicial', 'artigo'),
    ('contato', 'contato'),
    ('privacidade', 'privacidade'),
    ('404', '404'),
]
VP = {'desktop': (1440, 1, 9000), 'mobile': (390, 2, 8000)}  # largura, escala, altura máxima do viewport (px CSS)

STYLE = ('<style>.review,.notes,.cookie{display:none!important}html,body,.stage{background:#ff00ff!important;margin:0!important}'
         '.stage{padding:0!important;display:block!important}'
         '.frame{box-shadow:none!important;border:0!important;border-radius:0!important;margin:0!important}</style>')


def capture(html, screen, vp, extra_js=''):
    width, scale, height = VP[vp]
    js = (f'window.fit=function(){{frame.style.zoom=""}};setVP("{vp}");frame.style.setProperty("--hdr-top","0px");'
          f'location.hash="{screen}";route();setTimeout(()=>{{{extra_js}}},400);')
    page = html.replace('</head>', STYLE + '</head>', 1).replace('</body>', f'<script>{js}</script></body>')
    with tempfile.TemporaryDirectory() as tmp:
        f = Path(tmp) / 'p.html'
        png = Path(tmp) / 's.png'
        f.write_text(page, encoding='utf-8')
        subprocess.run([CHROME, '--headless=new', '--disable-gpu', '--hide-scrollbars', f'--user-data-dir={tmp}',
                        f'--force-device-scale-factor={scale}', f'--window-size={width + 40},{height}',  # folga: o protótipo reduz o quadro se faltar margem
                        '--virtual-time-budget=5000', f'--screenshot={png}', f.as_uri()],
                       check=True, capture_output=True, timeout=120)
        im = Image.open(png).convert('RGB')
    # recorta o palco magenta abaixo do fim da página
    px = im.load()
    y = im.height - 1
    while y > 0 and px[im.width // 2, y] == KEY:
        y -= 1
    # o quadro do site começa em x=0; descarta qualquer sobra à direita
    return im.crop((0, 0, min(im.width, width * scale), y + 1))


def save(im, name):
    path = OUT / f'{name}.jpg'
    im.save(path, quality=82, optimize=True, progressive=True)
    print(f'{path.name:28} {im.width}x{im.height}  {path.stat().st_size // 1024} KB')


OUT.mkdir(exist_ok=True)
html = SRC.read_text(encoding='utf-8')
import sys
ONLY = sys.argv[1:] or list(VP)  # ex.: capturar-telas.py desktop
for screen, name in SCREENS:
    for vp in ONLY:
        save(capture(html, screen, vp), f'{name}-{vp}')
# estado extra: menu mobile aberto (só a primeira dobra do celular)
if 'mobile' in ONLY:
  menu = capture(html, 'inicio', 'mobile', 'setMenu(true);')
  save(menu.crop((0, 0, menu.width, 844 * VP['mobile'][1])), 'menu-mobile')
