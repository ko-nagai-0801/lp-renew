<?php
/**
 * すべてのRSS/Atomフィードを停止して410で返す
 * inc/seo/disable-feeds.php
 *
 * @package LP_WP_Theme
 * @since 1.0.0
 * 更新履歴:
 * - 1.0.0 (2025-09-16): 初版（do_feed系を410へ）
 */
if (!defined('ABSPATH')) exit;

function lp_disable_feeds_410() {
    status_header(410);
    header('X-Robots-Tag: noindex, nofollow', true);
    nocache_headers();
    wp_die(
        'このサイトはRSS/Atomフィードを提供していません（410 Gone）。<br><a href="' . esc_url(home_url('/news/')) . '">お知らせ一覧</a>をご覧ください。',
        'Feed disabled',
        ['response' => 410]
    );
}
// すべてのfeed系アクションをフック
add_action('do_feed',               'lp_disable_feeds_410', 1);
add_action('do_feed_rdf',           'lp_disable_feeds_410', 1);
add_action('do_feed_rss',           'lp_disable_feeds_410', 1);
add_action('do_feed_rss2',          'lp_disable_feeds_410', 1);
add_action('do_feed_atom',          'lp_disable_feeds_410', 1);
add_action('do_feed_rss2_comments', 'lp_disable_feeds_410', 1);
add_action('do_feed_atom_comments', 'lp_disable_feeds_410', 1);
