/**
 * お問い合わせフォームUI拡張（必須の空欄検知・文字数カウンタ・軽いアニメ）
 * assets/js/contact.js
 *
 * @package LP_WP_Theme
 * @since 1.0.0
 *
 * 更新履歴:
 * - 1.3.2 (2025-09-09): 同意チェックの change で即時エラー表示（soft:false）を追加
 * - 1.3.1 (2025-09-09): カウンタの表示上限と警告閾値を softMax に統一
 * - 1.3.0 (2025-09-09): ソフト上限=1000、ハード上限=4000。同意未チェック時シェイク。未入力MSG個別化
 * - 1.2.0 (2025-09-09): 既定選択/同意required/各種調整
 * - 1.0.1 (2025-09-09): 純JS化
 * - 1.0.0: 初版
 */
(() => {
  "use strict";

  document.addEventListener("DOMContentLoaded", () => {
    /** @type {HTMLFormElement|null} */
    const form = document.querySelector(".contact__form");
    if (!form) return;

    const $ = (sel, root = form) => root.querySelector(sel);
    const $$ = (sel, root = form) => Array.from(root.querySelectorAll(sel));

    /* ===== 初期セットアップ ===== */
    // 種別：未選択なら「お仕事のご相談」を既定でチェック
    (() => {
      const radios = $$('input[type="radio"][name="type"]');
      if (!radios.length) return;
      const anyChecked = radios.some(
        (r) => r instanceof HTMLInputElement && r.checked
      );
      if (!anyChecked) {
        const target =
          radios.find(
            (r) => r instanceof HTMLInputElement && r.value === "お仕事のご相談"
          ) || radios[0];
        if (target && target instanceof HTMLInputElement) target.checked = true;
      }
    })();

    // 同意チェックに required を付与 + 変更時に即時エラー/解除
    (() => {
      const agree = form.querySelector('input[name="agree"]');
      if (
        agree &&
        agree instanceof HTMLInputElement &&
        agree.type === "checkbox"
      ) {
        agree.setAttribute("required", "required");
        agree.addEventListener("change", () => {
          // ここは「即時に」文言を出したいので soft:false
          validateField(agree, { soft: false });
        });
      }
    })();

    /* ===== 文字数カウンタ ===== */
    const SOFT_LIMIT = 1000; // 赤表示のみ
    const HARD_CAP_DEFAULT = 4000; // 実入力上限（HTMLの maxlength が優先）

    /** @type {HTMLTextAreaElement|null} */
    const msg = $("#cf-message");
    if (msg) setupCounter(msg);

    function setupCounter(textarea) {
      const hardCap =
        textarea.maxLength > 0 ? textarea.maxLength : HARD_CAP_DEFAULT;
      textarea.setAttribute("maxlength", String(hardCap));

      let note = textarea
        .closest(".contact__field")
        ?.querySelector(".contact__note--small");
      if (!note) {
        note = document.createElement("p");
        note.className = "contact__note contact__note--small";
        textarea.closest(".contact__field")?.appendChild(note);
      }
      const softMax = Math.min(SOFT_LIMIT, hardCap);
      note.innerHTML =
        '<span class="contact__counter">' +
        '<span class="contact__counter-current">0</span> / ' +
        '<span class="contact__counter-max">' +
        softMax +
        "</span>文字" +
        "</span>";

      const wrap = note.querySelector(".contact__counter");
      const cur = note.querySelector(".contact__counter-current");

      const update = () => {
        const len = textarea.value.length;
        cur.textContent = String(len);
        const isWarning = softMax - len <= 10 && len <= softMax;
        const isOver = len > softMax;
        wrap.classList.toggle("is-warning", isWarning);
        wrap.classList.toggle("is-over", isOver);
      };
      textarea.addEventListener("input", update);
      update();
    }

    /* ===== 必須の空欄チェック ===== */
    form.addEventListener(
      "blur",
      (ev) => {
        const el = ev.target;
        if (!el || !(el instanceof Element)) return;
        if (
          !el.matches(
            '[required], textarea[required], input[type="radio"][name="type"]'
          )
        )
          return;
        validateField(el, { soft: false });
      },
      true
    );

    form.addEventListener("input", (ev) => {
      const el = ev.target;
      if (!el || !(el instanceof Element)) return;

      if (el.matches("[required], textarea[required]")) {
        if (
          el.tagName === "INPUT" &&
          /** @type {HTMLInputElement} */ (el).type === "radio"
        )
          return;
        // 入力中はソフトに（エラー文は出さずクラスのみ）※同意は change でハードに出す
        validateField(el, { soft: true });
      }
      if (
        el.tagName === "INPUT" &&
        /** @type {HTMLInputElement} */ (el).name === "email_confirm"
      ) {
        validateField(el, { soft: true });
      }
    });

    /* ===== 送信時の最終チェック + 同意未チェックでシェイク ===== */
    form.addEventListener("submit", (ev) => {
      const requiredEls = $$(
        '[required], textarea[required], input[type="radio"][name="type"]'
      );
      const invalids = [];
      requiredEls.forEach((el) => {
        if (!validateField(el, { soft: false })) invalids.push(el);
      });

      if (invalids.length) {
        const hasAgreeError = invalids.some(
          (el) =>
            el.tagName === "INPUT" &&
            /** @type {HTMLInputElement} */ (el).name === "agree"
        );
        if (hasAgreeError) {
          form.classList.add("is-shake");
          setTimeout(() => form.classList.remove("is-shake"), 450);
        }

        ev.preventDefault();
        const first = invalids[0];

        // ラジオなら同名の最初のラジオにフォーカス
        let focusEl = first;
        if (
          first.tagName === "INPUT" &&
          /** @type {HTMLInputElement} */ (first).type === "radio"
        ) {
          const name = /** @type {HTMLInputElement} */ (first).name;
          const candidate = form.querySelector(
            'input[type="radio"][name="' + name + '"]'
          );
          if (candidate) focusEl = candidate;
        }

        (
          first.closest(".contact__field") ||
          first.closest("fieldset") ||
          first
        ).scrollIntoView({ behavior: "smooth", block: "center" });
        setTimeout(() => {
          if (focusEl && focusEl instanceof HTMLElement)
            focusEl.focus({ preventScroll: true });
        }, 200);
      }
    });

    /* ===== バリデーション本体 ===== */
    function validateField(el, opts) {
      const soft = !!(opts && opts.soft);
      const field =
        el.closest(".contact__field") || el.closest("fieldset.contact__field");
      removeClientErrors(field);

      let ok = true,
        msg = "";

      if (
        el.tagName === "INPUT" &&
        /** @type {HTMLInputElement} */ (el).type === "radio"
      ) {
        const name = /** @type {HTMLInputElement} */ (el).name;
        const group = $$('input[type="radio"][name="' + name + '"]', form);
        const checked = group.some(
          (r) => r instanceof HTMLInputElement && r.checked
        );
        if (!checked) {
          ok = false;
          msg = "お問い合わせ種別を選択してください。";
        }
      } else if (el.tagName === "TEXTAREA") {
        const ta = /** @type {HTMLTextAreaElement} */ (el);
        const val = ta.value.trim();
        if (!val) {
          ok = false;
          msg = "内容を入力してください。";
        } else if (val.length < 10) {
          ok = false;
          msg = "お問い合わせ内容は10〜1000文字で入力してください。";
        }
      } else if (
        el.tagName === "INPUT" &&
        /** @type {HTMLInputElement} */ (el).type === "email"
      ) {
        const input = /** @type {HTMLInputElement} */ (el);
        const v = input.value.trim();
        if (!v) {
          ok = false;
          msg = "メールアドレスを入力してください。";
        } else if (!input.checkValidity()) {
          ok = false;
          msg = "メールアドレスの形式が正しくありません。";
        }
        if (ok && input.name === "email_confirm") {
          const e1 = form.querySelector('input[name="email"]');
          if (
            e1 &&
            e1 instanceof HTMLInputElement &&
            e1.value.trim() &&
            v.toLowerCase() !== e1.value.trim().toLowerCase()
          ) {
            ok = false;
            msg = "確認用メールアドレスが一致しません。";
          }
        }
      } else if (el.tagName === "INPUT") {
        const input = /** @type {HTMLInputElement} */ (el);
        if (
          input.type === "text" ||
          input.type === "tel" ||
          input.type === "search"
        ) {
          const v = input.value.trim();
          if (!v) {
            if (input.name === "name") {
              ok = false;
              msg = "お名前を入力してください。";
            } else if (input.name === "name_kana") {
              ok = false;
              msg = "ふりがなを入力してください。";
            } else {
              ok = false;
              msg = "未入力です。";
            }
          }
          if (
            ok &&
            input.name === "name_kana" &&
            v &&
            !/^[ぁ-ゟ゠ー\s　]+$/u.test(v)
          ) {
            ok = false;
            msg = "ふりがなは「ひらがな」で入力してください。";
          }
        }
        if (input.type === "checkbox" && input.required && !input.checked) {
          ok = false;
          msg = "プライバシーポリシーへの同意をお願いします。";
        }
      }

      if (el instanceof HTMLElement) {
        el.setAttribute("aria-invalid", ok ? "false" : "true");
        el.classList.toggle("is-invalid", !ok);
      }
      if (!ok && !soft) {
        createClientError(field, msg);
        flash(field);
      } else if (ok) {
        field && field.classList.remove("is-invalid");
      }
      return ok;
    }

    function createClientError(container, message) {
      if (!container) return;
      const p = document.createElement("p");
      p.className = "contact__error contact__error--client";
      p.setAttribute("role", "alert");
      p.textContent = message;
      container.appendChild(p);
      container.classList.add("is-invalid");
    }

    function removeClientErrors(container) {
      if (!container) return;
      container
        .querySelectorAll(".contact__error--client")
        .forEach((n) => n.remove());
      container.classList.remove("is-invalid");
    }

    function flash(container) {
      if (!container) return;
      container.classList.add("is-flash");
      setTimeout(() => container.classList.remove("is-flash"), 400);
    }
  });
})();
