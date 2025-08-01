/* ==============================================================
   main.js – Header UI ＋ 汎用 Parallax（.parallax クラス対象）
   ============================================================== */
(() => {
  /* ----------------------------------------------------------------
     1.  Header : ハンバーガー開閉 & スクロール着色
     ---------------------------------------------------------------- */
  const header       = document.querySelector('.header');
  const toggleBtn    = document.querySelector('.header__toggle');
  const toggleLine   = document.querySelector('.header__toggle-line');
  const nav          = document.querySelector('.header__nav');

  if (toggleBtn && toggleLine && nav) {
    toggleBtn.addEventListener('click', () => {
      toggleBtn.classList.toggle('is-active');
      toggleLine.classList.toggle('is-active');
      nav.classList.toggle('is-active');
    });
  }

  const setHeaderColor = () =>
    header?.classList.toggle('header--scrolled', window.scrollY > 50);

  setHeaderColor();
  window.addEventListener('scroll', setHeaderColor);

  /* ----------------------------------------------------------------
     2.  Parallax : .parallax 要素を自動処理
         カスタムプロパティ --parallax-offset / --parallax-extra を出力
     ---------------------------------------------------------------- */
  const PARALLAX_DEFAULT_SPEED  = 0.4;   // data-parallax-speed 無指定時
  const PARALLAX_AMPLIFY_FACTOR = 1.2;   // 背景余白＆最大振幅の係数

  const pElems = document.querySelectorAll('.parallax');
  if (!pElems.length) return;        // 該当要素が無ければ終了

  /* メイン更新関数 */
  const updateParallax = () => {
    const scY  = window.pageYOffset || document.documentElement.scrollTop;
    const winH = window.innerHeight;

    pElems.forEach(el => {
      /* data-parallax-speed="0.6" のように指定可 */
      const speed = +el.dataset.parallaxSpeed || PARALLAX_DEFAULT_SPEED;

      const rect   = el.getBoundingClientRect();
      const elTop  = rect.top + scY;
      const ratio  = (scY + winH - elTop) / (rect.height + winH);   // 0–1
      const prog   = Math.min(Math.max(ratio, 0), 1);

      const maxMove = rect.height * speed * PARALLAX_AMPLIFY_FACTOR;
      const offsetY = prog * maxMove;

      el.style.setProperty('--parallax-offset', `-${offsetY}px`);
      el.style.setProperty('--parallax-extra',  `${maxMove}px`);
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
  window.addEventListener('scroll',  onScroll);
  window.addEventListener('resize',  updateParallax);
  window.addEventListener('load',    updateParallax);

  /* 要素自身のサイズ変化も監視（画像遅延読込やフォントロードに対応） */
  if ('ResizeObserver' in window) {
    const ro = new ResizeObserver(updateParallax);
    pElems.forEach(el => ro.observe(el));
  }

  /* 初期実行 */
  updateParallax();
})();
