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
