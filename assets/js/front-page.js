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
      speed: 1200, // フェード時間
      autoplay: {
        delay: 6000, // 画像の静止時間
        disableOnInteraction: false,
      },
      effect: "fade",
      fadeEffect: { crossFade: true },
    });
  }
})(); // ← 末尾で呼び出して実行

const toggle = document.querySelector(".header__toggle");
toggle.addEventListener("click", () => {
  toggle.classList.toggle("is-active"); // ← ボタンの線をアニメ
  document.querySelector(".header__nav").classList.toggle("is-open"); // ← メニューの開閉制御など
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
      backgroundPosition: "50% 80%", // 好きな終点位置に調整
      ease: "none",
      scrollTrigger: {
        trigger: recruit,
        start: "top bottom", // セクションが下から現れるとパララックス開始
        end: "bottom top", // 頂点を過ぎるまでスクラブ
        scrub: true, // スクロール量に同期
      },
    });
  }
})();

/* ---------------------------------------------------------
 * About – 大文字 1 文字ずつフェードイン
 * ------------------------------------------------------ */
(() => {
  const bigTextElm = document.querySelector(".about__big-text");
  if (!bigTextElm) return;

  /* -------- span 分割 -------- */
  bigTextElm.innerHTML = bigTextElm.dataset.text
    .trim()
    .split("")
    .map((ch) => `<span>${ch === " " ? "&nbsp;" : ch}</span>`)
    .join("");

  const spans = bigTextElm.querySelectorAll("span");
  gsap.registerPlugin(ScrollTrigger);

  /* -------- A. 親を 0→1 にフェード（1 回だけ） -------- */
  gsap.fromTo(
    bigTextElm,
    { opacity: 0 },
    {
      opacity: 1,
      duration: 0.6,
      ease: "power2.out",
      scrollTrigger: {
        trigger: bigTextElm,
        start: "top 85%",
        once: true, // 1 回で終了
      },
    }
  );

  /* -------- B. 文字ループ用タイムライン -------- */
  const tl = gsap
    .timeline({
      repeat: -1,
      paused: true,
    })
    .fromTo(
      spans,
      { y: 40, opacity: 0 },
      { y: 0, opacity: 1, duration: 0.6, stagger: 0.07, ease: "power3.out" }
    )
    .to(spans, { opacity: 0, duration: 0.3, stagger: 0.07 }, "+=0.6");

  /* -------- C. ビューポート制御 -------- */
  ScrollTrigger.create({
    trigger: bigTextElm,
    start: "top 85%",
    end: "bottom 15%",
    onEnter: () => tl.play(),
    onEnterBack: () => tl.play(),
    onLeave: () => tl.pause(),
    onLeaveBack: () => tl.pause(),
  });
})();

/* -------------------------------------------------------
 * About – 3 枚を順番にスライドイン（.from 版）
 * ----------------------------------------------------- */
(() => {
  const about = document.querySelector("#about");
  if (!about) return;

  gsap.registerPlugin(ScrollTrigger);

  const mm = gsap.matchMedia();          // 画面幅ごとにタイムラインを切替

  /* ----------- Desktop (≥993px) ---------- */
  mm.add("(min-width: 993px)", () => {
    gsap.timeline({
      scrollTrigger: {
        trigger: about,
        start: "top 75%",
        toggleActions: "play none none none"
      }
    })
    .from(".about__image-01", { x: 100, y: 80,  autoAlpha: 0, duration: 0.8, ease: "power3.out" })
    .from(".about__image-02", { x: 100, autoAlpha: 0, duration: 0.8, ease: "power3.out" },"-=0.4")
    .from(".about__image-03", { x: 140, y: 100, autoAlpha: 0, duration: 0.8, ease: "power3.out" },"-=0.4");
  });

  /* ----------- Tablet & Mobile (≤992px) --- */
  mm.add("(max-width: 992px)", () => {
    gsap.timeline({
      scrollTrigger: {
        trigger: about,
        start: "top 80%",                 // SP は少し下で発火
        toggleActions: "play none none none"
      }
    })
    .from(".about__image-01", { xPercent: -100, yPercent: 0,  autoAlpha: 0, duration: 0.8, ease: "power3.out" })
    .from(".about__image-02", { xPercent: 30, yPercent: 0,  autoAlpha: 0, duration: 0.8, ease: "power3.out" },"-=0.4")
    .from(".about__image-03", { xPercent: 30,  yPercent: 30, autoAlpha: 0, duration: 0.8, ease: "power3.out" },"-=0.4");
  });

})();


  /* 画像がふわっと現れ、テキストが左からスライド */
(() => {
  gsap.registerPlugin(ScrollTrigger);

  const tl = gsap.timeline({
    scrollTrigger:{
      trigger: "#about",
      start: "top 70%",
      toggleActions: "play none none none"
    }
  });

  tl.from(".about__title",{x:-40, opacity:0, duration:.6, ease:"power3.out"})
    .from(".about__lead",{x:-40, opacity:0, duration:.6}, "-=.4")
    .from(".about__text",{y:20, opacity:0, duration:.6}, "-=.4")
    .from(".about__cta",{scale:.8, opacity:0, duration:.4}, "-=.4")
    .from(".about__img--lg",{scale:.9, autoAlpha:0, duration:.8, ease:"power3.out"},"-=1")
    .from(".about__img--sm",{y:60, rotate:-15, autoAlpha:0, duration:.8, ease:"power3.out"},"-=.6");
})();