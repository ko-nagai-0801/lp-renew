<?php
/**
 * SNS代行 – Section: Points
 * template-parts/services/sns-section-points.php
 */
if (!defined('ABSPATH')) exit;

/* 既定リスト（ACF: sns_points があれば差し替え） */
$default_points = [
  'フォロー・フォローバック、リプライなど、アカウントを育てるお手伝いをいたします。',
  'お預かりした原稿を投稿用に加筆・修正し、実際に投稿します。',
  'B2B営業媒体を使用し、営業活動のご支援、アポイントメント獲得のお手伝いをいたします。',
];

$points = (function_exists('get_field') && get_field('sns_points'))
  ? array_filter((array) get_field('sns_points'))
  : $default_points;
?>

<section class="section container sns-points" aria-labelledby="sns-points-heading">
  <?php
  get_template_part(
    'components/section-header',
    null,
    [
      'id'          => 'sns-points-heading',
      'sub'         => 'Service',
      'title'       => 'ご提供内容',
      'tag'         => '', // 省略で h2
      'extra_class' => 'sns__header'
    ]
  );
  ?>

  <ul class="sns-points__list" role="list">
    <?php foreach ($points as $text): ?>
      <li class="sns-points__item">
        <?php echo esc_html(is_array($text) && isset($text['text']) ? $text['text'] : $text); ?>
      </li>
    <?php endforeach; ?>
  </ul>
</section>
