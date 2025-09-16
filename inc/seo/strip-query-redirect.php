<?php
/**
 * 余計なクエリを除去して正規URLへ301（ページ限定）
 * inc/seo/strip-query-redirect.php
 *
 * @package LP_WP_Theme
 * @since 1.0.0
 * 更新履歴:
 * - 1.0.0 (2025-09-16): 初版（privacy/from_page 等の除去）
 */
if (!defined('ABSPATH')) exit;

add_action('template_redirect', function () {
    // クエリ除去対象
    $strip = ['from_page', 'from_q', 'consent'];

    // 対象ページ（必要に応じて追加/削除）
    $targets = ['privacy', 'about-session', 'about-observation', 'about-trial'];

    if (is_page($targets)) {
        // 1つでも付いていたら除去して301
        foreach ($strip as $key) {
            if (isset($_GET[$key])) {
                global $wp;
                $current = home_url(add_query_arg([], $wp->request)); // 現在のパス
                $target  = remove_query_arg($strip, $current);        // クエリ除去
                if ($target !== $current) {
                    wp_safe_redirect($target, 301);
                    exit;
                }
                break;
            }
        }
    }
});
