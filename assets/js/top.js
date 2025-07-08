/**
 * assets/js/top.js
 * ---------------------------------------------------------
 * ① Hero セクション Swiper（フェード＋ズーム）
 * ---------------------------------------------------------
 */
(() => {                        // ← 先頭に (      ) を追加
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
})();                           // ← 末尾で呼び出す
