<?php

/**
 * components/cta-ghost.php
 * シンプルなゴーストCTA（白枠・白文字／hoverで白塗り）
 * さらに：hover時のテキスト＆矢印はグラデ塗り
 *
 * - 視覚演出のテキスト分割（<span>…）はスクリーンリーダー向けに aria-hidden
 * - リンク本体に aria-label を付与して音声読み上げは生テキスト
 * - CSS は common.css に統合済み（.cta--ghost スコープ）
 */

if (!defined('ABSPATH')) exit;

/* ==========================================================
 * 1) デフォルト引数（必要に応じてテンプレ側から上書き）
 * ----------------------------------------------------------
 * - wipe      : 'slide' | 'fade'（白塗りの広がり方）
 * - knockout  : true で実験的ノックアウト文字（対応ブラウザのみ）
 * - with_icon : true で矢印アイコン（.button--icon）を付与
 * - target    : '_blank' で新規タブ、rel に noopener を自動追加
 * - g1..g3    : hover時にテキストへ適用する文字グラデの3色
 * - border_w  : 白枠の太さ（例 '2px'）
 * - brand     : hover時のフォールバック文字色（グラデ非対応環境向け）
 * ========================================================== */
$defaults = [
    'url'         => '#',
    'label'       => 'View More',
    'extra_class' => '',
    'split'       => true,
    'border_w'    => '2px',        // 例: '2px', '3px'
    'brand'       => '#5c98ff',    // hover時のフォールバック文字色
    'wipe'        => 'slide',      // 'slide' | 'fade'
    'knockout'    => false,        // 実験的ノックアウト文字

    'g1' => '#74D690', // Green
    'g2' => '#FF7A7A', // Red
    'g3' => '#5AA8FF', // Blue

    // 追加オプション
    'target'      => '',           // '' | '_blank'
    'rel'         => '',           // 'nofollow' 等（_blank時はnoopenerを自動付与）
    'with_icon'   => true,         // 矢印アイコンの有無
];

$args = wp_parse_args($args ?? [], $defaults);

/* ==========================================================
 * 2) ラベル生成（XSS対策 + アクセシビリティ配慮）
 * ----------------------------------------------------------
 * - 視覚演出（分割）は <span class="btn-char"> を連ねる
 * - 空白は &nbsp; に変換
 * - 非分割時は esc_html() で必ずエスケープ
 * - aria-label 用に wp_strip_all_tags() で素の文言を用意
 * ========================================================== */
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

/* ==========================================================
 * 3) クラス生成（配列→implode で整頓・改行混入防止）
 * ----------------------------------------------------------
 * - footer は .c-cta .cta--ghost を基本に、演出フラグで付与
 * - button は .c-cta__button .button を基本に、アイコン有無で付与
 * ========================================================== */
$footer_classes = implode(' ', array_filter([
    'c-cta',
    'cta--ghost',
    $args['extra_class'],
    ($args['wipe'] === 'fade') ? 'is-fade' : '',
    (!empty($args['knockout'])) ? 'is-knockout' : '',
]));

$button_classes = implode(' ', array_filter([
    'c-cta__button',
    'button',
    $args['with_icon'] ? 'button--icon' : '',
]));

/* ==========================================================
 * 4) リンク属性（target / rel 安全化）
 * ----------------------------------------------------------
 * - target が '_blank' のときは rel に noopener を自動付与
 * - ユーザー指定 rel がある場合は後ろに結合、重複は許容
 * ========================================================== */
$target_attr = $args['target'] ? ' target="' . esc_attr($args['target']) . '"' : '';
$rel = $args['rel'];
if ($args['target'] === '_blank' && stripos($rel, 'noopener') === false) {
    $rel = trim($rel . ' noopener');
}
$rel_attr = $rel ? ' rel="' . esc_attr($rel) . '"' : '';

/* ==========================================================
 * 5) 出力（CSS変数は inline style で供給）
 * ----------------------------------------------------------
 * - --ghost-border-w : 枠線太さ
 * - --ghost-brand    : hover時のフォールバック文字色
 * - --ghost-g1..g3   : 文字グラデの3色
 * ========================================================== */
?>
<footer class="<?php echo esc_attr($footer_classes); ?>"
    style="--ghost-border-w: <?php echo esc_attr($args['border_w']); ?>;
         --ghost-brand: <?php echo esc_attr($args['brand']); ?>;
         --ghost-g1: <?php echo esc_attr($args['g1']); ?>;
         --ghost-g2: <?php echo esc_attr($args['g2']); ?>;
         --ghost-g3: <?php echo esc_attr($args['g3']); ?>;">
    <a href="<?php echo esc_url($args['url']); ?>"
        class="<?php echo esc_attr($button_classes); ?>"
        aria-label="<?php echo esc_attr($aria_label); ?>" <?php
                                                            echo $target_attr . $rel_attr; ?>>
        <!-- 視覚演出テキスト。読み上げ対象は aria-label 側 -->
        <span class="btn-text" aria-hidden="true"><?php echo $label; ?></span>
    </a>
</footer>