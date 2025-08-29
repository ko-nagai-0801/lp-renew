<?php

/**
 * デザイン制作 – Section: Points（提供内容）
 * template-parts/services/design-section-points.php
 */
if (!defined('ABSPATH')) exit;

/* 既定リスト（ACF: design_points があれば差し替え） */
$default_points = [
    'Webデザイン',
    'バナー制作',
    'チラシ制作',
    'ロゴデザイン',
    '名刺デザイン',
];

$points = (function_exists('get_field') && get_field('design_points'))
    ? array_filter((array) get_field('design_points'))
    : $default_points;
?>

<section class="section container design-points" aria-labelledby="design-points-heading">
    <?php
    get_template_part(
        'components/section-header',
        null,
        [
            'id'          => 'design-points-heading',
            'sub'         => 'Service',
            'title'       => 'ご提供内容',
            'tag'         => '', // 省略で h2
            'extra_class' => 'design__header'
        ]
    );
    ?>

    <ul class="design-points__list" role="list">
        <?php foreach ($points as $text): ?>
            <li class="design-points__item">
                <?php echo esc_html(is_array($text) && isset($text['text']) ? $text['text'] : $text); ?>
            </li>
        <?php endforeach; ?>
    </ul>
</section>