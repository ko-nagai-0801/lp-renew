<?php

/**
 * /services/web サブヒーロー
 * template-parts/services/web-section-hero.php
 * 
 * @package LP_WP_Theme
 * @since 1.0.0
 */
if (!defined('ABSPATH')) exit;

// 背景画像：ページのアイキャッチ優先、なければテーマ内のデフォルト画像にフォールバック
$default_img = get_theme_file_uri('assets/img/services/web-hero.webp');
$img = get_the_post_thumbnail_url(null, 'full') ?: $default_img;

get_template_part('components/subhero', null, [
  'sub'       => 'Web Production',
  'title'     => 'Web制作',
  'variant'   => 'web',
  'image_url' => $img,
  // パララックス設定
  'parallax'       => true,
  'parallax_speed' => 0.35,     // 0.25〜0.5で微調整
]);
