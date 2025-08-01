/**
 * assets/js/top.js
 * ---------------------------------------------------------
 * ① Hero セクション Swiper（フェード＋ズーム）
 * ---------------------------------------------------------
 */
(() => {
  /* Hero Swiper */
  const heroSliderElm = document.querySelector(".js-hero-slider");
  if (heroSliderElm) {
    /* eslint-disable no-unused-vars */
    const heroSwiper = new Swiper(heroSliderElm, {
      loop: true,
      speed: 1200,               // フェード時間
      autoplay: {
        delay: 6000,            // 画像の静止時間
        disableOnInteraction: false,
      },
      effect: "fade",
      fadeEffect: { crossFade: true },
    });
  }
})(); // ← 末尾で呼び出して実行

const toggle = document.querySelector('.header__toggle');
toggle.addEventListener('click', () => {
  toggle.classList.toggle('is-active');      // ← ボタンの線をアニメ
  document.querySelector('.header__nav')
          .classList.toggle('is-open');      // ← メニューの開閉制御など
});

/* ----------------------------------------------
 * Recruit パララックス（モバイルも対応）
 * ---------------------------------------------- */
(() => {
  const recruit = document.querySelector("#recruit");

  if (!recruit) return;

  /* モバイル（<=1024px）だけ ScrollTrigger を適用
     ── desktop は background-attachment:fixed が働くため */
  const mq = window.matchMedia("(max-width: 1024px)");
  if (mq.matches) {
    gsap.to(recruit, {
      backgroundPosition: "50% 80%",       // 好きな終点位置に調整
      ease: "none",
      scrollTrigger: {
        trigger: recruit,
        start: "top bottom",               // セクションが下から現れるとパララックス開始
        end: "bottom top",                 // 頂点を過ぎるまでスクラブ
        scrub: true                        // スクロール量に同期
      }
    });
  }
})();



