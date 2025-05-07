document.addEventListener("DOMContentLoaded", function () {
    const menuButton = document.querySelector(".header-menu-button");
    const menuNav = document.querySelector(".header-nav");
    const menuLines = document.querySelector(".header-menu-button-line");
    const menuLinks = document.querySelectorAll(".header-nav-menu-link");
    const header = document.querySelector(".header");
    const main = document.querySelector("main");
  
    // TOPページかどうかを判定（<main>タグにisTopクラスがあるか）
    function isTopPage() {
      return main && main.classList.contains("isTop");
    }
  
    if (isTopPage()) {
      // スクロール時の透過処理（TOPページのみ適用）
      window.addEventListener("scroll", function () {
        if (window.scrollY === 0) {
          header.classList.add("header-transparent"); // 最上部なら透過
        } else {
          header.classList.remove("header-transparent"); // スクロール開始で透過解除
        }
      });
  
      // 初回ロード時の透過チェック
      if (window.scrollY === 0) {
        header.classList.add("header-transparent");
      }
    }
  
    // メニュー開閉処理
    menuButton.addEventListener("click", function () {
      toggleMenu();
    });
  
    // ナビゲーションリンクをクリックしたらメニューを閉じる
    menuLinks.forEach(link => {
      link.addEventListener("click", function () {
        closeMenu();
      });
    });
  
    // 画面サイズが変更された時の処理
    window.addEventListener("resize", function () {
      if (window.innerWidth > 980) {
        closeMenu();
      }
    });
  
    // メニューを開閉する関数
    function toggleMenu() {
      const isOpen = menuNav.classList.contains("active");
      if (isOpen) {
        closeMenu();
      } else {
        openMenu();
      }
    }
  
    // メニューを開く関数
    function openMenu() {
      menuNav.classList.add("active");
      menuButton.classList.add("active");
      menuLines.classList.add("active");
    }
  
    // メニューを閉じる関数
    function closeMenu() {
      menuNav.classList.remove("active");
      menuButton.classList.remove("active");
      menuLines.classList.remove("active");
    }
  });
  