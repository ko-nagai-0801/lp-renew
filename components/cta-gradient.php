<?php

/**
 * components/cta-gradient.php
 * グラデ塗りスライド演出のCTA（他CTAに影響しないスコープ付き）
 */
if (!defined('ABSPATH')) exit;

/* デフォルト値（必要に応じて上書き可） */
$defaults = [
    'url'         => '#',
    'label'       => 'View More',
    'variant'     => 'primary',  // baseの.buttonに付与（例：button--white等）。未使用なら空でもOK
    'extra_class' => '',
    'split'       => true,       // 文字分割（視覚演出）

    // グラデーション色（inline styleのCSS変数で上書き可能）
    'g1' => '#55CB7B', // Green
    'g2' => '#3D97FF', // Blue
    'g3' => '#FF6666', // Red

    // 追加オプション
    'target'      => '',         // '' | '_blank'
    'rel'         => '',         // 'nofollow' 等。_blank時は自動でnoopener付与
    'with_icon'   => true,       // 矢印アイコンを出すなら true
];

$args = wp_parse_args($args ?? [], $defaults);

/* --------------------------------
 * ラベル生成（XSS対策 & アクセシビリティ）
 * ------------------------------ */
$raw_label = $args['label'];

if ($args['split']) {
    $chars = preg_split('//u', $raw_label, -1, PREG_SPLIT_NO_EMPTY);
    $label = '';
    foreach ($chars as $i => $c) {
        $escaped = ($c === ' ') ? '&nbsp;' : esc_html($c);
        $label  .= sprintf('<span class="btn-char" style="--i:%d;">%s</span>', $i, $escaped);
    }
} else {
    $label = esc_html($raw_label); // 非分割時は必ずエスケープ
}
$aria_label = wp_strip_all_tags($raw_label);

/* --------------------------------
 * クラス生成（配列→implodeで整頓）
 * ------------------------------ */
$footer_classes = implode(' ', array_filter([
    'c-cta',
    'cta--gradient',
    $args['extra_class'],
]));

// variantは class名として安全化
$variant_class  = $args['variant'] ? 'button--' . sanitize_html_class($args['variant']) : '';

$button_classes = implode(' ', array_filter([
    'c-cta__button',
    'button',
    $variant_class,
    $args['with_icon'] ? 'button--icon' : '',
]));

/* --------------------------------
 * リンク属性（target / rel）
 * ------------------------------ */
$target_attr = $args['target'] ? ' target="' . esc_attr($args['target']) . '"' : '';

$rel = $args['rel'];
if ($args['target'] === '_blank' && stripos($rel, 'noopener') === false) {
    $rel = trim($rel . ' noopener');
}
$rel_attr = $rel ? ' rel="' . esc_attr($rel) . '"' : '';

?>
<footer class="<?php echo esc_attr($footer_classes); ?>"
    style="--g1: <?php echo esc_attr($args['g1']); ?>;
         --g2: <?php echo esc_attr($args['g2']); ?>;
         --g3: <?php echo esc_attr($args['g3']); ?>;">
    <a href="<?php echo esc_url($args['url']); ?>"
        class="<?php echo esc_attr($button_classes); ?>"
        aria-label="<?php echo esc_attr($aria_label); ?>"
        <?php echo $target_attr . $rel_attr; ?>>
        <span class="btn-text" aria-hidden="true"><?php echo $label; ?></span>
    </a>
</footer>