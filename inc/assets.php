<?php

/**
 * inc/assets.php
 * CSS / JS の読み込み
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

    // 4) TOPページ 専用CSS
    if (is_front_page()) {
        wp_enqueue_style(
            'lp-front-page',
            "$theme_uri/assets/css/front-page.css",
            ['lp-common'],    // 共通のあとに
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
    
    // 7) Access 専用CSS（サブページ共通のあと）
    if (is_page_template('page-access.php') || is_page('access')) {
        wp_enqueue_style(
            'lp-access',
            get_theme_file_uri('assets/css/access.css'),
            ['lp-subcommon'], // ← サブページ共通に依存
            $ver
        );
    }



    // ＊) Google Fonts
    wp_enqueue_style(
        'lp-fonts',
        'https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;700&family=Roboto:wght@400;500;700&display=swap',
        [],
        null
    );
    wp_enqueue_style('lp-font-playball', 'https://fonts.googleapis.com/css2?family=Playball&display=swap', [], null);
    wp_enqueue_style('lp-font-kaushan', 'https://fonts.googleapis.com/css2?family=KaushanScript&display=swap', [], null);

    /* ---------- 2. JavaScript ---------- */
    wp_enqueue_script('swiper', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js', [], '11', true);
    wp_enqueue_script('gsap', 'https://cdn.jsdelivr.net/npm/gsap@3.12.2/dist/gsap.min.js', [], '3.12.2', true);
    wp_enqueue_script('gsap-scrolltrigger', 'https://cdn.jsdelivr.net/npm/gsap@3.12.2/dist/ScrollTrigger.min.js', ['gsap'], '3.12.2', true);

    wp_enqueue_script('main', "$theme_uri/assets/js/main.js", ['swiper', 'gsap', 'gsap-scrolltrigger'], $ver, true);

    if (is_front_page()) {
        wp_enqueue_script('front-page', "$theme_uri/assets/js/front-page.js", ['main'], $ver, true);
    }
}
add_action('wp_enqueue_scripts', 'lp_enqueue_assets');
