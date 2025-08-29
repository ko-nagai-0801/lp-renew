<?php

/**
 * 軽作業 – Hero
 */
if (!defined('ABSPATH')) exit;

$default_img = get_theme_file_uri('assets/img/services/lightwork-hero.webp');
$img = get_the_post_thumbnail_url(null, 'full') ?: $default_img;

get_template_part('components/subhero', null, [
    'sub'            => 'Light Work',
    'title'          => '軽作業',
    'variant'        => 'lightwork',     // → .subhero--lightwork
    'tag'            => 'h1',
    'image_url'      => $img,
    'parallax'       => true,
    'parallax_speed' => 0.35,
]);
