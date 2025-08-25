<?php
/**
 * Join Us サブヒーロー（components 使用）
 * template-parts/join-section-hero.php
 */
if (!defined('ABSPATH')) exit;

// 背景画像：ページのアイキャッチ優先、なければテーマ内のデフォルト画像にフォールバック
$default_img = get_theme_file_uri('assets/img/join-hero.webp');
$img = get_the_post_thumbnail_url(null, 'full') ?: $default_img;

get_template_part('components/subhero', null, [
  'sub'            => 'Join Us',
  'title'          => '利用者募集',
  'variant'        => 'join',
  'image_url'      => $img,
  // パララックス設定
  'parallax'       => true,
  'parallax_speed' => 0.35,     // 0.25〜0.5で微調整
]);
