/**
 * GA4 送信用イベント束（CTA/外部リンク/ファイルDL/リード）
 * assets/js/ga-events.js
 *
 * @package LP_WP_Theme
 * @since 1.0.0
 *
 * 更新履歴:
 * - 1.0.0 (2025-09-09): 初版。最低限の委譲リスナーで安定送信。
 */

/* ヘルパ：安全に gtag を叩く */
function lpGA(event, params) {
  try {
    if (typeof gtag === "function") {
      gtag("event", event, params || {});
    }
  } catch (e) {
    /* noop */
  }
}

/* DOM準備後にバインド */
document.addEventListener("DOMContentLoaded", function () {
  /* 1) CTAクリック（data-cta でマーク） */
  document.body.addEventListener("click", function (e) {
    const a = e.target.closest("[data-cta]");
    if (!a) return;
    lpGA("cta_click", {
      section: a.getAttribute("data-section") || "",
      cta_id: a.id || "",
      cta_text: (a.innerText || "").trim().slice(0, 100),
      link_url: a.href || "",
    });
  });

  /* 2) 外部リンク */
  document.body.addEventListener("click", function (e) {
    const a = e.target.closest('a[href^="http"]');
    if (!a) return;
    try {
      const url = new URL(a.href, location.href);
      if (url.hostname && url.hostname !== location.hostname) {
        lpGA("outbound_click", {
          link_domain: url.hostname,
          link_url: url.href,
          section: a.getAttribute("data-section") || "",
        });
      }
    } catch (_) {}
  });

  /* 3) ファイルダウンロード（拡張子判定） */
  const FILE_RE = /\.(pdf|docx?|xlsx?|pptx?|zip|csv|txt|mp3|mp4)$/i;
  document.body.addEventListener("click", function (e) {
    const a = e.target.closest("a[href]");
    if (!a) return;
    try {
      const url = new URL(a.href, location.href);
      if (FILE_RE.test(url.pathname)) {
        const name = url.pathname.split("/").pop() || "";
        const ext = (name.split(".").pop() || "").toLowerCase();
        lpGA("file_download", {
          file_name: name,
          file_extension: ext,
          section: a.getAttribute("data-section") || "",
        });
      }
    } catch (_) {}
  });

  /* 4) 問い合わせフォーム送信（data-ga-lead を付ける） */
  document.querySelectorAll("form[data-ga-lead]").forEach(function (form) {
    form.addEventListener("submit", function () {
      lpGA("generate_lead", {
        method: "form",
        form_id: form.id || "",
        form_name: form.getAttribute("name") || "",
        value: 1,
        currency: "JPY",
      });
    });
  });
});
