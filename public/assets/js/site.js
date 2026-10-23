/* Souza Guedes Advogados: comportamento das páginas (WDLC 5.1), em JavaScript sem dependências.
   Mesma lógica do protótipo da WDLC 3.2: gaveta mobile, header fixo, filtros, carrossel,
   botão "Copiar", validação do formulário e aviso de cookies. */
(() => {
  const $ = (s, r = document) => r.querySelector(s);
  const $$ = (s, r = document) => [...r.querySelectorAll(s)];

  // Região para avisos lidos por leitores de tela (ex.: "Copiado").
  const live = document.createElement('p');
  live.className = 'sr-only';
  live.setAttribute('aria-live', 'polite');
  document.body.append(live);
  const announce = (msg) => { live.textContent = ''; requestAnimationFrame(() => { live.textContent = msg; }); };

  /* ---------- Gaveta de navegação (abaixo de 1100px) ---------- */
  const hdr = $('.hdr'), menuBtn = $('#menu-btn'), scrim = $('#scrim');
  if (hdr && menuBtn && scrim) {
    const setMenu = (open) => {
      hdr.classList.toggle('is-open', open);
      scrim.classList.toggle('is-on', open);
      menuBtn.setAttribute('aria-expanded', String(open));
    };
    menuBtn.addEventListener('click', () => setMenu(!hdr.classList.contains('is-open')));
    scrim.addEventListener('click', () => setMenu(false));
    $$('.mnav a').forEach((a) => a.addEventListener('click', () => setMenu(false)));
    addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && hdr.classList.contains('is-open')) { setMenu(false); menuBtn.focus(); }
    });

    // Sombra do header quando ele gruda no topo
    const stuck = () => hdr.classList.toggle('is-stuck', scrollY > 0);
    addEventListener('scroll', stuck, { passive: true });
    stuck();
  }

  /* ---------- Filtro por público (Áreas de Atuação) ---------- */
  $$('[data-filter]').forEach((b) => b.addEventListener('click', () => {
    $$('[data-filter]').forEach((x) => x.setAttribute('aria-pressed', String(x === b)));
    $$('#areas-list .acard').forEach((c) => {
      c.hidden = b.dataset.filter !== 'todos' && !c.dataset.publico.split(' ').includes(b.dataset.filter);
    });
  }));

  /* ---------- Filtro por área (Artigos): em "Todas", a lista omite o destaque ---------- */
  const artCount = $('#arts-count');
  const filterArticles = (area) => {
    $$('[data-afilter]').forEach((x) => x.setAttribute('aria-pressed', String(x.dataset.afilter === area)));
    const cards = $$('#articles-list .art');
    cards.forEach((c, k) => { c.hidden = area === 'todas' ? k === 0 : c.dataset.area !== area; });
    const n = cards.filter((c) => !c.hidden).length;
    const chip = $(`[data-afilter="${area}"]`);
    const plural = n === 1 ? 'artigo' : 'artigos';
    artCount.textContent = area === 'todas'
      ? `${n} ${n === 1 ? 'publicação anterior' : 'publicações anteriores'}`
      : `${n} ${plural} em ${chip.dataset.label}`;
  };
  if (artCount) {
    $$('[data-afilter]').forEach((b) => b.addEventListener('click', () => filterArticles(b.dataset.afilter)));
    filterArticles('todas');
  }

  /* ---------- Carrossel de artigos (Início; vira grade a partir de 1100px) ---------- */
  const car = $('#home-articles');
  if (car && car.children.length > 1) {
    const cards = [...car.children], dots = $('#home-dots'), prev = $('#car-prev'), next = $('#car-next');
    dots.innerHTML = cards.map((_, i) => `<button type="button" aria-label="Ir para o artigo ${i + 1}"></button>`).join('');
    const step = () => cards[1].offsetLeft - cards[0].offsetLeft || 1;
    const current = () => Math.round(car.scrollLeft / step());
    const sync = () => {
      const last = car.scrollLeft + car.clientWidth >= car.scrollWidth - 4, i = last ? cards.length - 1 : current();
      [...dots.children].forEach((d, k) => d.setAttribute('aria-current', String(k === i)));
      prev.disabled = car.scrollLeft <= 4;
      next.disabled = last;
    };
    const go = (i) => car.scrollTo({ left: Math.max(0, Math.min(cards.length - 1, i)) * step() });
    prev.addEventListener('click', () => go(current() - 1));
    next.addEventListener('click', () => go(current() + 1));
    [...dots.children].forEach((d, k) => d.addEventListener('click', () => go(k)));
    car.addEventListener('scroll', () => requestAnimationFrame(sync), { passive: true });
    new ResizeObserver(sync).observe(car);
  }

  /* ---------- Botão "Copiar" dos canais ---------- */
  document.addEventListener('click', (e) => {
    const b = e.target.closest('[data-copy]');
    if (!b) return;
    const done = () => {
      const label = b.textContent;
      b.textContent = 'Copiado';
      announce(`Copiado: ${b.dataset.copy}`);
      setTimeout(() => { b.textContent = label; }, 1800);
    };
    if (navigator.clipboard) {
      navigator.clipboard.writeText(b.dataset.copy).then(done, () => announce(b.dataset.copy));
    } else {
      announce(b.dataset.copy);
    }
  });

  /* ---------- Formulário de contato: validação por campo ---------- */
  const form = $('#contact-form');
  if (form) {
    const status = $('#form-status');
    form.addEventListener('submit', (e) => {
      e.preventDefault();
      let first = null;
      $$('[data-msg]', form).forEach((input) => {
        const field = input.closest('.field'), hint = input.id === 'f-msg' ? 'f-msg-hint ' : '';
        field.querySelector('.err')?.remove();
        if (!input.checkValidity()) {
          field.dataset.invalid = '';
          input.setAttribute('aria-invalid', 'true');
          field.insertAdjacentHTML('beforeend', `<p class="err" id="${input.id}-err">${input.dataset.msg}</p>`);
          input.setAttribute('aria-describedby', `${hint}${input.id}-err`);
          first ??= input;
        } else {
          delete field.dataset.invalid;
          input.removeAttribute('aria-invalid');
          if (hint) input.setAttribute('aria-describedby', hint.trim()); else input.removeAttribute('aria-describedby');
        }
      });
      const check = $('#f-ciencia'), wrap = $('#f-ciencia-wrap');
      if (!check.checked) { wrap.dataset.invalid = ''; first ??= check; } else { delete wrap.dataset.invalid; }

      status.hidden = false;
      if (first) {
        status.textContent = 'Revise os campos destacados para enviar a mensagem.';
        first.focus();
      } else if (form.hasAttribute('data-envio-indisponivel')) {
        // O envio pelo servidor entra na WDLC 5.2 (#15).
        status.textContent = 'O envio pelo site ainda não está disponível. Use o telefone, o WhatsApp ou o e-mail indicados nesta página.';
      }
    });
  }

  /* ---------- Aviso de cookies: fechado fica lembrado neste navegador (sem gravar cookie) ---------- */
  const cookie = $('#cookie');
  if (cookie) {
    const key = 'sg-aviso-cookies';
    try { if (localStorage.getItem(key) === '1') cookie.hidden = true; } catch (err) { /* armazenamento indisponível */ }
    $('[data-cookie-dismiss]', cookie)?.addEventListener('click', () => {
      cookie.hidden = true;
      try { localStorage.setItem(key, '1'); } catch (err) { /* armazenamento indisponível */ }
    });
  }
})();
