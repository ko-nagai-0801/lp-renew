/* ==============================================================
   main.js – Header UI ＋ 汎用 Parallax（.parallax クラス対象）
   ============================================================== */
(() => {
  /* ----------------------------------------------------------------
     1.  Header : ハンバーガー開閉 & スクロール着色
     ---------------------------------------------------------------- */
  const header = document.querySelector(".header");
  const toggleBtn = document.querySelector(".header__toggle");
  const toggleLine = document.querySelector(".header__toggle-line");
  const nav = document.querySelector(".header__nav");

  if (toggleBtn && toggleLine && nav) {
    toggleBtn.addEventListener("click", () => {
      toggleBtn.classList.toggle("is-active");
      toggleLine.classList.toggle("is-active");
      nav.classList.toggle("is-active");
    });
  }

  const setHeaderColor = () =>
    header?.classList.toggle("header--scrolled", window.scrollY > 50);

  setHeaderColor();
  window.addEventListener("scroll", setHeaderColor);

  /* ----------------------------------------------------------------
     2.  Parallax : .parallax 要素を自動処理
         カスタムプロパティ --parallax-offset / --parallax-extra を出力
     ---------------------------------------------------------------- */
  const PARALLAX_DEFAULT_SPEED = 0.4; // data-parallax-speed 無指定時
  const PARALLAX_AMPLIFY_FACTOR = 1.2; // 背景余白＆最大振幅の係数

  const pElems = document.querySelectorAll(".parallax");
  if (!pElems.length) return; // 該当要素が無ければ終了

  /* メイン更新関数 */
  const updateParallax = () => {
    const scY = window.pageYOffset || document.documentElement.scrollTop;
    const winH = window.innerHeight;

    pElems.forEach((el) => {
      /* data-parallax-speed="0.6" のように指定可 */
      const speed = +el.dataset.parallaxSpeed || PARALLAX_DEFAULT_SPEED;

      const rect = el.getBoundingClientRect();
      const elTop = rect.top + scY;
      const ratio = (scY + winH - elTop) / (rect.height + winH); // 0–1
      const prog = Math.min(Math.max(ratio, 0), 1);

      const maxMove = rect.height * speed * PARALLAX_AMPLIFY_FACTOR;
      const offsetY = prog * maxMove;

      el.style.setProperty("--parallax-offset", `-${offsetY}px`);
      el.style.setProperty("--parallax-extra", `${maxMove}px`);
    });
  };

  /* requestAnimationFrame で最適化 */
  let ticking = false;
  const onScroll = () => {
    if (!ticking) {
      requestAnimationFrame(() => {
        updateParallax();
        ticking = false;
      });
      ticking = true;
    }
  };

  /* イベント登録 */
  window.addEventListener("scroll", onScroll);
  window.addEventListener("resize", updateParallax);
  window.addEventListener("load", updateParallax);

  /* 要素自身のサイズ変化も監視（画像遅延読込やフォントロードに対応） */
  if ("ResizeObserver" in window) {
    const rObserver = new ResizeObserver(updateParallax);
    pElems.forEach((el) => rObserver.observe(el));
  }

  /* ──────────────────────────────────────────────
     ③  Section アニメーション: 出入りでトグル
     ──────────────────────────────────────────── */

  /* ===========================================
   * 共通見出しを 1 文字ずつ <span class="char">
   * ======================================= */
  (() => {
    const titles = document.querySelectorAll(".section__title");

    titles.forEach((title) => {
      if (title.querySelector(".char")) return; // 既に分割済みなら無視

      const chars = Array.from(title.textContent.trim());
      title.innerHTML = chars
        .map((ch, i) => `<span class="char" style="--i:${i}">${ch}</span>`)
        .join("");
    });
  })();

  const sections = document.querySelectorAll(".section");

  /* タイトル文字数を CSS 変数へ（delay 計算用）*/
  sections.forEach((sec) => {
    const chars = sec.querySelectorAll(".section__title .char").length;
    sec.style.setProperty("--title-chars", chars);
  });

  /* ================= スクロール方向を記録 ================= */
  let lastScrollY = window.pageYOffset || document.documentElement.scrollTop;
  let scrollDir = "down";

  window.addEventListener("scroll", () => {
    const curY = window.pageYOffset || document.documentElement.scrollTop;
    scrollDir = curY > lastScrollY ? "down" : "up";
    lastScrollY = curY;
  });

  /* ============ IntersectionObserver ============ */
  const io = new IntersectionObserver(
    (entries) => {
      entries.forEach((ent) => {
        if (ent.isIntersecting) {
          /* ▼ 入ってきたときの条件判定 */
          const ratioOK =
            scrollDir === "down"
              ? true // 下方向は即
              : ent.intersectionRatio >= 0.6; // 上方向は60%見えたら

          if (ratioOK) ent.target.classList.add("is-visible");
        } else {
          /* ▼ 完全に出たらリセット */
          ent.target.classList.remove("is-visible");
        }
      });
    },
    {
      rootMargin: "0px 0px -10% 0px" /* 上から下は早めに発火 */,
      threshold: [0, 0.6] /* ratio=0 と 0.9 を拾う */,
    }
  );

  sections.forEach((sec) => io.observe(sec));

  /* 初期実行 */
  updateParallax();
})();

/* -------------------------------------------------------
 * Global CTA Buttons – scroll-in animation
 * ----------------------------------------------------- */
(() => {
  const ctaButtons = document.querySelectorAll(".c-cta__button");
  if (!ctaButtons.length) return;

  gsap.registerPlugin(ScrollTrigger);

  ctaButtons.forEach((btn) => {
    gsap.from(btn, {
      y: 30,
      autoAlpha: 0,
      duration: 0.9,
      ease: "power3.out",
      scrollTrigger: {
        trigger: btn, // ボタン自身をトリガー
        start: "top 85%", // 85% 付近で発火
        toggleActions: "play none none none",
      },
    });
  });
})();
