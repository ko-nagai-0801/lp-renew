Vue.createApp({
  data() {
    return {
      selectedPlan: initialData.plan,
      videoLength: initialData.videoLength,
      videoSourceLength: initialData.videoSourceLength,
      photoCount: initialData.photoCount,
      options: [
        {
          id: "channelSetup",
          name: "チャンネル及びアカウントの立ち上げ",
          price: 1650,
          suffix: "円 / 件",
        },
        {
          id: "thumbnailCreation",
          name: "サムネイル作成",
          price: 3300,
          suffix: "円 / 枚",
        },
        {
          id: "photoInsertion",
          name: "写真素材の挿入",
          price: 330,
          suffix: "円 / 枚",
        },
        {
          id: "openingCreation",
          name: "オープニング作成",
          price: 3300,
          suffix: "円 ～",
        },
        {
          id: "endingCreation",
          name: "エンディング作成",
          price: 3300,
          suffix: "円 ～",
        },
        {
          id: "materialCreation",
          name: "素材作成",
          price: 3300,
          suffix: "円 ～",
        },
        {
          id: "paidPhotos",
          name: "有料写真や画像・イラストの挿入",
          priceInfo: "応相談",
        },
        {
          id: "paidBGM",
          name: "有料BGMの追加",
          priceInfo: "応相談",
        },
        {
          id: "planning",
          name: "企画・構成",
          price: 22000,
          suffix: "円 ～",
        },
      ],
      selectedOptions: initialData.options,
    };
  },
  methods: {
    formatPrice(value) {
      return new Intl.NumberFormat("ja-JP").format(value);
    },
  },
  computed: {
    totalEstimate() {
      let total = 0;
      let additionalMinutes = Math.max(this.videoLength - 1, 0);
      switch (this.selectedPlan) {
        case "simple":
          total += 5940 + additionalMinutes * 550;
          break;
        case "detailed":
          total += 9900 + additionalMinutes * 1100;
          break;
        case "photo":
          total += 4950 + additionalMinutes * 550;
          break;
      }
      this.selectedOptions.forEach((optionId) => {
        const option = this.options.find((o) => o.id === optionId);
        if (option && option.price) {
          total += option.price;
        }
      });
      return total;
    },
    hasConsultation() {
      return this.selectedOptions.some((optionId) => {
        const option = this.options.find((o) => o.id === optionId);
        return option && option.priceInfo === "応相談";
      });
    },
  },
}).mount("#app");

// flatpickr日時選択ツール用スクリプト
document.addEventListener('DOMContentLoaded', function () {
  // 次の営業日を計算する関数
  function getNextWeekday(date) {
    do {
      date.setDate(date.getDate() + 1);
    } while (date.getDay() == 0 || date.getDay() == 6); // 土日をスキップ
    return date;
  }

  // 次の営業日を取得
  let nextWeekday = getNextWeekday(new Date());

  // Placeholderを設定
  function formatDate(date, hour) {
    let formattedDate = flatpickr.formatDate(date, "Y-m-d");
    return `例：${formattedDate} ${hour}:00`;
  }

  document.getElementById('meeting1').placeholder = formatDate(nextWeekday, 10);
  document.getElementById('meeting2').placeholder = formatDate(nextWeekday, 11);
  document.getElementById('meeting3').placeholder = formatDate(nextWeekday, 13);

  function initializeFlatpickr(selector, hour) {
    flatpickr(selector, {
      enableTime: true,
      noCalendar: false,
      dateFormat: "Y-m-d H:i",
      minDate: "today",
      maxDate: new Date().fp_incr(365), // 1年間の日程を許可
      minTime: "09:00",
      maxTime: "14:00",
      locale: flatpickr.l10ns.ja, // 日本語ロケールを適用
      weekNumbers: true, // 週番号を表示
      disable: [
        function(date) {
          // 土日を無効にする
          return (date.getDay() === 0 || date.getDay() === 6);
        }
      ],
      onOpen: function(selectedDates, dateStr, instance) {
        // フォーカスが当たった時に、次の営業日の指定した時間を設定
        if (!instance.input.value) {
          let nextWeekday = getNextWeekday(new Date());
          nextWeekday.setHours(hour, 0); // 指定した時間に設定
          instance.setDate(nextWeekday, true);
        }
      }
    });
  }

  // 各inputにflatpickrを適用
  initializeFlatpickr("#meeting1", 10); // 第一希望は10時
  initializeFlatpickr("#meeting2", 11); // 第二希望は11時
  initializeFlatpickr("#meeting3", 13); // 第三希望は13時
});

