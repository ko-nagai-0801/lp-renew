<?php
/**
 * その他の業務 – Hero
 */
if (!defined('ABSPATH')) exit;

$default_img = get_theme_file_uri('assets/img/services/others-hero.webp');
$img = get_the_post_thumbnail_url(null, 'full') ?: $default_img;

get_template_part('components/subhero', null, [
  'sub'            => 'Other Services',
  'title'          => 'その他の業務',
  'variant'        => 'others',       // → .subhero--others
  'tag'            => 'h1',
  'image_url'      => $img,
  'parallax'       => true,
  'parallax_speed' => 0.35,
]);
