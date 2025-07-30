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
    'label'       => 'View More',
    'variant'     => 'primary', // あるいは "white" など
    'extra_class' => '', // <footer> への追加クラス
];

$args = wp_parse_args($args ?? [], $defaults);

/* ---------- クラス文字列 ---------- */
$footer_classes = trim('c-cta ' . $args['extra_class']);
$button_classes = 'c-cta__button button button--' . esc_attr($args['variant']);

?>

<footer class="<?php echo esc_attr($footer_classes); ?>">
    <a href="<?php echo esc_url($args['url']); ?>" class="<?php echo esc_attr($button_classes); ?>">
        <?php echo esc_html($args['label']); ?>
        <i class="bi bi-caret-right-fill" aria-hidden="true"></i>
    </a>
</footer>