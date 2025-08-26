<?php

/**
 * About サブヒーロー
 * template-parts/about-section-hero.php
 * 
 * @package LP_WP_Theme
 * @since 1.0.0
 */
if (!defined('ABSPATH')) exit;

// 背景画像：ページのアイキャッチ優先、なければテーマ内のデフォルト画像にフォールバック
$default_img = get_theme_file_uri('assets/img/about-hero.webp');
$img = get_the_post_thumbnail_url(null, 'full') ?: $default_img;

get_template_part('components/subhero', null, [
  'sub'        => 'About Us',
  'title_html' => '<span id="LiNE">ＬｉＮＥ</span>&nbsp;<span id="PARK">ＰＡＲＫ</span>について',
  'variant'    => 'about',
  'image_url'      => $img,
  // パララックス設定
  'parallax'       => true,
  'parallax_speed' => 0.35,     // 0.25〜0.5で微調整
]);
