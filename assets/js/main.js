/* ==============================================================
   main.js – Header UI ＋ 慣性Parallax（.parallax 対象）＋ Section Anim
   --------------------------------------------------------------
   - 多重バインド防止
   - 開閉state一本化（menuOpen）
   - 空白クリック/タップで閉じる：PCのみ（スマホ/タブは無効）
   - SPレイアウト時、hover対応端末なら「ホバーで開閉」
     → 閉じ判定は header の mouseleave のみ
     → ウィンドウ外へ出た場合は閉じない
   - TOPページのみヘッダー透過（<main class="isTop">）
   - ナビ内リンク／ESCで閉じる
   - passive適用、reduce-motion尊重
   ============================================================== */
(() => {
  const prefersReduced = window.matchMedia(
    "(prefers-reduced-motion: reduce)"
  ).matches;

  /* ----------------------------------------------------------------
   * 1) Header : ハンバーガー開閉 & スクロール着色 & TOP透過
   * ---------------------------------------------------------------- */
  if (!window.__LP_HEADER_BOUND__) {
    window.__LP_HEADER_BOUND__ = true;

    const header = document.querySelector(".header");
    const toggleBtn = document.querySelector(".header__toggle");
    const nav = document.querySelector(".header__nav");

    if (header && toggleBtn && nav) {
      let applyTopTransparent = () => {};
      let menuOpen = false;

      // 判定：SPレイアウト（幅≤992px）かつ タッチ端末 なら「空白で閉じる」を無効化
      const mqSP = window.matchMedia("(max-width: 992px)");
      const canHover = window.matchMedia("(hover: hover) and (pointer: fine)");
      const isTouchLike = window.matchMedia("(hover: none), (pointer: coarse)");
      const blankCloseEnabled = () => !(mqSP.matches && isTouchLike.matches); // ← PCのみ true

      const syncMenuState = (open) => {
        menuOpen = !!open;
        toggleBtn.classList.toggle("is-active", menuOpen);
        nav.classList.toggle("is-active", menuOpen);
        document.body.classList.toggle("is-locked", menuOpen);
        toggleBtn.setAttribute("aria-expanded", menuOpen ? "true" : "false");
        applyTopTransparent();
      };
      const openMenu = () => syncMenuState(true);
      const closeMenu = () => syncMenuState(false);

      // 初期 ARIA
      toggleBtn.setAttribute("aria-expanded", "false");

      // クリック：現在の state を反転（同要素の他ハンドラを遮断）
      toggleBtn.addEventListener("click", (e) => {
        e.preventDefault();
        e.stopImmediatePropagation();
        syncMenuState(!menuOpen);
      });

      // ====== nav内クリック/タップ：リンクで閉じる。空白閉じはPCのみ ======
      const interactiveSel =
        "button, input, select, textarea, label, [role='button'], [role='menuitem'], [data-keepopen]";
      const navHandler = (e) => {
        const t = e.target;
        if (t.closest("a")) {
          closeMenu();
          return;
        } // リンク押下で閉じる（全環境）
        if (t.closest(interactiveSel)) {
          return;
        } // 入力系は閉じない
        if (blankCloseEnabled()) {
          // PCのみ：空白で閉じる
          closeMenu();
        }
      };
      nav.addEventListener("click", navHandler);
      nav.addEventListener("touchstart", navHandler);

      // ====== ドキュメント外側クリック/タップ：PCのみ有効 ======
      const outsideCloseHandler = (e) => {
        if (!menuOpen || !blankCloseEnabled()) return; // SP/タブでは無効
        const t = e.target;
        if (t.closest(".header__nav") || t.closest(".header__toggle")) return;
        closeMenu();
      };
      document.addEventListener("click", outsideCloseHandler, {
        capture: true,
      });
      document.addEventListener("touchstart", outsideCloseHandler, {
        capture: true,
      });

      // ESCで閉じる（全環境）
      document.addEventListener("keydown", (e) => {
        if (e.key === "Escape") closeMenu();
      });

      // 幅が広がったら一旦閉じる（表示崩れ対策）
      window.addEventListener(
        "resize",
        () => {
          if (window.innerWidth > 992) closeMenu();
        },
        { passive: true }
      );

      // ヘッダーのスクロール着色（全ページ）
      const setHeaderColor = () =>
        header.classList.toggle("header--scrolled", window.scrollY > 50);
      setHeaderColor();
      window.addEventListener("scroll", setHeaderColor, { passive: true });

      // TOPのみ透過（main.isTop があるとき）
      const isTopPage = !!document.querySelector("main.isTop");
      if (isTopPage) {
        applyTopTransparent = () => {
          // 「トップ位置 かつ メニュー閉」のときだけ透過
          const atTop = window.scrollY <= 10 && !menuOpen;
          header.classList.toggle("header--transparent", atTop);
        };
        applyTopTransparent();
        window.addEventListener("scroll", applyTopTransparent, {
          passive: true,
        });
      }

      // ====== SPレイアウト時のみ、hover対応端末でホバー開閉を有効化 ======
      let hoverBound = false;
      let openTimer, closeTimer;

      const bindHoverHandlers = () => {
        // 既存解除
        if (hoverBound) {
          toggleBtn.removeEventListener("mouseenter", onEnterOpen);
          nav.removeEventListener("mouseenter", onEnterOpen);
          header.removeEventListener("mouseleave", onHeaderLeaveClose);
          hoverBound = false;
        }
        // 条件：SPレイアウト かつ hover可能環境（=小さめウィンドウ＋マウス等）
        if (mqSP.matches && canHover.matches) {
          toggleBtn.addEventListener("mouseenter", onEnterOpen);
          nav.addEventListener("mouseenter", onEnterOpen);
          header.addEventListener("mouseleave", onHeaderLeaveClose);
          hoverBound = true;
        }
      };

      // 開く（hover-intent）
      function onEnterOpen() {
        clearTimeout(closeTimer);
        openTimer = setTimeout(openMenu, 120);
      }
      // 閉じる：ヘッダー領域を“出たときのみ”
      // ただし、relatedTarget === null（＝ウィンドウ外へ）や header/nav/toggle へ移動は閉じない
      function onHeaderLeaveClose(e) {
        clearTimeout(openTimer);
        const rt = e && e.relatedTarget;
        if (!rt) return; // ウィンドウ外へ出た → 閉じない
        if (header.contains(rt) || nav.contains(rt) || toggleBtn.contains(rt))
          return;
        closeTimer = setTimeout(closeMenu, 180);
      }

      // 初期＆変更時に適用
      bindHoverHandlers();
      mqSP.addEventListener?.("change", bindHoverHandlers);
      canHover.addEventListener?.("change", bindHoverHandlers);

      // 他スクリプトから安全に閉じられるよう公開（アンカー処理等）
      window.__LP_closeMenu = closeMenu;
    }
  }

  /* ----------------------------------------------------------------
   * 2) Inertia Parallax : .parallax 要素を慣性追従させる
   * ---------------------------------------------------------------- */
  const pElems = [...document.querySelectorAll(".parallax")];
  const hasParallax = pElems.length > 0 && !prefersReduced;
  const lerp = (a, b, t) => a + (b - a) * t;

  const pState = new WeakMap();
  const calcTarget = (el, scY, winH) => {
    const speed = Number(el.dataset.parallaxSpeed || 0.4);
    const rect = el.getBoundingClientRect();
    const elTop = rect.top + scY;
    const ratio = (scY + winH - elTop) / (rect.height + winH); // 0–1
    const prog = Math.min(Math.max(ratio, 0), 1);
    const maxMove = rect.height * speed * 1.2;
    return { target: prog * maxMove, maxMove };
  };

  let rafId = 0;
  const renderParallax = () => {
    const scY = window.pageYOffset || document.documentElement.scrollTop;
    const winH = window.innerHeight;

    pElems.forEach((el) => {
      const st = pState.get(el) || { cur: 0, target: 0, maxMove: 0 };
      const { target, maxMove } = calcTarget(el, scY, winH);

      if (st.maxMove == null || Math.abs(maxMove - st.maxMove) > 0.5) {
        el.style.setProperty("--parallax-extra", `${Math.ceil(maxMove * 2)}px`);
        st.maxMove = maxMove;
      }

      st.target = target;
      st.cur = lerp(st.cur, st.target, 0.12); // 慣性
      el.style.setProperty("--parallax-offset", `-${st.cur.toFixed(2)}px`);
      pState.set(el, st);
    });

    rafId = requestAnimationFrame(renderParallax);
  };

  const startParallax = () => {
    if (!hasParallax) return;
    pElems.forEach((el) => pState.set(el, { cur: 0, target: 0, maxMove: 0 }));
    rafId = requestAnimationFrame(renderParallax);
    if ("ResizeObserver" in window) {
      const ro = new ResizeObserver(() => {});
      pElems.forEach((el) => ro.observe(el));
    }
    window.addEventListener(
      "resize",
      () => {
        /* 次フレームでmaxMove反映 */
      },
      { passive: true }
    );
  };

  /* ----------------------------------------------------------------
   * 3) Section Animation : ★同時発火モード（.is-sync 付与）
   * ---------------------------------------------------------------- */
  const TRIGGER_POS = 0.9;
  const sections = [...document.querySelectorAll(".section")];

  const splitTitle = (title) => {
    if (!title || title.dataset.split === "done") return;

    const wrapTextNode = (node) => {
      const frag = document.createDocumentFragment();
      const chars = node.textContent.split("");
      chars.forEach((ch, i) => {
        const span = document.createElement("span");
        span.className = "char";
        span.style.setProperty("--i", i);
        span.textContent = ch;
        frag.appendChild(span);
      });
      node.parentNode.replaceChild(frag, node);
    };

    const walker = document.createTreeWalker(title, NodeFilter.SHOW_TEXT, {
      acceptNode: (n) =>
        n.nodeValue.trim()
          ? NodeFilter.FILTER_ACCEPT
          : NodeFilter.FILTER_REJECT,
    });
    const textNodes = [];
    while (walker.nextNode()) textNodes.push(walker.currentNode);
    textNodes.forEach(wrapTextNode);

    title.dataset.split = "done";
  };

  const updateTitleCounts = (sec) => {
    const count = sec.querySelectorAll(".section__title .char").length;
    if (count) sec.style.setProperty("--title-chars", String(count));
  };

  const onEnter = (sec) => {
    const title = sec.querySelector(".section__title");
    if (title) {
      splitTitle(title);
      updateTitleCounts(sec);
    }
    sec.classList.add("is-visible", "is-sync");
  };

  const io = new IntersectionObserver(
    (entries, observer) => {
      entries.forEach((ent) => {
        if (!ent.isIntersecting) return;
        onEnter(ent.target);
        observer.unobserve(ent.target);
      });
    },
    {
      rootMargin: `0px 0px -${(1 - TRIGGER_POS) * 100}% 0px`,
      threshold: 0,
    }
  );

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
    a.addEventListener("click", (e) => {
      const id = decodeURIComponent(a.getAttribute("href"));
      const target = id && id !== "#" ? document.querySelector(id) : null;
      if (!target) return;
      e.preventDefault();
      target.scrollIntoView({
        behavior: prefersReduced ? "auto" : "smooth",
        block: "start",
      });
      (window.__LP_closeMenu || function () {})();
    });
  });

  /* ----------------------------------------------------------------
   * 5) 起動
   * ---------------------------------------------------------------- */
  const init = () => {
    startParallax();
  };
  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", init);
  } else {
    init();
  }
})();

/* -------------------------------------------------------
 * Global CTA Buttons – scroll-in animation
 * ----------------------------------------------------- */
(() => {
  const ctaButtons = document.querySelectorAll(".c-cta__button");
  if (!ctaButtons.length) return;
  if (!(window.gsap && window.ScrollTrigger)) return;

  gsap.registerPlugin(ScrollTrigger);
  ctaButtons.forEach((btn) => {
    gsap.from(btn, {
      y: 30,
      autoAlpha: 0,
      duration: 0.9,
      ease: "power3.out",
      scrollTrigger: {
        trigger: btn,
        start: "top 85%",
        toggleActions: "play none none none",
      },
    });
  });
})();
