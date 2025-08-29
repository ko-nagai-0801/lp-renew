<?php
/**
 * Web制作 – Section: Points（4項目のみ）
 * template-parts/services/web-section-points.php
 */
if (!defined('ABSPATH')) exit;

/* 4項目（ACFに配列/リピータ "web_points" があれば置換） */
$default_points = [
  'HP、LPのご制作。',
  'デザインからコーディング、公開までお客様のニーズに沿ってご制作いたします。',
  'サーバーの契約代行、ドメイン取得代行も承っております。',
  '公開後の保守・運用もお任せください。',
];

$points = (function_exists('get_field') && get_field('web_points'))
  ? array_filter((array) get_field('web_points'))
  : $default_points;
?>

<section class="section container web-points" aria-labelledby="web-points-heading">
      <?php
    get_template_part(
      'components/section-header',
      null,
      [
        'id' => '', // 見出しID（任意）
        'sub' => 'Service', // 小見出し
        'title' => 'ご提供内容', // メイン見出し
        'tag' => '', // h1〜h6（省略でh2）
        'extra_class' => 'web__header' // 追加クラス（任意）
      ]
    );
    ?>

  <ul class="web-points__list" role="list">
    <?php foreach ($points as $text): ?>
      <li class="web-points__item"><?php echo esc_html(is_array($text) && isset($text['text']) ? $text['text'] : $text); ?></li>
    <?php endforeach; ?>
  </ul>
</section>
