<?php

/**
 * components/cta-ghost.php
 * シンプルなゴーストCTA（白枠・白文字／hoverで白塗り）
 * さらに：hover時のテキスト＆矢印はグラデ塗り
 */
if (!defined('ABSPATH')) exit;

$defaults = [
    'url'         => '#',
    'label'       => 'View More',
    'extra_class' => '',
    'split'       => true,
    'border_w'    => '2px',        // 2px or 3px など
    'brand'       => '#5c98ff',    // フォールバック文字色（hover時）
    'wipe'        => 'slide',      // 'slide' | 'fade'
    'knockout'    => false,        // 文字を“くり抜き”たい時 true（実験的）

    // ▼ 追加：hover時 文字グラデの色
    'g1'          => '#74D690',    // green
    'g2'          => '#5AA8FF',    // blue
    'g3'          => '#FF7A7A',    // red
];
$args = wp_parse_args($args ?? [], $defaults);

/* ラベル分割 */
$label = $args['label'];
if ($args['split']) {
    $chars = preg_split('//u', $label, -1, PREG_SPLIT_NO_EMPTY);
    $label = '';
    foreach ($chars as $i => $c) {
        $escaped = ($c === ' ') ? '&nbsp;' : esc_html($c);
        $label .= sprintf('<span class="btn-char" style="--i:%d;">%s</span>', $i, $escaped);
    }
}

$footer_classes = trim('c-cta cta--ghost ' . $args['extra_class']);
$btn_classes    = 'c-cta__button button button--icon';

static $printed = false;
if (!$printed): ?>
    <style>
        /* ===== Scoped to .cta--ghost only ===== */
        .cta--ghost .button {
            --ghost-border-w: 2px;
            --ghost-brand: #5c98ff;
            /* 文字グラデ（hoverで使用） */
            --ghost-grad: linear-gradient(90deg, var(--ghost-g1), var(--ghost-g2), var(--ghost-g3));

            position: relative;
            isolation: isolate;
            background: none !important;
            color: #fff;
            /* 初期は白文字 */
            border: var(--ghost-border-w) solid rgba(255, 255, 255, .95);
            border-radius: 0;
            box-shadow: 0 10px 22px rgba(0, 0, 0, .12);
            transition: color .3s, box-shadow .3s, transform .2s;
            overflow: hidden;
        }

        /* 左→右ホワイト塗り（初期0%） */
        .cta--ghost .button::before {
            content: "";
            position: absolute;
            inset: 0;
            background: #fff;
            z-index: 0;
            pointer-events: none;
            transform-origin: left center;
            transform: scaleX(0);
            transition: transform .45s cubic-bezier(.22, 1, .36, 1), opacity .3s;
        }

        /* 文字＆矢印は前面 */
        .cta--ghost .button .btn-text,
        .cta--ghost .button::after {
            position: relative;
            z-index: 1;
        }

        /* 矢印はcurrentColor（clip時に必要なため inline-block） */
        .cta--ghost .button::after {
            color: currentColor !important;
            display: inline-block;
        }

        /* hover/focus：塗り展開 */
        .cta--ghost .button:hover::before,
        .cta--ghost .button:focus-visible::before {
            transform: scaleX(1);
        }

        /* フォールバックの文字色は brand（下のグラデで上書きされる想定） */
        .cta--ghost .button:hover,
        .cta--ghost .button:focus-visible {
            color: var(--ghost-brand);
            outline: none;
            box-shadow: 0 12px 26px rgba(0, 0, 0, .18);
        }

        /* === hover時：テキスト＆矢印をグラデ塗りに === */
        .cta--ghost .button:hover .btn-text,
        .cta--ghost .button:focus-visible .btn-text {
            background-image: var(--ghost-grad);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            color: transparent;
        }

        .cta--ghost .button:hover::after,
        .cta--ghost .button:focus-visible::after {
            background-image: var(--ghost-grad) !important;
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            color: transparent !important;
        }

        /* wipe: fade バージョン（必要時に .is-fade を付与） */
        .cta--ghost.is-fade .button::before {
            transform: none;
            opacity: 0;
        }

        .cta--ghost.is-fade .button:hover::before,
        .cta--ghost.is-fade .button:focus-visible::before {
            opacity: 1;
        }

        /* ノックアウト文字（実験的・非対応環境は通常色にフォールバック） */
        .cta--ghost.is-knockout .button:hover .btn-text,
        .cta--ghost.is-knockout .button:focus-visible .btn-text {
            -webkit-text-fill-color: transparent;
            color: transparent;
            mix-blend-mode: normal;
        }

        @supports (-webkit-mask-composite: xor) or (mask-composite: exclude) {

            .cta--ghost.is-knockout .button:hover::before,
            .cta--ghost.is-knockout .button:focus-visible::before {
                z-index: 2;
            }

            .cta--ghost.is-knockout .button:hover .btn-text,
            .cta--ghost.is-knockout .button:focus-visible .btn-text {
                position: relative;
                z-index: 3;
                background: none;
                -webkit-mask-image: none;
            }
        }

        /* モバイル：タップ時の小さな沈み */
        @media (hover:none) {
            .cta--ghost .button {
                min-height: 48px;
                padding: .9rem 1.25rem;
                font-size: 1.0625rem;
            }

            .cta--ghost .button:active {
                transform: translateY(1px) scale(.98);
                box-shadow: 0 8px 20px rgba(0, 0, 0, .16);
            }
        }

        /* reduce-motion */
        @media (prefers-reduced-motion: reduce) {
            .cta--ghost .button::before {
                transition: opacity .3s;
                transform: none;
                opacity: 0;
            }

            .cta--ghost .button:hover::before,
            .cta--ghost .button:focus-visible::before {
                opacity: 1;
            }
        }
    </style>
<?php $printed = true;
endif; ?>

<footer class="<?php echo esc_attr($footer_classes); ?>
  <?php echo $args['wipe'] === 'fade' ? ' is-fade' : ''; ?>
  <?php echo $args['knockout'] ? ' is-knockout' : ''; ?>"
    style="--ghost-border-w: <?php echo esc_attr($args['border_w']); ?>;
         --ghost-brand: <?php echo esc_attr($args['brand']); ?>;
         --ghost-g1: <?php echo esc_attr($args['g1']); ?>;
         --ghost-g2: <?php echo esc_attr($args['g2']); ?>;
         --ghost-g3: <?php echo esc_attr($args['g3']); ?>;">
    <a href="<?php echo esc_url($args['url']); ?>" class="<?php echo esc_attr($btn_classes); ?>">
        <span class="btn-text"><?php echo $label; ?></span>
    </a>
</footer>