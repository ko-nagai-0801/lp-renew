/**
 * assets/js/top.js
 * ---------------------------------------------------------
 *  ① Hero セクション Swiper（フェード）
 *  ② Header  : ハンバーガーの開閉 + スクロール時の背景切替
 *  ③ About   : 画像３枚を Intersection Observer でスライドイン
 * ---------------------------------------------------------
 *  ※ functions.php 側で下記ライブラリを依存として登録済み
 *    - Swiper 11
 *    - GSAP（今回は未使用だが将来拡張用）
 */
(() => {
  /* ===============================================
     ① Hero Swiper
     ============================================ */
  const heroSliderElm = document.querySelector(".js-hero-slider");
  if (heroSliderElm) {
    // eslint-disable-next-line no-unused-vars
    const heroSwiper = new Swiper(heroSliderElm, {
      loop: true,
      speed: 1000,
      autoplay: { delay: 5000 },
      effect: "fade",
    });
  }

  /* ===============================================
     ② Header : hamburger & scroll color change
     ============================================ */
  const header = document.querySelector(".header");
  const toggleButton = document.querySelector(".header__toggle");
  const toggleLine = document.querySelector(".header__toggle-line");
  const nav = document.querySelector(".header__nav");

  if (toggleButton && toggleLine && nav) {
    toggleButton.addEventListener("click", () => {
      toggleButton.classList.toggle("is-active");
      toggleLine.classList.toggle("is-active");
      nav.classList.toggle("is-active");
    });
  }

  // 50px 以上スクロールしたら背景色付与
  const onScrollHeader = () => {
    if (!header) return;
    header.classList.toggle("header--scrolled", window.scrollY > 50);
  };
  onScrollHeader(); // 初期チェック
  window.addEventListener("scroll", onScrollHeader);

  /* ===============================================
     ③ About : slide-in images (IntersectionObserver)
     ============================================ */
  const aboutTargets = document.querySelectorAll(
    ".about .js-slide-from-left, .about .js-slide-from-right"
  );

  if (aboutTargets.length) {
    const observer = new IntersectionObserver(
      (entries, io) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            entry.target.classList.add("is-slide-in");
            io.unobserve(entry.target); // 一度だけアニメ
          }
        });
      },
      {
        rootMargin: "0px 0px -10% 0px", // 少し早めに発火
        threshold: 0.3,
      }
    );

    // 0.15 秒ステップでディレイ
    aboutTargets.forEach((el, idx) => {
      el.style.animationDelay = `${idx * 0.15}s`;
      observer.observe(el);
    });
  }
})();
