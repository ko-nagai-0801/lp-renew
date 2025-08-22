<?php
/**
 * template-parts/services-index-section-hero.php
 * Services インデックス用サブヒーロー
 */
if (!defined('ABSPATH')) exit;

get_template_part('components/subhero', null, [
  'sub'        => 'Services',
  'title'      => get_the_title(),
  'variant'    => 'services-index',      // → .subhero--services-index
  'tag'        => 'h1',
  // 'image_url' => get_the_post_thumbnail_url(null, 'full'), // ページサムネがあれば自動で適用される仕様なので省略OK
  'id'         => 'subhero-services',
]);
