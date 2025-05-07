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

document.addEventListener("DOMContentLoaded", function() {
  const {
    createApp
  } = Vue;

  createApp({
    data() {
      return {
        selectedPlan: 'detailed', // デフォルトで「こだわりプラン」を選択
        videoLength: 1,
        videoSourceLength: 1,
        photoCount: 0,
        options: [{
            id: 'channelSetup',
            name: 'チャンネル及びアカウントの立ち上げ',
            price: 1650,
            suffix: '円 / 件'
          },
          {
            id: 'thumbnailCreation',
            name: 'サムネイル作成',
            price: 3300,
            suffix: '円 / 枚'
          },
          {
            id: 'photoInsertion',
            name: '写真素材の挿入',
            price: 330,
            suffix: '円 / 枚'
          },
          {
            id: 'openingCreation',
            name: 'オープニング作成',
            price: 3300,
            suffix: '円～'
          },
          {
            id: 'endingCreation',
            name: 'エンディング作成',
            price: 3300,
            suffix: '円～'
          },
          {
            id: 'materialCreation',
            name: '素材作成（説明画像など）',
            price: 3300,
            suffix: '円～'
          },
          {
            id: 'paidPhotos',
            name: '有料写真や画像・イラストの挿入',
            priceInfo: '応相談'
          },
          {
            id: 'paidBGM',
            name: '有料BGMの追加',
            priceInfo: '応相談'
          },
          {
            id: 'planning',
            name: '企画・構成',
            price: 22000,
            suffix: '円～'
          }
        ],
        selectedOptions: []
      };
    },
    methods: {
      formatPrice(value) {
        return new Intl.NumberFormat('ja-JP').format(value);
      },
      displayPrice(option) {
        if (option.priceInfo) {
          return option.priceInfo;
        } else {
          return `${this.formatPrice(option.price)}${option.suffix || ''}`;
        }
      }
    },
    computed: {
      totalEstimate() {
        let basePrice = 0;
        switch (this.selectedPlan) {
          case 'simple':
            basePrice = 5940 + (this.videoLength - 1) * 550;
            break;
          case 'detailed':
            basePrice = 9900 + (this.videoLength - 1) * 1100;
            break;
          case 'photo':
            basePrice = 4950 + (this.videoLength - 1) * 550;
            break;
        }

        let materialCost = 0;
        if (this.selectedPlan === 'simple' || this.selectedPlan === 'detailed') {
          const extraLength = Math.max(this.videoSourceLength - (this.videoLength + 10), 0);
          materialCost = extraLength * (this.selectedPlan === 'simple' ? 330 : 440);
        } else if (this.selectedPlan === 'photo') {
          const extraPhotos = Math.max(this.photoCount - (this.videoLength * 20), 0);
          materialCost = Math.ceil(extraPhotos / 5) * 550;
        }

        let optionCost = this.selectedOptions.reduce((sum, optionId) => {
          const option = this.options.find(o => o.id === optionId);
          return sum + (option.price || 0);
        }, 0);

        return this.formatPrice(basePrice + materialCost + optionCost);
      },
      hasConsultation() {
        return this.selectedOptions.some(optionId => {
          const option = this.options.find(o => o.id === optionId);
          return option && option.priceInfo === '応相談';
        });
      }
    }
  }).mount('#app');
});