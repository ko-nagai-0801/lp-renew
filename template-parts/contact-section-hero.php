<?php

/**
 * Contact サブヒーロー
 * template-parts/contact-section-hero.php
 * 
 * @package LP_WP_Theme
 * @since 1.0.0
 */
if (!defined('ABSPATH')) exit;

$default_img = get_theme_file_uri('assets/img/contact-hero.webp');
$img = get_the_post_thumbnail_url(null, 'full') ?: $default_img;

get_template_part('components/subhero', null, [
  'sub'       => 'Contact',
  'title'     => 'お問い合わせ',
  'variant'   => 'contact',
  'image_url' => $img,
  'parallax'       => true,
  'parallax_speed' => 0.35,
]);
