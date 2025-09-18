<?php

/**
 * ヘッダー（BEM）本体
 * theme root /header.php
 * @package LP_WP_Theme
 * @since 1.0.0
 *
 * 更新履歴:
 * - 1.1.0: Yoast優先のSEOメタへ変更。Yoast無効時のみ最小限のcanonical/OGPをフォールバック出力。
 * - 1.0.0: 初版
 */
if (!defined('ABSPATH')) exit;
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <!-- 文字コード / レスポンシブ基本設定 -->
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">

    <?php
    /**
     * SEOメタ（Yoastが有効なら Yoast に完全委譲）
     * - WPSEO_VERSION 定数が定義されていれば Yoast が動作中
     * - 無効時のみ、最低限の canonical / OGP / Twitter Card をフォールバック出力
     */
    if (!defined('WPSEO_VERSION')) :
        // 単一ページと一覧で正規URLを分岐（末尾スラッシュはサイト方針に合わせて）
        $is_single  = is_singular();
        $canonical  = $is_single ? get_permalink() : trailingslashit(home_url('/'));
        $site_name  = get_bloginfo('name');
        $desc       = get_bloginfo('description') ?: '';
        // OGP画像パス（テーマ内の実在ファイルに合わせて調整）
        $og_image   = get_theme_file_uri('assets/img/ogp.jpg');
    ?>
        <link rel="canonical" href="<?php echo esc_url($canonical); ?>">
        <meta property="og:site_name" content="<?php echo esc_attr($site_name); ?>">
        <meta property="og:type" content="<?php echo $is_single ? 'article' : 'website'; ?>">
        <meta property="og:title" content="<?php echo esc_attr(wp_get_document_title()); ?>">
        <meta property="og:description" content="<?php echo esc_attr($desc); ?>">
        <meta property="og:url" content="<?php echo esc_url($canonical); ?>">
        <meta property="og:image" content="<?php echo esc_url($og_image); ?>">
        <meta name="twitter:card" content="summary_large_image">
    <?php endif; ?>

    <?php
    /**
     * wp_head()
     * - プラグイン/テーマが登録した <head> 資産（CSS/JS/メタ等）を出力
     * - Yoast 等のSEO系メタもここで出力される
     */
    wp_head();
    ?>
</head>

<body <?php body_class(); ?>>
    <?php
    /**
     * wp_body_open()
     * - body直下に挿入したいフック（タグマネ等）のための標準フック
     */
    if (function_exists('wp_body_open')) {
        wp_body_open();
    }
    ?>

    <header class="header">
        <div class="header__inner">

            <!-- Logo ------------------------------------------------------------ -->
            <div class="header__logo">
                <a href="<?php echo esc_url(home_url('/')); ?>" class="header__logo-link">
                    <img
                        src="<?php echo esc_url(get_theme_file_uri('assets/img/neonlogo-01.webp')); ?>"
                        alt="株式会社ＬｉＮＥ ＰＡＲＫ"
                        class="header__logo-image">
                </a>
            </div>

            <!-- Global-nav ------------------------------------------------------- -->
            <nav class="header__nav" id="global-nav" aria-label="グローバルナビゲーション">
                <ul class="header__menu">
                    <li class="header__item">
                        <a href="<?php echo esc_url(home_url('/about/')); ?>" class="header__link">
                            <span class="header__link-main">ＬｉＮＥ ＰＡＲＫについて</span>
                            <span class="header__link-sub">About us</span>
                        </a>
                    </li>
                    <li class="header__item">
                        <a href="<?php echo esc_url(home_url('/services/')); ?>" class="header__link">
                            <span class="header__link-main">事業内容</span>
                            <span class="header__link-sub">Services</span>
                        </a>
                    </li>
                    <li class="header__item">
                        <a href="<?php echo esc_url(home_url('/join/')); ?>" class="header__link">
                            <span class="header__link-main">利用者募集</span>
                            <span class="header__link-sub">Join Us</span>
                        </a>
                    </li>
                    <li class="header__item">
                        <a href="<?php echo esc_url(home_url('/access/')); ?>" class="header__link">
                            <span class="header__link-main">アクセス</span>
                            <span class="header__link-sub">Access</span>
                        </a>
                    </li>
                    <li class="header__item">
                        <a href="<?php echo esc_url(home_url('/news/')); ?>" class="header__link">
                            <span class="header__link-main">お知らせ</span>
                            <span class="header__link-sub">NEWS</span>
                        </a>
                    </li>
                </ul>

                <!-- CTA Button -->
                <div class="header__cta">
                    <a href="<?php echo esc_url(home_url('/contact/')); ?>" class="header__cta-button">
                        <i class="bi bi-envelope-fill header__cta-icon" aria-hidden="true"></i>
                        お問い合わせ
                    </a>
                </div>
            </nav>

            <!-- Hamburger button (for SP) -------------------------------------- -->
            <button
                class="header__toggle"
                type="button"
                aria-label="メニューを開閉"
                aria-controls="global-nav"
                aria-expanded="false">
                <span class="header__toggle-line" aria-hidden="true"></span>
            </button>

        </div><!-- /.header__inner -->
    </header>