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
};
