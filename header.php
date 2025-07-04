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
                    <img src="<?php echo esc_url(get_theme_file_uri('img/neonlogo01.png')); ?>"
                        alt="株式会社ＬｉＮＥ ＰＡＲＫ" class="header__logo-image">
                </a>
            </div>

            <!-- Global-nav ------------------------------------------------------- -->
            <nav class="header__nav">
                <ul class="header__menu">
                    <li class="header__item">
                        <a href="<?php echo esc_url(home_url('/about/')); ?>" class="header__link">
                            ＬｉＮＥ&nbsp;ＰＡＲＫについて<span class="header__sub">About</span>
                        </a>
                    </li>
                    <li class="header__item">
                        <a href="<?php echo esc_url(home_url('/#business')); ?>" class="header__link">
                            事業内容<span class="header__sub">Business</span>
                        </a>
                    </li>
                    <li class="header__item">
                        <a href="<?php echo esc_url(home_url('/recruit/')); ?>" class="header__link">
                            採用情報<span class="header__sub">Recruit</span>
                        </a>
                    </li>
                    <li class="header__item">
                        <a href="<?php echo esc_url(home_url('/access/')); ?>" class="header__link">
                            アクセスガイド<span class="header__sub">Access</span>
                        </a>
                    </li>
                    <li class="header__item">
                        <a href="<?php echo esc_url(home_url('/#news')); ?>" class="header__link">
                            お知らせ<span class="header__sub">News</span>
                        </a>
                    </li>
                    <li class="header__item header__item--cta">
                        <a href="<?php echo esc_url(home_url('/contact/')); ?>" class="header__link header__link--cta">
                            <img src="<?php echo esc_url(get_theme_file_uri('img/common/contact-icon.svg')); ?>"
                                alt="" class="header__cta-icon"> お問い合わせ
                        </a>
                    </li>
                </ul>
            </nav>

            <!-- Hamburger button (for SP) -------------------------------------- -->
            <button class="header__toggle" aria-label="メニューを開閉">
                <span class="header__toggle-line"></span>
            </button>

        </div><!-- /.header__inner -->

    </header>