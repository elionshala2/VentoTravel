(() => {
  const $ = (s, r = document) => r.querySelector(s), $$ = (s, r = document) => [...r.querySelectorAll(s)];
  const nav = $('#nav'), burger = $('#burger'), links = $('#navLinks'), backdrop = $('#navBackdrop');

  // Theme toggle (saved in cookie)
  $('#themeBtn')?.addEventListener('click', () => {
    const next = document.documentElement.dataset.theme === 'light' ? 'dark' : 'light';
    document.documentElement.dataset.theme = next;
    document.cookie = `theme=${next};path=/;max-age=31536000;SameSite=Lax`;
    document.querySelector('meta[name=theme-color]')?.setAttribute('content', next === 'light' ? '#f4f7fc' : '#0b1220');
  });

  // Mobile menu
  const setMenu = open => {
    document.body.classList.toggle('menu-open', open);
    burger.setAttribute('aria-expanded', open);
  };
  burger?.addEventListener('click', () => setMenu(!document.body.classList.contains('menu-open')));
  backdrop?.addEventListener('click', () => setMenu(false));
  $$('a', links).forEach(a => a.addEventListener('click', () => setMenu(false)));
  addEventListener('keydown', e => e.key === 'Escape' && setMenu(false));
  matchMedia('(min-width: 981px)').addEventListener('change', () => setMenu(false));

  // Sticky nav + scroll progress
  const bar = $('.scroll-progress');
  const onScroll = () => {
    nav.classList.toggle('is-scrolled', scrollY > 20);
    const max = document.documentElement.scrollHeight - innerHeight;
    if (bar) bar.style.transform = `scaleX(${max > 0 ? scrollY / max : 0})`;
  };
  addEventListener('scroll', onScroll, { passive: true }); onScroll();

  // Reveal on scroll
  const io = new IntersectionObserver(es => es.forEach(e => {
    if (e.isIntersecting) { e.target.classList.add('in'); io.unobserve(e.target); }
  }), { threshold: .12 });
  $$('.reveal').forEach((el, i) => { el.style.setProperty('--d', (i % 6) * 70 + 'ms'); io.observe(el); });

  // Count-up
  const cio = new IntersectionObserver(es => es.forEach(e => {
    if (!e.isIntersecting) return; cio.unobserve(e.target);
    const el = e.target, end = +el.dataset.count, t0 = performance.now();
    const tick = t => { const p = Math.min((t - t0) / 1400, 1); el.textContent = Math.round(end * (1 - Math.pow(1 - p, 3))).toLocaleString() + (el.dataset.suffix || ''); p < 1 && requestAnimationFrame(tick); };
    requestAnimationFrame(tick);
  }), { threshold: .6 });
  $$('[data-count]').forEach(el => cio.observe(el));

  // Card tilt (desktop only)
  if (matchMedia('(hover:hover) and (prefers-reduced-motion: no-preference)').matches) {
    $$('.tilt').forEach(c => {
      c.addEventListener('pointermove', e => {
        const r = c.getBoundingClientRect(), x = (e.clientX - r.left) / r.width - .5, y = (e.clientY - r.top) / r.height - .5;
        c.style.transform = `perspective(900px) rotateX(${-y * 5}deg) rotateY(${x * 5}deg) translateY(-4px)`;
      });
      c.addEventListener('pointerleave', () => c.style.transform = '');
    });
  }

  // Toast auto-dismiss
  const toast = $('.toast');
  if (toast) { requestAnimationFrame(() => toast.classList.add('show')); const x = () => toast.classList.remove('show'); $('button', toast).onclick = x; setTimeout(x, 5000); }

  // Offer banner (once per session)
  const offer = $('#offer');
  if (offer) {
    if (sessionStorage.getItem('offerClosed')) offer.remove();
    else setTimeout(() => offer.classList.add('show'), 900);
    $('button', offer)?.addEventListener('click', () => { offer.classList.remove('show'); sessionStorage.setItem('offerClosed', 1); });
  }

  // Destination sorting (CSS order: no DOM re-appending, so images never reload)
  const sel = $('#sort'), grid = $('#destGrid');
  if (sel && grid) {
    const sort = () => {
      const cards = $$('.dest', grid), v = sel.value;
      cards.sort((a, b) => v === 'low' ? a.dataset.price - b.dataset.price : v === 'high' ? b.dataset.price - a.dataset.price : v === 'new' ? b.dataset.id - a.dataset.id : a.dataset.id - b.dataset.id);
      cards.forEach((c, i) => c.style.order = i);
    };
    sel.addEventListener('change', sort); sort();
  }
  $$('.dest__toggle').forEach(b => b.addEventListener('click', () => {
    const open = b.closest('.dest').classList.toggle('open');
    b.textContent = open ? b.dataset.less : b.dataset.more;
  }));

  // Admin / history inline edit panels
  $$('[data-edit]').forEach(btn => btn.addEventListener('click', () => {
    const f = $('#editPanel'); if (!f) return;
    const row = JSON.parse(btn.dataset.edit);
    Object.entries(row).forEach(([k, v]) => { const i = f.elements['new_' + k] || f.elements[k]; if (i) i.value = v; });
    f.hidden = false; f.scrollIntoView({ behavior: 'smooth', block: 'center' });
  }));
  $('#editCancel')?.addEventListener('click', () => $('#editPanel').hidden = true);
  $$('[data-confirm]').forEach(a => a.addEventListener('click', e => { if (!confirm(a.dataset.confirm)) e.preventDefault(); }));
})();
