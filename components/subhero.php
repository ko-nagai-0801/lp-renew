<?php
/**
 * components/subhero.php
 * 汎用サブヒーロー
 *
 * 使用例:
 * get_template_part('components/subhero', null, [
 *   'sub'        => 'Join Us',
 *   'title'      => '利用者募集',
 *   'variant'    => 'join',         // subhero--{variant} を付与（任意）
 *   'tag'        => 'h1',           // 見出しタグ (h1〜h3 推奨)
 *   'image_url'  => get_theme_file_uri('assets/img/join-hero.webp'), // 省略時は投稿サムネ
 *   'overlay_from' => 'rgba(0,0,0,.30)',  // 暗幕グラデーション（開始/終了）
 *   'overlay_to'   => 'rgba(0,0,0,.30)',
 *   'extra_class'=> 'my-subhero',   // 追加クラス
 *   'id'         => 'subhero-join'  // セクションID
 * ]);
 */

if (!defined('ABSPATH')) exit;

$defaults = [
  'sub'          => '',
  'title'        => '',
  'variant'      => '',       // ex) 'about', 'join'
  'tag'          => 'h1',
  'image_url'    => '',       // 指定なければ投稿サムネを使用
  'overlay_from' => 'rgba(0,0,0,.30)',
  'overlay_to'   => 'rgba(0,0,0,.30)',
  'extra_class'  => '',
  'id'           => '',
];

$args = wp_parse_args($args ?? [], $defaults);

/* 画像の決定：明示指定が無ければ投稿サムネを試す */
if (empty($args['image_url'])) {
  $thumb = get_the_post_thumbnail_url(null, 'full');
  if ($thumb) $args['image_url'] = $thumb;
}

/* style カスタムプロパティ & 背景画像 */
$style = [];
if (!empty($args['image_url'])) {
  $style[] = "background-image:url('" . esc_url($args['image_url']) . "')";
}
/* 変数は汎用名（--subhero-overlay-*）と、join用の互換名の両方を出す */
$style[] = "--subhero-overlay-from:{$args['overlay_from']}";
$style[] = "--subhero-overlay-to:{$args['overlay_to']}";
$style[] = "--join-overlay-from:{$args['overlay_from']}"; // 互換
$style[] = "--join-overlay-to:{$args['overlay_to']}";     // 互換
$style_attr = implode(';', $style);

/* クラス */
$classes = ['subhero'];
if (!empty($args['variant'])) $classes[] = 'subhero--' . sanitize_html_class($args['variant']);
if (!empty($args['extra_class'])) $classes[] = $args['extra_class'];
$class_attr = esc_attr(implode(' ', $classes));

$tag = in_array($args['tag'], ['h1','h2','h3','h4','h5','h6'], true) ? $args['tag'] : 'h1';
?>

<section<?php if ($args['id']) echo ' id="' . esc_attr($args['id']) . '"'; ?>
  class="<?php echo $class_attr; ?>"
  style="<?php echo esc_attr($style_attr); ?>">
  <div class="subhero__inner">
    <div class="subhero__content">
      <?php if ($args['sub']) : ?>
        <p class="subhero__sub"><?php echo esc_html($args['sub']); ?></p>
      <?php endif; ?>
      <?php if ($args['title']) : ?>
        <<?php echo $tag; ?> class="subhero__title">
          <?php echo esc_html($args['title']); ?>
        </<?php echo $tag; ?>>
      <?php endif; ?>
    </div>
  </div>
</section>
