/**
 * assets/js/front-page.js
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
if (toggle)
  toggle.addEventListener("click", () => {
    toggle.classList.toggle("is-active"); // ← ボタンの線をアニメ
    document.querySelector(".header__nav")?.classList.toggle("is-open");
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


/* ---------------------------------------------------------
 * Services – カードに連番インデックス（--i）を付与
 * ------------------------------------------------------ */
(() => {
  const sec = document.querySelector('#services');
  if (!sec) return;
  sec.querySelectorAll('.services__card').forEach((li, i) => {
    li.style.setProperty('--i', i);
  });
})();

/* ---------------------------------------------------------
 * NEWS – 各アイテムが順に“奥からパタッ”と倒れてくる
 * ------------------------------------------------------ */
(() => {
  const sec  = document.querySelector('#news');
  if (!sec) return;

  const list  = sec.querySelector('.news__list');
  const rows  = sec.querySelectorAll('.news__item .news__link'); // ← 各行のaを動かす
  if (!rows.length) return;

  if (!(window.gsap && window.ScrollTrigger)) return;
  gsap.registerPlugin(ScrollTrigger);

  const prefersReduced =
    window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  // 初期状態：少し奥に傾けて浮かせておく
  gsap.set(rows, {
    transformOrigin: 'top center',
    rotateX: -65,
    y: -8,
    opacity: 0
  });

  // リストが見えたら“順に”実行（1回だけ）
  ScrollTrigger.create({
    trigger: list,
    start: 'top 80%',
    once: true,
    onEnter: () => {
      if (prefersReduced) {
        gsap.to(rows, { opacity: 1, y: 0, duration: 0.4, stagger: 0.08, ease: 'power2.out' });
        return;
      }
      gsap.to(rows, {
        rotateX: 0,
        y: 0,
        opacity: 1,
        duration: 0.9,
        ease: 'back.out(1.5)',
        stagger: 0.12    // ← ここで“1行ずつ順番に”
      });
    }
  });
})();