<?php

/**
 * 黒ゴーストCTA（黒枠・黒文字／hoverで黒塗り→白文字）
 * components/cta-ghost-black.php
 *
 * @package LP_WP_Theme
 * @since 1.1.0
 *
 * 更新履歴
 * - 1.1.0 (2025-09-03): 左側アイコン（Bootstrap Icons）対応、サイズ可変（sm/md/lg）対応
 * - 1.0.0 (2025-09-03): 初版
 */
if (!defined('ABSPATH')) exit;

/* ================== 引数 ================== */
$defaults = [
    'url'         => '#',
    'label'       => '戻る',
    'extra_class' => '',
    'split'       => true,          // 視覚演出で1文字ずつ <span>
    'border_w'    => '2px',
    'with_icon'   => true,          // ← デフォルトで左矢印を出す
    'icon'        => 'chevron-double-left', // Bootstrap Icons 名
    'icon_pos'    => 'left',        // left | right（rightは今は未使用）
    'size'        => 'sm',          // sm | md | lg
    'target'      => '',
    'rel'         => '',
];
$args = wp_parse_args($args ?? [], $defaults);

/* ================== ラベル生成（a11y配慮） ================== */
$raw_label = (string)$args['label'];
if (!empty($args['split'])) {
    $chars = preg_split('//u', $raw_label, -1, PREG_SPLIT_NO_EMPTY);
    $label = '';
    foreach ($chars as $i => $c) {
        $label .= sprintf(
            '<span class="btn-char" style="--i:%d;">%s</span>',
            $i,
            $c === ' ' ? '&nbsp;' : esc_html($c)
        );
    }
} else {
    $label = esc_html($raw_label);
}
$aria_label = wp_strip_all_tags($raw_label);

/* ================== クラス ================== */
$footer_classes = implode(' ', array_filter([
    'cta--ghost-black',
    $args['extra_class'],
]));

$button_classes = implode(' ', array_filter([
    'button',
    // サイズ修飾子（.button--sm が base を上書きして小さくする）
    $args['size'] ? 'button--' . sanitize_html_class($args['size']) : '',
    // 左アイコン用
    !empty($args['with_icon']) && $args['icon_pos'] === 'left' ? 'button--icon-left' : '',
]));

/* ================== target/rel 安全化 ================== */
$target_attr = $args['target'] ? ' target="' . esc_attr($args['target']) . '"' : '';
$rel = (string)$args['rel'];
if ($args['target'] === '_blank' && stripos($rel, 'noopener') === false) {
    $rel = trim($rel . ' noopener');
}
$rel_attr = $rel ? ' rel="' . esc_attr($rel) . '"' : '';

/* ================== 出力 ================== */
?>
<footer class="<?php echo esc_attr($footer_classes); ?>"
    style="--gb-border-w: <?php echo esc_attr($args['border_w']); ?>;">
    <a href="<?php echo esc_url($args['url']); ?>"
        class="<?php echo esc_attr($button_classes); ?>"
        aria-label="<?php echo esc_attr($aria_label); ?>" <?php echo $target_attr . $rel_attr; ?>>

        <?php if (!empty($args['with_icon']) && $args['icon_pos'] === 'left'): ?>
            <!-- Bootstrap Icons（CSSで描画されるので中身は空でOK） -->
            <i class="bi bi-<?php echo esc_attr($args['icon']); ?> btn-icon" aria-hidden="true"></i>
        <?php endif; ?>

        <span class="btn-text" aria-hidden="true"><?php echo $label; ?></span>
    </a>
</footer>