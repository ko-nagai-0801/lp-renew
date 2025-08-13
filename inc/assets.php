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

    // 1) リセット
    wp_enqueue_style(
        'lp-reset',
        "$theme_uri/assets/css/reset.css",
        [],        // 依存なし（最初に読み込む）
        $ver
    );

    // 2) ライブラリ（Bootstrap, Swiper） ※リセット後に来るよう依存を付与
    wp_enqueue_style(
        'bootstrap',
        'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css',
        ['lp-reset'],   // ← 依存に lp-reset
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
        ['lp-reset'],   // ← 依存に lp-reset
        '11'
    );

    // 3) サイト共通
    foreach (['common', 'header', 'footer'] as $handle) {
        wp_enqueue_style(
            "lp-$handle",
            "$theme_uri/assets/css/$handle.css",
            ['bootstrap'],
            $ver
        );
    }

    // 4) トップページ
    if (is_front_page()) {
        wp_enqueue_style(
            'lp-front-page',
            "$theme_uri/assets/css/front-page.css",
            ['bootstrap'],
            $ver
        );
    }


    // ＊) Google Fonts（順序依存なし）
    wp_enqueue_style(
        'lp-fonts',
        'https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;700&family=Roboto:wght@400;500;700&display=swap',
        [],
        null
    );
    wp_enqueue_style(
        'lp-font-playball',
        'https://fonts.googleapis.com/css2?family=Playball&display=swap',
        [],
        null
    );
    wp_enqueue_style(
        'lp-font-kaushan',
        'https://fonts.googleapis.com/css2?family=KaushanScript&display=swap',
        [],
        null
    );


    /* ---------- 2. JavaScript ---------- */
    // ライブラリ
    wp_enqueue_script(
        'swiper',
        'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js',
        [],
        '11',
        true
    );

    // GSAP 本体
    wp_enqueue_script(
        'gsap',
        'https://cdn.jsdelivr.net/npm/gsap@3.12.2/dist/gsap.min.js',
        [],            // 依存なし
        '3.12.2',
        true           // フッター読み込み
    );
    // ScrollTrigger（gsap に依存）
    wp_enqueue_script(
        'gsap-scrolltrigger',
        'https://cdn.jsdelivr.net/npm/gsap@3.12.2/dist/ScrollTrigger.min.js',
        ['gsap'],
        '3.12.2',
        true
    );

    /* -----------------------------
	 *  main.js
	 * --------------------------- */
    wp_enqueue_script(
        'main',
        "$theme_uri/assets/js/main.js",
        ['swiper', 'gsap', 'gsap-scrolltrigger'],
        $ver,
        true
    );


    /* -----------------------------
 *  front-page.js（TOP だけ）
 *   main.js の後で読ませる
 * --------------------------- */
    if (is_front_page()) {
        wp_enqueue_script(
            'front-page',
            "$theme_uri/assets/js/front-page.js",
            ['main'],  // mainに依存
            $ver,
            true
        );
    }
}
add_action('wp_enqueue_scripts', 'lp_enqueue_assets');
