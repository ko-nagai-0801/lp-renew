<?php
/**
 * その他の業務 – Points
 */
if (!defined('ABSPATH')) exit;

$default_points = [
  'データ入力／リスト整備',
  '画像のトリミング・簡易加工',
  '短尺動画の簡易編集',
  'アンケート集計・レポート補助',
  'イベント補助（受付・配布 等）',
  '郵送手配・在庫カウント',
];

$points = (function_exists('get_field') && get_field('others_points'))
  ? array_filter((array) get_field('others_points'))
  : $default_points;
?>
<section class="section container others-points" aria-labelledby="others-points-heading">
  <?php
  get_template_part('components/section-header', null, [
    'id' => 'others-points-heading',
    'sub' => 'Service',
    'title' => 'ご提供内容',
    'tag' => '',
    'extra_class' => 'others__header',
  ]);
  ?>
  <ul class="others-points__list" role="list">
    <?php foreach ($points as $text): ?>
      <li class="others-points__item">
        <?php echo esc_html(is_array($text) && isset($text['text']) ? $text['text'] : $text); ?>
      </li>
    <?php endforeach; ?>
  </ul>
</section>
