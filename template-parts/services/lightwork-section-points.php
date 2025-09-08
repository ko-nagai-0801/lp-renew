<?php
/**
 * 軽作業 – Points
 */
if (!defined('ABSPATH')) exit;

$default_points = [
  'チラシの折り込み・封入',
  '商品の組み立て・袋入れ',
  '商品へのシール貼り・ラベル貼付',
  '検品作業・仕分けなど',
];

$points = (function_exists('get_field') && get_field('lightwork_points'))
  ? array_filter((array) get_field('lightwork_points'))
  : $default_points;
?>
<section class="section container lightwork-points" aria-labelledby="lightwork-points-heading">
  <?php
  get_template_part('components/section-header', null, [
    'id' => 'lightwork-points-heading',
    'sub' => 'Service',
    'title' => 'ご提供内容',
    'tag' => '',
    'extra_class' => 'lightwork__header',
  ]);
  ?>
  <ul class="lightwork-points__list" role="list">
    <?php foreach ($points as $text): ?>
      <li class="lightwork-points__item">
        <?php echo esc_html(is_array($text) && isset($text['text']) ? $text['text'] : $text); ?>
      </li>
    <?php endforeach; ?>
  </ul>
</section>
