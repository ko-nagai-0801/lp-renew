<?php
/**
 * ドキュメントタイトル整形（区切り変更＆空タイトルの簡易フォールバック）
 * inc/seo/document-title.php
 *
 * @package LP_WP_Theme
 * @since 1.0.0
 *
 * 更新履歴:
 * - 1.0.0 (2025-10-02): 区切りを「｜」に統一。title が空のときの保険を追加。
 */

if (!defined('ABSPATH')) exit;

/* 区切り文字を変更（例： “｜” ） */
add_filter('document_title_separator', function ($sep) {
    return '｜';
});

/* タイトル部品の整形：空回避の保険（Yoastと競合しないよう「空のときだけ」埋める） */
add_filter('document_title_parts', function ($parts) {
    if (empty($parts['title'])) {
        if (is_singular()) {
            $parts['title'] = trim(wp_strip_all_tags(get_the_title()));
        } elseif (is_home() || is_front_page()) {
            $parts['title'] = get_bloginfo('name');
        } elseif (is_archive()) {
            $parts['title'] = get_the_archive_title();
        } elseif (is_search()) {
            $parts['title'] = sprintf('検索結果：%s', get_search_query());
        } elseif (is_404()) {
            $parts['title'] = 'ページが見つかりません';
        } else {
            $parts['title'] = get_bloginfo('name');
        }
    }

    // サイト名は末尾に（Yoastの並びと喧嘩しない軽い整形）
    if (isset($parts['site'])) {
        $site = $parts['site'];
        unset($parts['site']);
        $parts['site'] = $site;
    }
    return $parts;
}, 20);
