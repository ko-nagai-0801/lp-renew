<?php

/**
 * Cookie同意リセット用ショートコード群
 * inc/shortcodes.php
 *
 * @package LP_WP_Theme
 * @since 1.0.0
 *
 * 更新履歴:
 * - 1.0.0 (2025-09-09): [lp_consent_reset] を追加。window.lpConsent.reset() を優先し、無ければ ?consent=reset にフォールバック。
 */
if (!defined('ABSPATH')) exit;

/**
 * [lp_consent_reset] ショートコードの本体
 * - 使用例: [lp_consent_reset text="Cookie設定をやり直す" class="link-underline"]
 * - クリック時:
 *   1) window.lpConsent.reset() があればそれを呼ぶ（JS有効・analytics.php読込済）
 *   2) 無ければ `?consent=reset` 付きURLへ遷移（サーバ側で再表示）
 */
function lp_shortcode_consent_reset($atts)
{
    // 属性（デフォルト値）
    $a = shortcode_atts([
        'text'  => 'Cookie設定をやり直す',
        'class' => 'link-underline',
    ], $atts, 'lp_consent_reset');

    // フォールバック用のクエリ（現在URLに ?consent=reset を付与）
    $href = esc_url(add_query_arg('consent', 'reset'));

    // aタグを返す（JSが有ればAPI、無ければhrefへ）
    $html  = '<a href="' . $href . '"';
    $html .= ' class="' . esc_attr($a['class']) . '"';
    $html .= ' onclick="if(window.lpConsent){window.lpConsent.reset();return false;}"';
    $html .= '>';
    $html .= esc_html($a['text']);
    $html .= '</a>';

    return $html;
}

/**
 * 初期化時にショートコードを登録
 * - initでの登録は、プラグインや他テーマとの読み込み順のズレに強い
 */
add_action('init', function () {
    add_shortcode('lp_consent_reset', 'lp_shortcode_consent_reset');
});
