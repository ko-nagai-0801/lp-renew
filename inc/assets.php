<?php

/**
 * CSS / JS の読み込み
 * inc/assets.php
 *
 * @package LP_WP_Theme
 * @since 1.0.0
 *
 * 更新履歴:
 * - 1.3.0 (2025-09-09): GAイベント送信用スクリプト lp-ga-events を全ページで読み込み
 */

if (! defined('ABSPATH')) {
    exit;
}

function lp_enqueue_assets()
{

    $theme_uri = get_theme_file_uri();
    $ver       = wp_get_theme()->get('Version'); // テーマと同じバージョン

    /* ---------- 1. CSS ---------- */

    // 1) リセット（最初）
    wp_enqueue_style(
        'lp-reset',
        "$theme_uri/assets/css/reset.css",
        [],
        $ver
    );

    // 2) ライブラリ（Resetの後）
    wp_enqueue_style(
        'bootstrap',
        'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css',
        ['lp-reset'],
        '5.2.0'
    );
    wp_enqueue_style(
        'bootstrap-icons',
        'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.css',
        ['bootstrap'],
        '1.13.1'
    );
    wp_enqueue_style(
        'swiper',
        'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css',
        ['lp-reset'],
        '11'
    );

    // 3) サイト共通（どのページでも）
    foreach (['common', 'header', 'footer'] as $handle) {
        wp_enqueue_style(
            "lp-$handle",
            "$theme_uri/assets/css/$handle.css",
            ['bootstrap'],
            $ver
        );
    }

    // 3.5) サブページ共通（TOP 以外で使用）
    // 先に register しておくと依存指定が安全
    wp_register_style(
        'lp-subcommon',
        "$theme_uri/assets/css/sub-common.css",
        ['lp-common'],   // 共通CSSのあとに適用
        $ver
    );
    if (! is_front_page()) {
        wp_enqueue_style('lp-subcommon');
    }

    // 3.6) NEWS 共通（TOP と Newsインデックスで使用）
    if (is_front_page() || is_page_template('page-news-index.php') || is_page('news')) {
        wp_enqueue_style(
            'lp-news',
            get_theme_file_uri('assets/css/news.css'),
            ['lp-common'], // 共通CSSのあとに
            $ver
        );
    }

    // 4) TOPページ 専用CSS
    if (is_front_page()) {
        wp_enqueue_style(
            'lp-front-page',
            "$theme_uri/assets/css/front-page.css",
            ['lp-common', 'lp-news'],    // 共通のあとに
            $ver
        );
    }

    // 5) About Us 専用CSS（サブページ共通のあと）
    if (is_page_template('page-about.php') || is_page('about')) {
        wp_enqueue_style(
            'lp-about',
            get_theme_file_uri('assets/css/about.css'),
            ['lp-subcommon'], // ← サブページ共通に依存
            $ver
        );
    }

    // 6) Join Us 専用CSS（サブページ共通のあと）
    if (is_page_template('page-join.php') || is_page('join')) {
        wp_enqueue_style(
            'lp-join',
            get_theme_file_uri('assets/css/join.css'),
            ['lp-subcommon'], // ← サブページ共通に依存
            $ver
        );
    }

    // 7) Access 専用CSS
    if (is_page_template('page-access.php') || is_page('access')) {
        wp_enqueue_style(
            'lp-access',
            get_theme_file_uri('assets/css/access.css'),
            ['lp-subcommon'], // ← サブページ共通に依存
            $ver
        );
    }

    // 8) Services Index 専用CSS
    if (is_page_template('page-services-index.php') || is_page('services')) {
        wp_enqueue_style(
            'lp-services-index',
            get_theme_file_uri('assets/css/services-index.css'),
            ['lp-subcommon'], // ← サブページ共通に依存
            $ver
        );
    }

    // 9) Services Web 専用CSS（サブページ共通のあと）
    if (is_page_template('page-services-web.php') || is_page('web')) {
        wp_enqueue_style(
            'lp-services-web',
            get_theme_file_uri('assets/css/services-web.css'),
            ['lp-subcommon'], // ← サブページ共通に依存
            $ver
        );
    }

    // 10) Services Design 専用CSS（サブページ共通のあと）
    if (is_page_template('page-services-design.php') || is_page('design')) {
        wp_enqueue_style(
            'lp-services-design',
            get_theme_file_uri('assets/css/services-design.css'),
            ['lp-subcommon'], // サブページ共通に依存
            $ver
        );
    }

    // 11) Services SNS 専用CSS（サブページ共通のあと）
    if (is_page_template('page-services-sns.php') || is_page('sns')) {
        wp_enqueue_style(
            'lp-services-sns',
            get_theme_file_uri('assets/css/services-sns.css'),
            ['lp-subcommon'], // サブページ共通に依存
            $ver
        );
    }

    // 12) Services Lightwork 専用CSS
    if (is_page_template('page-services-lightwork.php') || is_page('lightwork')) {
        wp_enqueue_style(
            'lp-services-lightwork',
            get_theme_file_uri('assets/css/services-lightwork.css'),
            ['lp-subcommon'],
            $ver
        );
    }

    // 13) Services Others 専用CSS
    if (is_page_template('page-services-others.php') || is_page('others')) {
        wp_enqueue_style(
            'lp-services-others',
            get_theme_file_uri('assets/css/services-others.css'),
            ['lp-subcommon'],
            $ver
        );
    }

    // X) News Index 専用CSS
    if (is_page_template('page-news-index.php') || is_page('news')) {
        wp_enqueue_style(
            'lp-news-index',
            get_theme_file_uri('assets/css/news-index.css'),
            ['lp-subcommon', 'lp-news'], // ← 依存に lp-news を追加
            $ver
        );
    }

    // News Single 専用CSS
    if (is_single() && get_post_type() === 'post') {
        wp_enqueue_style(
            'lp-news-single',
            get_theme_file_uri('assets/css/news-single.css'),
            ['lp-subcommon'], // サブページ共通のあと
            $ver
        );
    }


    // X) Associate Company 専用CSS
    if (is_page_template('page-associate-company.php') || is_page('associates')) {
        wp_enqueue_style(
            'lp-associates',
            get_theme_file_uri('assets/css/associates.css'),
            ['lp-subcommon'], // ← サブページ共通に依存
            $ver
        );
    }

    // Privacy Policy専用CSS
    if (is_page_template('page-privacy.php') || is_page('privacy')) {
        wp_enqueue_style(
            'lp-privacy',
            get_theme_file_uri('assets/css/privacy.css'),
            ['lp-subcommon'], // ← サブページ共通に依存
            $ver
        );
    }


    // Contact / Confirm / Thanks で共通CSSを読み込む
    $lp_is_contact_flow = (
        is_page_template('page-contact.php') ||
        is_page('contact') ||
        (bool) get_query_var('lp_confirm') ||
        (bool) get_query_var('lp_thanks')
    );
    if ($lp_is_contact_flow) {
        wp_enqueue_style(
            'lp-contact',
            get_theme_file_uri('assets/css/contact.css'),
            ['lp-subcommon'], // サブページ共通に依存
            $ver
        );
    }


    // 404 Page 専用CSS
    if (is_404()) {
        wp_enqueue_style(
            'lp-404',
            get_theme_file_uri('assets/css/404.css'),
            ['lp-subcommon'], // サブページ共通に依存
            $ver
        );
    }




    // ＊) Google Fonts
    wp_enqueue_style(
        'lp-fonts-pack',
        'https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;700&family=Roboto:wght@400;500;700&family=Playball&family=Kaushan+Script&display=swap',
        [],
        null
    );

    /* ---------- 2. JavaScript ---------- */
    wp_enqueue_script('swiper', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js', [], '11', true);
    wp_enqueue_script('gsap', 'https://cdn.jsdelivr.net/npm/gsap@3.12.2/dist/gsap.min.js', [], '3.12.2', true);
    wp_enqueue_script('gsap-scrolltrigger', 'https://cdn.jsdelivr.net/npm/gsap@3.12.2/dist/ScrollTrigger.min.js', ['gsap'], '3.12.2', true);

    wp_enqueue_script('main', "$theme_uri/assets/js/main.js", ['swiper', 'gsap', 'gsap-scrolltrigger'], $ver, true);

    if (is_front_page()) {
        wp_enqueue_script('front-page', "$theme_uri/assets/js/front-page.js", ['main'], $ver, true);
    }

    /* Contact/Confirm/Thanks だけで contact.js を読み込み */
    $lp_is_contact_flow = (
        is_page_template('page-contact.php') ||
        is_page('contact') ||
        (bool) get_query_var('lp_confirm') ||
        (bool) get_query_var('lp_thanks')
    );
    if ($lp_is_contact_flow) {
        wp_enqueue_script(
            'lp-contact',
            "$theme_uri/assets/js/contact.js",
            ['main'],
            $ver,
            true
        );
    }

    // GA4 送信用の共通イベント束（cta_click / file_download / generate_lead など）
    // gtag() は <head> 側（inc/analytics.php）で先に定義済みの前提
    wp_enqueue_script(
        'lp-ga-events',
        "$theme_uri/assets/js/ga-events.js",
        [],   // 依存がなければ空。main.js依存にしたいなら ['main'] に
        $ver,
        true  // フッター読込
    );
}
add_action('wp_enqueue_scripts', 'lp_enqueue_assets');

// 404ページは noindex を明示
add_action('wp_head', function () {
    if (is_404()) {
        echo '<meta name="robots" content="noindex, nofollow">' . "\n";
    }
}, 1);
