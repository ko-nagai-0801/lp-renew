<?php
/**
 * News Index サブヒーロー
 * template-parts/news-index-section-hero.php
 * 
 * @package LP_WP_Theme
 * @since 1.0.0
 */
if (!defined('ABSPATH')) exit;

$default_img = get_theme_file_uri('assets/img/news-hero.webp');
$img = get_the_post_thumbnail_url(null, 'full') ?: $default_img;

// 共通 subhero コンポーネントを利用
get_template_part('components/subhero', null, [
  'sub'       => 'NEWS',
  'title'     => 'お知らせ',
  'variant'   => 'news-index',   // ← variant 名にも index を付与
  'image_url' => $img,
  'parallax'       => true,
  'parallax_speed' => 0.35,
]);
