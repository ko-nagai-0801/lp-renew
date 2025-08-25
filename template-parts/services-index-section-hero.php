<?php
/**
 * template-parts/services-index-section-hero.php
 * Services インデックス用サブヒーロー
 */
if (!defined('ABSPATH')) exit;

get_template_part('components/subhero', null, [
  'sub'        => 'Services',
  'title'      => '事業内容',
  'variant'    => 'services-index', // → .subhero--services-index
  'image_url'      => get_theme_file_uri('assets/img/services-index-hero.webp'),
  // パララックス設定
  'parallax'       => true,
  'parallax_speed' => 0.35,     // 0.25〜0.5で微調整
]);
