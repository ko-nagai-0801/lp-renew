<?php

/**
 * About サブヒーロー（components 使用）
 * template-parts/about-section-hero.php
 */
if (!defined('ABSPATH')) exit;

get_template_part('components/subhero', null, [
  'sub'        => 'About Us',
  'title_html' => '<span id="LiNE">ＬｉＮＥ</span>&nbsp;<span id="PARK">ＰＡＲＫ</span>について',
  'variant'    => 'about',
  'image_url'      => get_theme_file_uri('assets/img/about-hero.webp'),
  // パララックス設定
  'parallax'       => true,
  'parallax_speed' => 0.35,     // 0.25〜0.5で微調整
]);
