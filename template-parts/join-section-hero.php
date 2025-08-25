<?php
/**
 * Join Us サブヒーロー（components 使用）
 * template-parts/join-section-hero.php
 */
if (!defined('ABSPATH')) exit;

get_template_part('components/subhero', null, [
  'sub'            => 'Join Us',
  'title'          => '利用者募集',
  'variant'        => 'join',
  'image_url'      => get_theme_file_uri('assets/img/join-hero.webp'),
  // パララックス設定
  'parallax'       => true,
  'parallax_speed' => 0.35,     // 0.25〜0.5で微調整
]);
