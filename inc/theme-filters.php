<?php
/**
 * テーマ共通フィルタ群
 * inc/theme-filters.php
 *
 * @package LP_WP_Theme
 * @since 1.0.0
 *
 * 変更履歴:
 * - 1.0.0 (2025-09-03): タイトル未入力時は "No Title" を返すフィルタを追加。
 */

if (!defined('ABSPATH')) exit;

/**
 * タイトル未入力時は自動で "No Title" にフォールバック
 *
 * 注意:
 * - 管理画面では変更しない（一覧や編集画面に影響させないため）
 * - 先頭末尾の空白やタグを削った上で空と判定
 */
add_filter('the_title', function ($title, $post_id = 0) {
    if (is_admin()) return $title; // 管理画面は素通し

    $t = trim( wp_strip_all_tags( (string) $title ) );
    return ($t === '') ? 'No Title' : $title;
}, 10, 2);
