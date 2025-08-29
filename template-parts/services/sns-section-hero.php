<?php
/**
 * /services/sns サブヒーロー
 * template-parts/services/sns-section-hero.php
 */
if (!defined('ABSPATH')) exit;

// アイキャッチ優先、なければデフォルト画像
$default_img = get_theme_file_uri('assets/img/services/sns-hero.webp');
$img = get_the_post_thumbnail_url(null, 'full') ?: $default_img;

// ACFがあれば上書き（任意）
$sub   = function_exists('get_field') && get_field('hero_sub')   ? get_field('hero_sub')   : 'SNS Management';
$title = get_the_title() ?: 'SNS運用';
$image = function_exists('get_field') && get_field('hero_image') ? get_field('hero_image') : $img;

get_template_part('components/subhero', null, [
  'sub'            => $sub,
  'title'          => $title,
  'variant'        => 'sns',   // → .subhero--sns
  'tag'            => 'h1',
  'image_url'      => $image,
  'parallax'       => true,
  'parallax_speed' => 0.35,
]);
