<?php

/**
 * components/cta.php
 * Re‑usable Call‑To‑Action component.
 *
 * Example usage (in any template):
 * ------------------------------------------------
 * get_template_part( 'components/cta', null, [
 *     'url'         => home_url( '/about/' ),   // クリック先
 *     'label'       => 'View More',            // ボタン文言
 *     'variant'     => 'primary',              // primary | white | etc.
 *     'extra_class' => 'about__cta'            // <footer> に追加したいクラス
 * ] );
 * ------------------------------------------------
 */

if (! defined('ABSPATH')) {
  exit;
}
/* ---------- デフォルト値 ---------- */
$defaults = [
  'url'         => '#',
  'label'       => 'View More',
  'variant'     => 'primary',
  'extra_class' => '',
  'split'       => true,          // ★ 追加：true で文字分割
];
$args = wp_parse_args($args ?? [], $defaults);

/* ---------- ラベルを <span> 分割 ---------- */
$label = $args['label'];
if ($args['split']) {
  $chars = preg_split('//u', $label, -1, PREG_SPLIT_NO_EMPTY);
  $label = '';
  foreach ($chars as $i => $c) {
    /* 半角スペースは &nbsp; に置換（CSS で幅が潰れず、改行も防げる） */
    $escaped = ($c === ' ') ? '&nbsp;' : esc_html($c);

    $label .= sprintf(
      '<span class="btn-char" style="--i:%d;">%s</span>',
      $i,
      $escaped
    );
  }
}


$footer_classes = trim('c-cta ' . $args['extra_class']);
$button_classes = 'c-cta__button button button--' . esc_attr($args['variant']);
?>
<footer class="<?php echo esc_attr($footer_classes); ?>">
  <a href="<?php echo esc_url($args['url']); ?>"
    class="<?php echo esc_attr($button_classes); ?> button--icon">
    <span class="btn-text">
      <?php echo $label; // ここに分割済み or 通常ラベルが入る 
      ?>
    </span>
  </a>
</footer>