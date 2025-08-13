/* ==============================================================
   main.js – Header UI ＋ 慣性Parallax（.parallax 対象）＋ Section Anim
   --------------------------------------------------------------
   ◇ポイント
   - 『.parallax が無いページでも』他の処理が止まらないように修正
   - パララックスは“スクロール後に少し追従する”慣性式（lerp）
   - 見出し分割は安全化（子要素は温存／必要な時だけ）
   - TOPページのみヘッダー透過（<main class="isTop"> で判定）
   - ナビ内リンククリック／ESC でメニューを閉じる
   - passive でパフォーマンス最適化、reduce-motion 尊重
   - ★ セクションの見出し・テキスト等を同時発火（.is-sync 付与）
   ============================================================== */
(() => {
  const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  /* ----------------------------------------------------------------
   * 1) Header : ハンバーガー開閉 & スクロール着色 & TOP透過
   * ---------------------------------------------------------------- */
  const header     = document.querySelector('.header');
  const toggleBtn  = document.querySelector('.header__toggle');
  const toggleLine = document.querySelector('.header__toggle-line');
  const nav        = document.querySelector('.header__nav');

  const openMenu = () => {
    toggleBtn?.classList.add('is-active');
    toggleLine?.classList.add('is-active');
    nav?.classList.add('is-active');
    document.body.classList.add('is-locked');
    toggleBtn?.setAttribute('aria-expanded', 'true');
  };
  const closeMenu = () => {
    toggleBtn?.classList.remove('is-active');
    toggleLine?.classList.remove('is-active');
    nav?.classList.remove('is-active');
    document.body.classList.remove('is-locked');
    toggleBtn?.setAttribute('aria-expanded', 'false');
  };

  if (toggleBtn && nav) {
    toggleBtn.addEventListener('click', () => {
      const willOpen = !nav.classList.contains('is-active');
      willOpen ? openMenu() : closeMenu();
    });

    // ナビ内リンクを押したら閉じる
    nav.addEventListener('click', (e) => {
      if (e.target.closest('a')) closeMenu();
    });

    // ESCで閉じる
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') closeMenu();
    });
  }

  // ヘッダーのスクロール着色（全ページ）
  const setHeaderColor = () => header?.classList.toggle('header--scrolled', window.scrollY > 50);
  setHeaderColor();
  window.addEventListener('scroll', setHeaderColor, { passive: true });

  // TOPのみ透過（main.isTop があるとき）
  const isTopPage = !!document.querySelector('main.isTop');
  if (isTopPage && header) {
    const applyTopTransparent = () => {
      const atTop = window.scrollY <= 10 && !nav?.classList.contains('is-active');
      header.classList.toggle('header--transparent', atTop);
    };
    applyTopTransparent();
    window.addEventListener('scroll', applyTopTransparent, { passive: true });
  }

  /* ----------------------------------------------------------------
   * 2) Inertia Parallax : .parallax 要素を慣性追従させる
   *     CSS側：background-position のYに var(--parallax-offset) を使用
   * ---------------------------------------------------------------- */
  const pElems = [...document.querySelectorAll('.parallax')];
  const hasParallax = pElems.length > 0 && !prefersReduced;
  const lerp = (a, b, t) => a + (b - a) * t;

  // 要素ごとの状態（現在値と目標値）を保持
  const pState = new WeakMap();
  const calcTarget = (el, scY, winH) => {
    const speed = Number(el.dataset.parallaxSpeed || 0.4); // 旧仕様互換
    const rect = el.getBoundingClientRect();
    const elTop = rect.top + scY;
    const ratio = (scY + winH - elTop) / (rect.height + winH); // 0–1
    const prog = Math.min(Math.max(ratio, 0), 1);
    const maxMove = rect.height * speed * 1.2; // 少し強めに
    return { target: prog * maxMove, maxMove };
  };

  let rafId = 0;
  const renderParallax = () => {
    const scY = window.pageYOffset || document.documentElement.scrollTop;
    const winH = window.innerHeight;

    pElems.forEach((el) => {
      const st = pState.get(el) || { cur: 0, target: 0 };
      // 目標を更新
      const { target } = calcTarget(el, scY, winH);
      st.target = target; // px
      // 慣性追従
      st.cur = lerp(st.cur, st.target, 0.12); // 0.08〜0.2 で好みに
      el.style.setProperty('--parallax-offset', `-${st.cur.toFixed(2)}px`);
      pState.set(el, st);
    });

    rafId = requestAnimationFrame(renderParallax);
  };

  const startParallax = () => {
    if (!hasParallax) return;
    // 初期マップ
    pElems.forEach((el) => pState.set(el, { cur: 0, target: 0 }));
    rafId = requestAnimationFrame(renderParallax);

    // レイアウト変化に追随（観測だけ・処理は render 側で常時）
    if ('ResizeObserver' in window) {
      const ro = new ResizeObserver(() => {});
      pElems.forEach((el) => ro.observe(el));
    }
  };

  /* ----------------------------------------------------------------
   * 3) Section Animation : ★同時発火モード（.is-sync 付与）
   *    - 従来の一文字分割は維持（必要なケースのため）
   *    - ただし .is-sync が付いた時は全 delay を 0 に（CSS側で上書き）
   * ---------------------------------------------------------------- */
  const TRIGGER_POS = 0.9; // 画面下から10%ラインで発火
  const sections = [...document.querySelectorAll('.section')];

  // 見出しの 1 文字分割（子要素は保持）。必要なときだけ実施
  const splitTitle = (title) => {
    if (!title || title.dataset.split === 'done') return;

    const wrapTextNode = (node) => {
      const frag = document.createDocumentFragment();
      const chars = node.textContent.split('');
      chars.forEach((ch, i) => {
        const span = document.createElement('span');
        span.className = 'char';
        span.style.setProperty('--i', i);
        span.textContent = ch;
        frag.appendChild(span);
      });
      node.parentNode.replaceChild(frag, node);
    };

    // テキストノードのみ分割し、子要素はそのまま温存
    const walker = document.createTreeWalker(title, NodeFilter.SHOW_TEXT, {
      acceptNode: (n) => n.nodeValue.trim() ? NodeFilter.FILTER_ACCEPT : NodeFilter.FILTER_REJECT
    });
    const textNodes = [];
    while (walker.nextNode()) textNodes.push(walker.currentNode);
    textNodes.forEach(wrapTextNode);

    title.dataset.split = 'done';
  };

  // タイトル文字数を CSS 変数へ（delay 計算用だが .is-sync で無効化）
  const updateTitleCounts = (sec) => {
    const count = sec.querySelectorAll('.section__title .char').length;
    if (count) sec.style.setProperty('--title-chars', String(count));
  };

  // 発火処理（同時発火クラスを必ず付与）
  const onEnter = (sec) => {
    const title = sec.querySelector('.section__title');
    if (title) { splitTitle(title); updateTitleCounts(sec); }
    sec.classList.add('is-visible', 'is-sync'); // ★ 同時発火用クラス
  };

  const io = new IntersectionObserver((entries, observer) => {
    entries.forEach((ent) => {
      if (!ent.isIntersecting) return;
      onEnter(ent.target);
      observer.unobserve(ent.target); // 1回だけ
    });
  }, {
    rootMargin: `0px 0px -${(1 - TRIGGER_POS) * 100}% 0px`,
    threshold: 0
  });

  // 初期表示で既に見えている要素は即時発火
  sections.forEach((sec) => {
    const r = sec.getBoundingClientRect();
    if (r.top < window.innerHeight * TRIGGER_POS && r.bottom > 0) {
      onEnter(sec);
    } else {
      io.observe(sec);
    }
  });

  /* ----------------------------------------------------------------
   * 4) Smooth anchor (reduce-motion 尊重)
   * ---------------------------------------------------------------- */
  document.querySelectorAll('a[href^="#"]').forEach((a) => {
    a.addEventListener('click', (e) => {
      const id = decodeURIComponent(a.getAttribute('href'));
      const target = id && id !== '#' ? document.querySelector(id) : null;
      if (!target) return;
      e.preventDefault();
      target.scrollIntoView({ behavior: prefersReduced ? 'auto' : 'smooth', block: 'start' });
      closeMenu();
    });
  });

  /* ----------------------------------------------------------------
   * 5) 起動
   * ---------------------------------------------------------------- */
  const init = () => {
    startParallax();
  };
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();

/* -------------------------------------------------------
 * Global CTA Buttons – scroll-in animation (GSAPがあれば)
 * ----------------------------------------------------- */
(() => {
  const ctaButtons = document.querySelectorAll('.c-cta__button');
  if (!ctaButtons.length) return;
  if (!(window.gsap && window.ScrollTrigger)) return; // GSAPが無い環境でのエラー回避

  gsap.registerPlugin(ScrollTrigger);
  ctaButtons.forEach((btn) => {
    gsap.from(btn, {
      y: 30,
      autoAlpha: 0,
      duration: 0.9,
      ease: 'power3.out',
      scrollTrigger: {
        trigger: btn,
        start: 'top 85%',
        toggleActions: 'play none none none',
      },
    });
  });
})();
