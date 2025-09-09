<?php

/**
 * GA4 + Consent Mode v2（同意管理バナー & 設定一式 — コンパクトUI版）
 * inc/analytics.php
 *
 * @package LP_WP_Theme
 * @since 1.0.0
 *
 * 更新履歴:
 * - 1.2.0 (2025-09-09): UIを「フッターバー型（コンパクト）」に刷新。レイアウトを1行化、SPは2段組に自動折返し。
 * - 1.1.1 (2025-09-09): Cookie削除を全パスで試行・API強化・堅牢化
 * - 1.1.0 (2025-09-09): 再表示機能追加（?consent=reset / window.lpConsent.reset()）
 * - 1.0.1 (2025-09-09): クリック処理を堅牢化
 * - 1.0.0: 初版（default denied → 同意後 update、cookieless 対応）
 */
if (!defined('ABSPATH')) exit;

/* =========================================================
 *  <head>：Consent Mode → gtag.js → config（推奨順）
 *  計測IDは wp-config.php で定義：
 *    define('LP_GA_MEASUREMENT_ID', 'G-XXXXXXXXXX');
 * =======================================================*/
add_action('wp_head', function () {
    if (!defined('LP_GA_MEASUREMENT_ID') || !LP_GA_MEASUREMENT_ID) return;
    $mid = LP_GA_MEASUREMENT_ID;
?>
    <!-- Consent Mode v2（default=denied, cookieless で初期化） -->
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }

        gtag('consent', 'default', {
            ad_storage: 'denied',
            analytics_storage: 'denied',
            ad_user_data: 'denied',
            ad_personalization: 'denied',
            wait_for_update: 500
        });

        // 既存Cookieの同意状態（lp_consent_v2 = granted/denied）を復元
        (function() {
            try {
                var m = document.cookie.match(/(?:^| )lp_consent_v2=(granted|denied)(?=;|$)/);
                if (m && m[1] && typeof gtag === 'function') {
                    var s = m[1];
                    gtag('consent', 'update', {
                        ad_storage: s,
                        analytics_storage: s,
                        ad_user_data: s,
                        ad_personalization: s
                    });
                }
            } catch (e) {}
        })();
    </script>

    <!-- gtag.js -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo esc_attr($mid); ?>"></script>
    <script>
        gtag('js', new Date());
        gtag('config', '<?php echo esc_js($mid); ?>', {
            send_page_view: true
        });
    </script>
<?php
}, 1);


/* =========================================================
 *  フッター：同意バナー（コンパクトUI）＋JS
 *  - 初回表示
 *  - ?consent=reset で強制再表示
 *  - window.lpConsent API（show/hide/reset/state）
 * =======================================================*/
