<?php

/**
 * components/section-header.php
 * 汎用セクション見出しコンポーネント。
 *
 * 使用例:
 * --------------------------------------------------
 * get_template_part( 'components/section-header', null, [
 *  'id' => 'about-heading', // 見出しID（任意）
 *  'sub' => 'About us', // 小見出し <p>
 *  'title' => 'ＬｉＮＥ ＰＡＲＫ について', // メイン見出し
 *  'tag' => 'h2', // h1〜h6 いずれか（デフォルト h2）
 *  'extra_class' => 'about__header' // <header> に追加したいクラス
 * ] );
 * ---------------------------------------------------
 */

if (! defined('ABSPATH')) {
    exit;
}

/* ---------- デフォルト値 ---------- */
$defaults = [
    'id'          => '',
    'sub'         => '',
    'title'       => '',
    'tag'         => 'h2',
    'extra_class' => '',
];

$args = wp_parse_args($args ?? [], $defaults);

$tag = in_array(strtolower($args['tag']), ['h1', 'h2', 'h3', 'h4', 'h5', 'h6'], true) ? strtolower($args['tag']) : 'h2';

$header_classes = trim('section__header ' . $args['extra_class']);

?>
<header class="<?php echo esc_attr($header_classes); ?>">
    <?php if ($args['sub']) : ?>
        <p class="section__sub"><?php echo esc_html($args['sub']); ?></p>
    <?php endif; ?>

    <<?php echo $tag; ?><?php echo $args['id'] ? ' id="' . esc_attr($args['id']) . '"' : ''; ?> class="section__title">
        <?php echo esc_html($args['title']); ?>
    </<?php echo $tag; ?>>
</header>