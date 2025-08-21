<?php
/**
 * Join Us サブヒーロー（components 使用）
 * template-parts/join-section-hero.php
 */
if (!defined('ABSPATH')) exit;

get_template_part('components/subhero', null, [
  'sub'        => 'Join Us',
  'title'      => '利用者募集',
  'variant'    => 'join',  // → .subhero--join が付与されます
  'tag'        => 'h1',
  // 'image_url' => get_the_post_thumbnail_url(null, 'full'), // 指定しなければ自動で投稿サムネを使用
  'id'         => 'subhero-join',
]);
