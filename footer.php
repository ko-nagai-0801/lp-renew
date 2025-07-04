<?php

/**
 * Footer – BEM
 * theme root /footer.php
 */
?>
<footer class="footer">

    <div class="footer__inner">

        <!-- Company block --------------------------------------------------- -->
        <address class="footer__company">

            <div class="footer__logo">
                <img src="<?php echo esc_url(get_theme_file_uri('img/logo-white.svg')); ?>" alt="">
            </div>

            <p class="footer__address">
                株式会社ＬｉＮＥ&nbsp;ＰＡＲＫ<br>
                〒120-0005 東京都足立区綾瀬&nbsp;2-27-4&nbsp;D1&nbsp;AYASE&nbsp;2F
            </p>

            <p class="footer__mail">
                <img src="<?php echo esc_url(get_theme_file_uri('img/common/mail-icon.svg')); ?>" alt="">
                <a href="mailto:info@linepark.co.jp" data-nosnippet>info@linepark.co.jp</a>
            </p>

            <p class="footer__phone">
                <img src="<?php echo esc_url(get_theme_file_uri('img/common/phone-icon.svg')); ?>" alt="">
                <a href="tel:03-4400-5584">03-4400-5584</a><br>
                <span class="footer__time">受付：月-金&nbsp;9:00-17:00</span>
            </p>

            <!-- SNS -->
            <ul class="footer__sns">
                <li class="footer__sns-item">
                    <a href="https://www.instagram.com/linepark.ayase/" target="_blank" aria-label="Instagram">
                        <img src="<?php echo esc_url(get_theme_file_uri('img/common/instagram-icon.svg')); ?>" alt="">
                    </a>
                </li>
                <li class="footer__sns-item">
                    <a href="https://twitter.com/LiNEPARK_ayase" target="_blank" aria-label="X">
                        <img src="<?php echo esc_url(get_theme_file_uri('img/common/x-icon.webp')); ?>" alt="">
                    </a>
                </li>
                <li class="footer__sns-item">
                    <a href="https://www.youtube.com/@fusafusachannel" target="_blank" aria-label="YouTube">
                        <img src="<?php echo esc_url(get_theme_file_uri('img/common/youtube-icon.svg')); ?>" alt="">
                    </a>
                </li>
                <li class="footer__sns-item">
                    <a href="https://www.tiktok.com/@linepark.inc" target="_blank" aria-label="TikTok">
                        <img src="<?php echo esc_url(get_theme_file_uri('img/common/tictok-icon.svg')); ?>" alt="">
                    </a>
                </li>
            </ul>

        </address>

        <!-- Footer-nav ------------------------------------------------------ -->
        <nav class="footer__nav">
            <ul class="footer__nav-list">
                <li class="footer__nav-item"><a href="/about/">ＬｉＮＥ&nbsp;ＰＡＲＫ について</a></li>
                <li class="footer__nav-item"><a href="/#business">事業内容</a></li>
                <li class="footer__nav-item"><a href="/products-web/">┗ Web制作</a></li>
                <li class="footer__nav-item"><a href="/products-movie/">┗ 動画編集</a></li>
                <li class="footer__nav-item"><a href="/products-design/">┗ デザイン制作</a></li>
                <li class="footer__nav-item"><a href="/access/">アクセスガイド</a></li>
                <li class="footer__nav-item"><a href="/#news">お知らせ</a></li>
                <li class="footer__nav-item"><a href="/recruit/">採用情報</a></li>
                <li class="footer__nav-item"><a href="/associates/">協力企業</a></li>
                <li class="footer__nav-item"><a href="/contact/">お問い合わせ</a></li>
                <li class="footer__nav-item"><a href="/privacy/">プライバシーポリシー</a></li>
            </ul>
        </nav>

    </div><!-- /.footer__inner -->

    <small class="footer__copyright">
        &copy;&nbsp;<?php echo date('Y'); ?>&nbsp;<?php bloginfo('name'); ?> Inc.
    </small>

</footer>

<?php wp_footer(); ?>
</body>

</html>