add_action('wp_footer', function () {
    $privacy_url = home_url('/privacy/');
?>
    <style>
        /* ===== コンパクトUI：画面下バー固定（白・境界線のみ） ===== */
        .lp-consent {
            position: fixed;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 2147483000;
            background: #fff;
            color: #222;
            border-top: 1px solid rgba(0, 0, 0, .12);
            padding: 10px 12px;
            display: none;
            font-size: 14px;
            line-height: 1.6;
        }

        .lp-consent__inner {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr auto;
            gap: .75rem;
            align-items: center;
        }

        .lp-consent__text {
            margin: 0;
        }

        .lp-consent a {
            color: #0b57d0;
            text-decoration: underline;
        }

        .lp-consent__actions {
            display: flex;
            gap: .5rem;
            justify-self: end;
        }

        .lp-btn {
            appearance: none;
            border-radius: 6px;
            padding: .5rem .9rem;
            cursor: pointer;
            font-weight: 500;
        }

        .lp-btn--ghost {
            border: 1px solid #c7c7c7;
            background: #fff;
            color: #222;
        }

        .lp-btn--primary {
            border: 1px solid #111;
            background: #111;
            color: #fff;
        }

        /* SPは上下2段に折り返す */
        @media (max-width: 576px) {
            .lp-consent__inner {
                grid-template-columns: 1fr;
            }

            .lp-consent__actions {
                justify-self: stretch;
            }

            .lp-consent__actions .lp-btn {
                width: 100%;
            }
        }

        /* ダークモードの軽微調整（任意） */
        @media (prefers-color-scheme: dark) {
            .lp-consent {
                background: #161616;
                color: #f2f2f2;
                border-top-color: rgba(255, 255, 255, .16);
            }

            .lp-btn--ghost {
                border-color: #555;
                background: transparent;
                color: #f2f2f2;
            }

            .lp-btn--primary {
                border-color: #f2f2f2;
                background: #f2f2f2;
                color: #111;
            }

            .lp-consent a {
                color: #9bd0ff;
            }
        }
    </style>

    <!-- バナー本体（テキスト左 / ボタン右） -->
    <div id="lp-consent" class="lp-consent" role="dialog" aria-live="polite" aria-label="Cookie consent">
        <div class="lp-consent__inner">
            <p class="lp-consent__text">
                当サイトは、Webサイトにおけるお客様の利用状況の把握のためにCookie使用しています。
                「同意する」ボタンクリックで、当サイトでのCookieの使用に同意することになります。
                <a href="<?php echo esc_url($privacy_url); ?>">プライバシーポリシー</a>
            </p>
            <div class="lp-consent__actions">
                <button id="lp-consent-reject" type="button" class="lp-btn lp-btn--ghost" aria-label="Cookieの使用に同意しない">同意しない</button>
                <button id="lp-consent-accept" type="button" class="lp-btn lp-btn--primary" aria-label="Cookieの使用に同意する">同意する</button>
            </div>
        </div>
    </div>

    <script>
        (function() {
            var el = document.getElementById('lp-consent');
            if (!el) return;

            /* ---------- Cookieユーティリティ ---------- */
            function getCookie(k) {
                var m = document.cookie.match(new RegExp('(?:^| )' + k + '=([^;]+)'));
                return m ? decodeURIComponent(m[1]) : '';
            }

            function setCookie(k, v, days) {
                var s = days * 24 * 60 * 60;
                document.cookie = k + '=' + encodeURIComponent(v) + ';path=/;max-age=' + s;
            }
            // すべてのパス候補で削除（サブディレクトリ環境の削除漏れ対策）
            function delCookieAllPaths(name) {
                var paths = ['/'];
                var segs = location.pathname.split('/').filter(Boolean);
                for (var i = 0; i < segs.length; i++) {
                    paths.push('/' + segs.slice(0, i + 1).join('/') + '/');
                    paths.push('/' + segs.slice(0, i + 1).join('/'));
                }
                paths = Array.from(new Set(paths));
                paths.forEach(function(p) {
                    document.cookie = name + '=; Max-Age=0; path=' + p;
                });
            }

            /* ---------- Consent更新（Consent Mode v2の4項目） ---------- */
            function updateConsent(granted) {
                var s = granted ? 'granted' : 'denied';
                try {
                    if (typeof gtag === 'function') {
                        gtag('consent', 'update', {
                            ad_storage: s,
                            analytics_storage: s,
                            ad_user_data: s,
                            ad_personalization: s
                        });
                    }
                } catch (e) {}
                setCookie('lp_consent_v2', s, 365);
                el.style.display = 'none';
            }

            /* ---------- 初期表示：?consent=reset or 未同意 ---------- */
            var qs = new URLSearchParams(location.search);
            if (qs.get('consent') === 'reset') {
                delCookieAllPaths('lp_consent_v2');
                el.style.display = 'block';
                if (history.replaceState) {
                    qs.delete('consent');
                    var u = location.pathname + (qs.toString() ? ('?' + qs) : '') + location.hash;
                    history.replaceState(null, '', u);
                }
            } else {
                if (!getCookie('lp_consent_v2')) el.style.display = 'block';
            }

            /* ---------- クリック ---------- */
            var acceptBtn = document.querySelector('#lp-consent-accept');
            var rejectBtn = document.querySelector('#lp-consent-reject');
            acceptBtn && acceptBtn.addEventListener('click', function(e) {
                e.preventDefault();
                updateConsent(true);
            });
            rejectBtn && rejectBtn.addEventListener('click', function(e) {
                e.preventDefault();
                updateConsent(false);
            });

            /* ---------- グローバルAPI（任意で使える） ---------- */
            window.lpConsent = {
                show: function() {
                    el.style.display = 'block';
                },
                hide: function() {
                    el.style.display = 'none';
                },
                reset: function() {
                    delCookieAllPaths('lp_consent_v2');
                    updateConsent(false);
                    el.style.display = 'block';
                },
                state: function() {
                    return getCookie('lp_consent_v2') || 'unset';
                }
            };
        })();
    </script>
<?php
}, 99);
