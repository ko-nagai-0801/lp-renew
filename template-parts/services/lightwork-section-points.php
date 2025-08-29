<?php
/**
 * 軽作業 – Points
 */
if (!defined('ABSPATH')) exit;

$default_points = [
  '商品の梱包・袋詰め',
  'シール貼り・ラベル貼付',
  'DM／チラシの封入・封緘',
  '検品・仕分け',
  'セット組み・内職作業',
  '簡易清掃・整頓',
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
