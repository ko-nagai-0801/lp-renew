<?php
/**
 * template-parts/access-section-hero.php
 * Access サブヒーローは components/subhero を利用
 */
if (!defined('ABSPATH')) exit;

// 背景画像：ページのアイキャッチ優先、なければテーマ内のデフォルト画像にフォールバック
$default_img = get_theme_file_uri('assets/img/access-hero.webp');
$img = get_the_post_thumbnail_url(null, 'full') ?: $default_img;

get_template_part('components/subhero', null, [
  'sub'       => 'Access',
  'title'     => 'アクセス',
  'variant'   => 'access',       // -> .subhero--access が付与されます
  'tag'       => 'h1',
  'id'        => 'subhero-access',
  'image_url' => $img,           // components/subhero 側で background-image に適用
]);
