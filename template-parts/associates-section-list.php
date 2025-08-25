<?php

/**
 * 協力企業一覧（本体・BEM記法／Bootstrapは container のみ）
 * File: template-parts/associates-section-list.php
 *
 * 取得の優先順位：
 *  (1) ACFリピーター（推奨）
 *  (2) CPT 'associate'
 *  (3) テーマ内フォールバック配列
 *
 * 並び順は kana（ふりがな）で50音順。
 */
if (!defined('ABSPATH')) exit;

/** 画像フィールドから添付IDを抽出（ACF互換） */
$lp_get_image_id = static function ($field): int {
    if (is_array($field) && isset($field['ID'])) return (int) $field['ID'];
    if (is_array($field) && isset($field['id'])) return (int) $field['id'];
    if (is_numeric($field)) return (int) $field;
    return 0;
};

$companies = [];

/* ============================================================
 * (1) ACF リピーター
 * フィールド例:
 *  - associates (repeater)
 *     - name (text) 会社名［必須］
 *     - kana (text) ふりがな［推奨/並び順］
 *     - url  (url)  外部リンク［必須］
 *     - logo (image) 画像（ID推奨）［任意］
 * ========================================================== */
if (function_exists('have_rows') && have_rows('associates')) {
    while (have_rows('associates')) {
        the_row();
        $name = trim((string) get_sub_field('name'));
        $kana = trim((string) (get_sub_field('kana') ?: $name));
        $url  = trim((string) get_sub_field('url'));
        $logo_field = get_sub_field('logo');
        $logo_id = $lp_get_image_id($logo_field);

        if (!$name || !$url) continue;

        $companies[] = [
            'name'     => $name,
            'kana'     => $kana,
            'url'      => $url,
            'logo_id'  => $logo_id,
            'logo_url' => null,
        ];
    }
}

/* ============================================================
 * (2) CPT: associate
 * メタキー: kana（ふりがな）, url（外部リンク）
 * アイキャッチをロゴとして使用
 * ========================================================== */
if (empty($companies)) {
    $q = new WP_Query([
        'post_type'      => 'associate',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'meta_key'       => 'kana',
        'orderby'        => 'meta_value',
        'order'          => 'ASC',
        'no_found_rows'  => true,
    ]);

    if ($q->have_posts()) {
        while ($q->have_posts()) {
            $q->the_post();
            $name    = get_the_title();
            $kana    = get_post_meta(get_the_ID(), 'kana', true) ?: $name;
            $url     = get_post_meta(get_the_ID(), 'url', true);
            $logo_id = (int) get_post_thumbnail_id();

            if (!$name) continue;
            if (!$url) $url = get_the_permalink(); // 念のため

            $companies[] = [
                'name'     => $name,
                'kana'     => $kana,
                'url'      => $url,
                'logo_id'  => $logo_id,
                'logo_url' => null,
            ];
        }
        wp_reset_postdata();
    }
}

/* ============================================================
 * (3) テーマ内フォールバック
 * 現行HTMLの3社を初期値として用意
 * ========================================================== */
if (empty($companies)) {
    $companies = [
        [
            'name'     => '株式会社Nextwel',
            'kana'     => 'ねくすとうぇる',
            'url'      => 'https://nextwel.co.jp/',
            'logo_id'  => 0,
            'logo_url' => get_theme_file_uri('assets/img/associates/nextwel.webp'),
        ],
        // [
        //     'name'     => '株式会社Repro Accompany',
        //     'kana'     => 'りぷろあかんぱにー',
        //     'url'      => 'https://www.reproaccompany.com/',
        //     'logo_id'  => 0,
        //     'logo_url' => get_theme_file_uri('assets/img/associates/Repro-Accompany.webp'),
        // ],
        [
            'name'     => '株式会社ワライフ',
            'kana'     => 'わらいふ',
            'url'      => 'https://kabu-wa-life.com/',
            'logo_id'  => 0,
            'logo_url' => get_theme_file_uri('assets/img/associates/walife.webp'),
        ],
    ];
}

/* ============================================================
 * 50音順ソート（kana 正規化）
 * ========================================================== */
if (!empty($companies)) {
    usort($companies, static function ($a, $b) {
        $ka = mb_strtolower(mb_convert_kana((string) ($a['kana'] ?? ''), 'asKVc'));
        $kb = mb_strtolower(mb_convert_kana((string) ($b['kana'] ?? ''), 'asKVc'));
        return $ka <=> $kb;
    });
}

/** ロゴHTML（添付ID優先→URL→テキスト） */
$associates_logo_html = static function (array $c): string {
    $alt = esc_attr($c['name'] ?? '');
    if (!empty($c['logo_id'])) {
        return wp_get_attachment_image(
            (int) $c['logo_id'],
            'medium',
            false,
            [
                'class'    => 'associates__logo',
                'alt'      => $alt,
                'loading'  => 'lazy',
                'decoding' => 'async',
            ]
        );
    }
    if (!empty($c['logo_url'])) {
        return sprintf(
            '<img class="associates__logo" src="%s" alt="%s" loading="lazy" decoding="async">',
            esc_url($c['logo_url']),
            $alt
        );
    }
    // ロゴ無し時は会社名テキスト
    return sprintf('<span class="associates__name-text">%s</span>', esc_html($c['name'] ?? ''));
};
?>

<section class="section associates">
    <div class="container">
        <header class="section__header">
            <p class="section__sub">Associate Company</p>
            <h2 class="section__title">協力企業</h2>
        </header>

        <ul class="associates__list" role="list">
            <?php foreach ($companies as $company): ?>
                <li class="associates__item">
                    <a
                        class="associates__link"
                        href="<?php echo esc_url($company['url']); ?>"
                        target="_blank" rel="noopener noreferrer"
                        aria-label="<?php echo esc_attr(($company['name'] ?? '') . ' のサイトへ'); ?>">
                        <?php echo $associates_logo_html($company); ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>

        <p class="associates__note">※50音順に記載させていただいております。</p>
    </div>
</section>