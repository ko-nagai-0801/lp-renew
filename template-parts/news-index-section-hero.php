<?php

/**
 * News Index サブヒーロー呼び出し
 * template-parts/news-index-section-hero.php
 *
 * @package LP_WP_Theme
 * @since 1.1.0
 * 更新履歴:
 * - 1.1.0 2ページ目以降の挙動オプションを渡す例を追記
 * - 1.0.0 初版
 */
if (!defined('ABSPATH')) exit;

$default_img = get_theme_file_uri('assets/img/news-hero.webp');
$img = get_the_post_thumbnail_url(null, 'full') ?: $default_img;

get_template_part('components/subhero', null, [
  'sub'            => 'NEWS',
  'title'          => 'お知らせ',
  'variant'        => 'news-index',
  'image_url'      => $img,
  'parallax'       => true,
  'parallax_speed' => 0.35,
  'breadcrumbs_args' => [
    'blog_label'  => 'お知らせ一覧',
    'posts_index_slug' => 'news', // /news/ が一覧の1ページ目
    'show_post_category' => false, // 一覧なのでカテゴリは出さない
    // ▼要望に応じて切り替え
    'paged_index_label_clickable' => true, // 2ページ目以降も「お知らせ一覧」を1ページ目リンクにする
    'show_paged_suffix' => true, // 末尾の「ページ 2」を出す（消したいなら false）
  ],
]);
