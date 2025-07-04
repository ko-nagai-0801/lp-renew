<?php
/*--------------------------------------------------------------
  Theme setup
--------------------------------------------------------------*/
function lp_setup()
{

    // HTML5 マークアップ
    add_theme_support('html5', ['search-form', 'comment-form', 'gallery']);

    // アイキャッチ
    add_theme_support('post-thumbnails');

    // ナビメニュー
    register_nav_menus([
        'primary' => 'プライマリナビゲーション',
    ]);
}
add_action('after_setup_theme', 'lp_setup');


/*--------------------------------------------------------------
  Assets (CSS / JS)
--------------------------------------------------------------*/
function lp_enqueue_assets()
{

    $theme_uri = get_theme_file_uri();
    $ver       = wp_get_theme()->get('Version');  // テーマと同じバージョン

    /* ---------- 1. CSS ---------- */
    // Reset → Google Fonts → 外部ライブラリ → テーマ内
    wp_enqueue_style(
        'lp-reset',
        "$theme_uri/css/reset.css",
        [],
        $ver
    );

    wp_enqueue_style(
        'lp-fonts',
        'https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;700&family=Roboto:wght@400;500;700&display=swap',
        [],
        null
    );

    wp_enqueue_style(
        'bootstrap',
        'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css',
        [],
        '5.2.0'
    );

    wp_enqueue_style(
        'swiper',
        'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css',
        [],
        '11'
    );

    // テーマ固有（依存＝reset）
    foreach (['common', 'header', 'footer', 'top'] as $handle) {
        wp_enqueue_style(
            "lp-$handle",
            "$theme_uri/css/$handle.css",
            ['lp-reset'],
            $ver
        );
    }

    /* ---------- 2. JavaScript ---------- */
    // ライブラリ
    wp_enqueue_script(
        'swiper',
        'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js',
        [],
        '11',
        true
    );

    wp_enqueue_script(
        'gsap',
        'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.9.1/gsap.min.js',
        [],
        '3.9.1',
        true
    );

    wp_enqueue_script(
        'gsap-scrolltrigger',
        'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.9.1/ScrollTrigger.min.js',
        ['gsap'],
        '3.9.1',
        true
    );

    // すべての TOP 用ロジックを集約
    wp_enqueue_script(
        'lp-top',
        "$theme_uri/assets/js/top.js",
        ['swiper', 'gsap', 'gsap-scrolltrigger'], // 依存
        $ver,
        true
    );
}
add_action('wp_enqueue_scripts', 'lp_enqueue_assets');
