// スクロール時のヘッダースタイル変更
// window.addEventListener("scroll", function () {
//   const header = document.querySelector(".l-header");
//   header.classList.toggle("scroll-nav", window.scrollY > 100);
// });

document.addEventListener("DOMContentLoaded", function() {
  // ハンバーガーメニューのトグル
  const btn = document.querySelector(".c-btn-menu");
  const nav = document.querySelector(".l-header-nav");
  const navLinks = document.querySelectorAll(".p-header-nav-link");

  if (btn && nav && navLinks.length > 0) {
    // ハンバーガーメニューのトグル機能
    btn.addEventListener("click", function () {
      btn.classList.toggle("active");
      nav.classList.toggle("active");
      console.log("Menu toggled"); // デバッグ用
    });

    // ナビゲーションリンクをクリックした際にメニューを閉じる
    navLinks.forEach(link => {
      console.log("Adding event listener to:", link); // デバッグ用
      link.addEventListener("click", function () {
        btn.classList.remove("active");
        nav.classList.remove("active");
        console.log("Link clicked, menu closed"); // デバッグ用
      });
    });
  } else {
    console.error("One or more elements not found:", btn, nav, navLinks);
  }
});

// Swiperライブラリを使用し、スライドアニメーションを実装（TOPページでのみ使用）
const swiper = new Swiper(".js-swiper-container", {
  // Optional parameters
  autoplay: {
    delay: 400,
    disableOnInteraction: false,
  },
  speed: 4000,
  loop: true,
  effect: "fade",
});

// scroll-itemクラスを持つ要素にCSSアニメーションなどを追加
let fadeInTarget = document.querySelectorAll(".scroll-item");
window.addEventListener("scroll", () => {
  for (let i = 0; i < fadeInTarget.length; i++) {
    const rect = fadeInTarget[i].getBoundingClientRect().top;
    const scroll = window.scrollY || document.documentElement.scrollTop;
    const offset = rect + scroll;
    const windowHeight = window.innerHeight;
    if (scroll > offset - windowHeight + 150) {
      fadeInTarget[i].classList.add("scroll-in");
    }
  }
});

// ページTOPに戻るボタンの制御
window.addEventListener("scroll", function () {
  const topBtn = document.getElementById("toTopBtn");
  const scroll = window.scrollY;
  if (scroll > 500) {
    topBtn.style.opacity = 1;
  } else {
    topBtn.style.opacity = 0;
  }
});

