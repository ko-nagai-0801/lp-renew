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
  'title_html'   => '',      // ← 追加：HTMLタイトル用
  'variant'      => '',
  'tag'          => 'h1',
  'image_url'    => '',
  'overlay_from' => 'rgba(0,0,0,.30)',
  'overlay_to'   => 'rgba(0,0,0,.30)',
  'extra_class'  => '',
  'id'           => '',
];
$args = wp_parse_args($args ?? [], $defaults);

/* サムネ自動適用 */
if (empty($args['image_url'])) {
  $thumb = get_the_post_thumbnail_url(null, 'full');
  if ($thumb) $args['image_url'] = $thumb;
}

/* style */
$style = [];
if (!empty($args['image_url'])) $style[] = "background-image:url('" . esc_url($args['image_url']) . "')";
$style[] = "--subhero-overlay-from:{$args['overlay_from']}";
$style[] = "--subhero-overlay-to:{$args['overlay_to']}";
$style_attr = implode(';', $style);

/* class */
$classes = ['subhero'];
if (!empty($args['variant']))   $classes[] = 'subhero--' . sanitize_html_class($args['variant']);
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

      <?php if ($args['title_html'] || $args['title']) : ?>
        <<?php echo $tag; ?> class="subhero__title">
          <?php
            echo $args['title_html']
              ? wp_kses_post($args['title_html'])  // HTML許可版
              : esc_html($args['title']);          // テキスト版
          ?>
        </<?php echo $tag; ?>>
      <?php endif; ?>
    </div>
  </div>
</section>
