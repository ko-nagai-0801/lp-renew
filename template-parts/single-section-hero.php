<?php
/**
 * Single Post サブヒーロー
 * template-parts/single-section-hero.php
 *
 * @package LP_WP_Theme
 */
if (!defined('ABSPATH')) exit;

// 背景画像：アイキャッチ優先、なければデフォルト
$default_img = get_theme_file_uri('assets/img/news-post-hero.webp');
$img = get_the_post_thumbnail_url(null, 'full') ?: $default_img;

// サブテキスト：最初のカテゴリ名 or NEWS
$cats = get_the_category();
$sub  = !empty($cats) ? $cats[0]->name : 'NEWS';

get_template_part('components/subhero', null, [
  'sub'            => 'NEWS',
  'title'          => get_the_title(),
  'variant'        => 'news',
  'tag' => 'h2',
  'image_url'      => $img,
  'parallax'       => true,
  'parallax_speed' => 0.30,
  'breadcrumbs_args' => [
    'blog_label'         => 'お知らせ一覧',
    'posts_index_slug'   => 'news',
    'show_post_category' => false,
  ],
]);
