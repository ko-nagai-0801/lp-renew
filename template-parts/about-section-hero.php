<?php
/**
 * About サブヒーロー（components 使用）
 * template-parts/about-section-hero.php
 */
if (!defined('ABSPATH')) exit;

get_template_part('components/subhero', null, [
  'sub'        => 'About Us',
  'title_html' => '<span id="LiNE">ＬｉＮＥ</span>&nbsp;<span id="PARK">ＰＡＲＫ</span>について',
  'variant'    => 'about',   // → .subhero--about が付与
  'tag'        => 'h1',
  'id'         => 'subhero-about',
]);
