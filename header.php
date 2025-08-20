<?php

/**
 * Header  – BEM
 * theme root /header.php
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">

    <!-- Canonical & OGP -->
    <link rel="canonical" href="<?php echo esc_url(home_url()); ?>">
    <meta property="og:site_name" content="<?php bloginfo('name'); ?>">
    <?php if (is_front_page()) : ?>
        <meta property="og:title" content="<?php bloginfo('name'); ?>">
        <meta property="og:description" content="足立区綾瀬にある就労継続支援B型事業所…">
        <meta property="og:url" content="<?php echo esc_url(home_url()); ?>">
        <meta property="og:image" content="<?php echo esc_url(get_theme_file_uri('img/ogp.jpg')); ?>">
    <?php endif; ?>

    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

    <header class="header">

        <div class="header__inner">

            <!-- Logo ------------------------------------------------------------ -->
            <div class="header__logo">
                <a href="<?php echo esc_url(home_url('/')); ?>" class="header__logo-link">
                    <img src="<?php echo esc_url(get_theme_file_uri('assets/img/neonlogo-01.webp')); ?>"
                        alt="株式会社ＬｉＮＥ ＰＡＲＫ" class="header__logo-image">
                </a>
            </div>

            <!-- Global-nav ------------------------------------------------------- -->
            <nav class="header__nav" id="global-nav">
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
                            <span class="header__link-sub">Business</span>
                        </a>
                    </li>
                    <li class="header__item">
                        <a href="<?php echo esc_url(home_url('/recruit/')); ?>" class="header__link">
                            <span class="header__link-main">採用情報</span>
                            <span class="header__link-sub">Recruit</span>
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
                            <span class="header__link-sub">News</span>
                        </a>
                    </li>
                </ul>
                
                <!-- CTA Button -->
                <div class="header__cta">
                    <a href="<?php echo esc_url(home_url('/contact/')); ?>" class="header__cta-button">
                        <i class="bi bi-envelope-fill header__cta-icon"></i>
                        お問い合わせ
                    </a>
                </div>
            </nav>

            <!-- Hamburger button (for SP) -------------------------------------- -->
            <button class="header__toggle" type="button" aria-label="メニューを開閉" aria-controls="global-nav">
                <span class="header__toggle-line"></span>
            </button>

        </div><!-- /.header__inner -->

    </header>