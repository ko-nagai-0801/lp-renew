/*! assets/js/parallax.js  (全ページで読み込み OK) */
(() => {
  /** 設定値 */
  const DEFAULT_SPEED = 0.4; /* data-parallax-speed が無い場合 */
  const AMPLIFY_FACTOR = 1.2; /* 余白兼最大移動量の係数        */

  /** 対象要素を取得 */
  const els = document.querySelectorAll(".parallax");
  if (!els.length) return;

  /** メイン計算 */
  const update = () => {
    const scY = window.pageYOffset || document.documentElement.scrollTop;
    const winH = window.innerHeight;

    els.forEach((el) => {
      const speed = +el.dataset.parallaxSpeed || DEFAULT_SPEED;
      const rect = el.getBoundingClientRect();
      const top = rect.top + scY;

      const progress = Math.min(
        Math.max((scY + winH - top) / (rect.height + winH), 0),
        1
      );

      const maxMove = rect.height * speed * AMPLIFY_FACTOR;
      const offsetY = progress * maxMove;

      el.style.setProperty("--parallax-offset", `-${offsetY}px`);
      el.style.setProperty("--parallax-extra", `${maxMove}px`);
    });
  };

  /** rAF 最適化 */
  let ticking = false;
  const onScroll = () => {
    if (!ticking) {
      requestAnimationFrame(() => {
        update();
        ticking = false;
      });
      ticking = true;
    }
  };

  /** リサイズ & 要素サイズ変化対応（ResizeObserver） */
  const ro = "ResizeObserver" in window ? new ResizeObserver(update) : null;
  els.forEach((el) => ro?.observe(el));

  window.addEventListener("scroll", onScroll);
  window.addEventListener("resize", update);
  window.addEventListener("load", update);
})();
