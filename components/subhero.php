<?php
/**
 * components/subhero.php
 * 汎用サブヒーロー（common.css の .parallax ベース対応）
 */
if (!defined('ABSPATH')) exit;

$defaults = [
  'sub'            => '',
  'title'          => '',
  'title_html'     => '',
  'variant'        => '',
  'tag'            => 'h1',
  'image_url'      => '',
  'overlay_from'   => 'rgba(0,0,0,.30)',
  'overlay_to'     => 'rgba(0,0,0,.30)',
  'extra_class'    => '',
  'id'             => '',
  'parallax'       => true,   // ← 有効化時は .parallax を付け、CSS変数で背景を渡す
  'parallax_speed' => 0.35,   // 0.25〜0.5 推奨
];
$args = wp_parse_args($args ?? [], $defaults);

/* サムネ自動適用 */
if (empty($args['image_url'])) {
  $thumb = get_the_post_thumbnail_url(null, 'full');
  if ($thumb) $args['image_url'] = $thumb;
}

/* クラス */
$classes = ['subhero'];
if (!empty($args['variant']))     $classes[] = 'subhero--' . sanitize_html_class($args['variant']);
if (!empty($args['extra_class'])) $classes[] = $args['extra_class'];
if (!empty($args['parallax']))    $classes[] = 'parallax'; // ← common.css の基盤を使う
$class_attr = esc_attr(implode(' ', $classes));

/* style（parallax 時は CSS 変数、非 parallax 時は従来の background-image） */
$style = [];
$style[] = "--subhero-overlay-from:{$args['overlay_from']}";
$style[] = "--subhero-overlay-to:{$args['overlay_to']}";

if (!empty($args['parallax'])) {
  // ★ parallax: 背景は .parallax::before が描画するので、CSS変数に渡す
  if (!empty($args['image_url'])) {
    $style[] = "--parallax-image:url('" . esc_url($args['image_url']) . "')";
  }
  $style[] = "--parallax-overlay:linear-gradient(var(--subhero-overlay-from), var(--subhero-overlay-to))";
} else {
  // 非 parallax: 従来どおり要素自身に背景を適用
  if (!empty($args['image_url'])) {
    $style[] = "background-image:url('" . esc_url($args['image_url']) . "')";
  }
}
$style_attr = esc_attr(implode(';', $style));

$tag = in_array($args['tag'], ['h1','h2','h3','h4','h5','h6'], true) ? $args['tag'] : 'h1';
?>
<section
  <?php if ($args['id']) echo ' id="' . esc_attr($args['id']) . '"'; ?>
  class="<?php echo $class_attr; ?>"
  style="<?php echo $style_attr; ?>"
  <?php if (!empty($args['parallax'])) : ?>
    data-parallax-speed="<?php echo esc_attr($args['parallax_speed']); ?>"
  <?php endif; ?>
>
  <div class="subhero__inner">
    <div class="subhero__content">
      <?php if ($args['sub']) : ?>
        <p class="subhero__sub"><?php echo esc_html($args['sub']); ?></p>
      <?php endif; ?>

      <?php if ($args['title_html'] || $args['title']) : ?>
        <<?php echo $tag; ?> class="subhero__title">
          <?php
            echo $args['title_html']
              ? wp_kses_post($args['title_html'])
              : esc_html($args['title']);
          ?>
        </<?php echo $tag; ?>>
      <?php endif; ?>
    </div>
  </div>
</section>

<?php 
// ここでパンくず（引数を受け渡し可能に）
$bc_args = $args['breadcrumbs_args'] ?? [];
get_template_part('components/breadcrumbs', null, $bc_args);
?>
