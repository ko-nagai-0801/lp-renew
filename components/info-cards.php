<?php

/**
 * 情報カードグリッド（汎用／SVG・サムネ対応版）
 * components/info-cards.php
 *
 * @package LP_WP_Theme
 * @since 1.1.0
 *
 * 更新履歴:
 * - 1.1.0: media（svg/img）対応、accent色、装飾bg_svgを追加
 * - 1.0.0: 初版
 *
 * 使い方（例）:
 * get_template_part('components/info-cards', null, [
 *   'items' => [[
 *     'icon'  => 'clock',
 *     'label' => '時間',
 *     'title' => '9:00–14:30',
 *     'desc'  => '補足',
 *     'media' => [
 *        'type' => 'img',
 *        'src'  => get_theme_file_uri('assets/img/sample.webp'),
 *        'alt'  => '作業イメージ',
 *        'width'=> 1200, 'height'=> 800, // CLS対策
 *     ],
 *     'accent'   => '#009cdf', // アイコン円の色を上書き
 *     'bg_svg'   => '<svg ...>…</svg>', // 装飾（右上に薄く）
 *   ]],
 *   'columns_md' => 2,
 *   'columns_lg' => 3,
 *   'extra_class'=> 'foo',
 *   'aria_label' => '説明ラベル',
 * ]);
 */
if (!defined('ABSPATH')) exit;

$items       = $args['items']       ?? [];
$cols_md     = (int)($args['columns_md'] ?? 2);
$cols_lg     = (int)($args['columns_lg'] ?? 3);
$extra_class = $args['extra_class'] ?? '';
$aria_label  = $args['aria_label']  ?? '';

if (!$items) return;
?>

<div class="info-cards <?php echo esc_attr($extra_class); ?>"
    role="list"
    aria-label="<?php echo esc_attr($aria_label); ?>"
    style="--cards-cols-md: <?php echo $cols_md; ?>; --cards-cols-lg: <?php echo $cols_lg; ?>;">

    <?php foreach ($items as $i => $it):
        $icon    = $it['icon']   ?? 'info-circle';
        $label   = $it['label']  ?? '';
        $title   = $it['title']  ?? '';
        $desc    = $it['desc']   ?? '';
        $badge   = $it['badge']  ?? '';
        $link    = $it['link']   ?? null;
        $accent  = $it['accent'] ?? ''; // アイコン円の色
        $bg_svg  = $it['bg_svg'] ?? ''; // 薄い装飾

        // メディア（画像 or インラインSVG）
        $media   = $it['media']  ?? null;
        $hasMedia = is_array($media) && !empty($media['type']);
    ?>
        <article class="info-card<?php echo $hasMedia ? ' has-media' : ''; ?>" role="listitem"
            <?php if ($accent) echo 'style="--card-accent:' . esc_attr($accent) . ';"'; ?>>

            <?php if ($bg_svg): ?>
                <div class="info-card__bg-svg" aria-hidden="true">
                    <?php echo $bg_svg; // 信頼できる自前SVGのみ想定 
                    ?>
                </div>
            <?php endif; ?>

            <?php if ($hasMedia): ?>
                <figure class="info-card__media">
                    <?php if ($media['type'] === 'img' && !empty($media['src'])): ?>
                        <img
                            src="<?php echo esc_url($media['src']); ?>"
                            alt="<?php echo esc_attr($media['alt'] ?? ''); ?>"
                            loading="lazy" decoding="async"
                            <?php
                            // 幅高さがあればCLS対策として出力
                            if (!empty($media['width']) && !empty($media['height'])) {
                                echo ' width="' . (int)$media['width'] . '" height="' . (int)$media['height'] . '"';
                            }
                            ?>>
                    <?php elseif ($media['type'] === 'svg' && !empty($media['html'])): ?>
                        <div class="info-card__media-svg" aria-hidden="true">
                            <?php echo $media['html']; // 自前SVGのみ 
                            ?>
                        </div>
                    <?php endif; ?>
                </figure>
            <?php endif; ?>

            <div class="info-card__body">
                <div class="info-card__icon" aria-hidden="true">
                    <i class="bi bi-<?php echo esc_attr($icon); ?>"></i>
                </div>

                <?php if ($badge): ?>
                    <span class="info-card__badge"><?php echo esc_html($badge); ?></span>
                <?php endif; ?>

                <?php if ($label): ?>
                    <p class="info-card__label"><?php echo esc_html($label); ?></p>
                <?php endif; ?>

                <?php if ($title): ?>
                    <h3 class="info-card__title"><?php echo esc_html($title); ?></h3>
                <?php endif; ?>

                <?php if ($desc): ?>
                    <p class="info-card__desc"><?php echo wp_kses_post($desc); ?></p>
                <?php endif; ?>

                <?php if ($link && !empty($link['url'])): ?>
                    <p class="info-card__link">
                        <a href="<?php echo esc_url($link['url']); ?>" target="_blank" rel="noopener">
                            <?php echo esc_html($link['text'] ?? '詳しく見る'); ?>
                            <i class="bi bi-arrow-right-short" aria-hidden="true"></i>
                        </a>
                    </p>
                <?php endif; ?>
            </div>
        </article>
    <?php endforeach; ?>
</div>