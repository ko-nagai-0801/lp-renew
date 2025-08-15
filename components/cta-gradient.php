<?php

/**
 * components/cta-gradient.php
 * グラデ塗りスライド演出のCTA（他CTAに影響しないスコープ付き）
 */
if (!defined('ABSPATH')) exit;

/* デフォルト */
$defaults = [
    'url'         => '#',
    'label'       => 'View More',
    'variant'     => 'primary',
    'extra_class' => '',   // 既存と同じAPI
    'split'       => true, // 文字分割も踏襲
    // 色は淡〜濃の使い分けが効くように引数化
    'g1'          => '#55CB7B', // Green
    'g2'          => '#3D97FF', // Blue
    'g3'          => '#FF6666', // Red
];
$args = wp_parse_args($args ?? [], $defaults);

/* ラベルを <span> 分割（既存に合わせる） */
$label = $args['label'];
if ($args['split']) {
    $chars = preg_split('//u', $label, -1, PREG_SPLIT_NO_EMPTY);
    $label = '';
    foreach ($chars as $i => $c) {
        $escaped = ($c === ' ') ? '&nbsp;' : esc_html($c);
        $label .= sprintf('<span class="btn-char" style="--i:%d;">%s</span>', $i, $escaped);
    }
}

/* クラス */
$footer_classes = trim('c-cta cta--gradient ' . $args['extra_class']);
$button_classes = 'c-cta__button button button--' . esc_attr($args['variant']) . ' button--icon';

/* スタイルは一度だけ出力 */
static $printed = false;
if (!$printed) : ?>
    <style>
        /* ===== Scoped: .cta--gradient 以下だけに適用 ===== */
        .cta--gradient .button {
            --grad: linear-gradient(90deg, var(--g1), var(--g2), var(--g3));
            background: none !important;
            background-color: transparent !important;
            border: 2px solid transparent;
            border-image: var(--grad) 1;
            position: relative;
            overflow: hidden;
            isolation: isolate;
            border-radius: 0;
            color: var(--c-navy);
            box-shadow: 0 10px 22px rgba(0, 0, 0, .12);
            transition: color .35s, box-shadow .35s, transform .25s;
        }

        /* 左→右に広がる塗り */
        .cta--gradient .button::before {
            content: "";
            position: absolute;
            inset: 0;
            border-radius: inherit;
            background: linear-gradient(rgba(0, 0, 0, .06), rgba(0, 0, 0, .06)), var(--grad);
            transform-origin: left center;
            transform: scaleX(0);
            transition: transform .55s cubic-bezier(.22, 1, .36, 1);
            z-index: 0;
            pointer-events: none;
            will-change: transform;
        }

        /* 初期：文字＆矢印はグラデ塗り */
        .cta--gradient .button .btn-text {
            background-image: var(--grad);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            color: transparent;
            position: relative;
            z-index: 1;
        }

        .cta--gradient .button::after {
            background-image: var(--grad) !important;
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            color: transparent !important;
            display: inline-block;
            position: relative;
            z-index: 1;
        }

        /* hover / focus：塗りを全幅→白文字 */
        .cta--gradient .button:hover::before,
        .cta--gradient .button:focus-visible::before {
            transform: scaleX(1);
        }

        .cta--gradient .button:hover,
        .cta--gradient .button:focus-visible {
            color: #fff;
            outline: none;
            box-shadow: 0 12px 26px rgba(0, 0, 0, .18);
            background: none !important;
            /* テーマの:hover背景を無効化 */
        }

        .cta--gradient .button:hover .btn-text,
        .cta--gradient .button:focus-visible .btn-text {
            background-image: none;
            -webkit-text-fill-color: #fff;
            color: #fff;
        }

        .cta--gradient .button:hover::after,
        .cta--gradient .button:focus-visible::after {
            background-image: none !important;
            -webkit-text-fill-color: #fff;
            color: #fff !important;
        }

        /* モバイル：常に塗り＋白文字、タップで軽く沈む */
        @media (hover: none) {
            .cta--gradient .button {
                border-image: none;
                /* 枠消したい場合。残すならこの行を削除 */
                min-height: 48px;
                padding: .9rem 1.25rem;
                font-size: 1.0625rem;
                -webkit-tap-highlight-color: rgba(0, 0, 0, 0);
                touch-action: manipulation;
            }

            .cta--gradient .button::before {
                transform: scaleX(1);
            }

            .cta--gradient .button .btn-text {
                background-image: none;
                -webkit-text-fill-color: #fff;
                color: #fff;
            }

            .cta--gradient .button::after {
                background-image: none !important;
                -webkit-text-fill-color: #fff;
                color: #fff !important;
            }

            .cta--gradient .button:active {
                transition: transform .18s ease, box-shadow .18s ease;
                transform: translateY(1px) scale(.98);
                box-shadow: 0 8px 20px rgba(0, 0, 0, .16);
            }
        }

        /* 動きが苦手な環境ではフェードに切替 */
        @media (prefers-reduced-motion: reduce) {
            .cta--gradient .button::before {
                transition: opacity .3s;
                transform: none;
                opacity: 0;
            }

            .cta--gradient .button:hover::before,
            .cta--gradient .button:focus-visible::before {
                opacity: 1;
            }
        }
    </style>
<?php
    $printed = true;
endif;
?>
<footer class="<?php echo esc_attr($footer_classes); ?>"
    style="--g1: <?php echo esc_attr($args['g1']); ?>; --g2: <?php echo esc_attr($args['g2']); ?>; --g3: <?php echo esc_attr($args['g3']); ?>;">
    <a href="<?php echo esc_url($args['url']); ?>" class="<?php echo esc_attr($button_classes); ?>">
        <span class="btn-text"><?php echo $label; ?></span>
    </a>
</footer>