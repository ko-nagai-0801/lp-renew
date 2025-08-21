<?php

/**
 * Footer – BEM
 * theme root /footer.php
 */
?>
<footer
    class="footer is-bright"
    style="
    --footer-image:url('<?php echo esc_url(get_theme_file_uri('assets/img/footer-bg.webp')); ?>');
    --footer-pos:center 60%;
    --mask-0:.80; --mask-1:.55; --mask-2:.55;
  ">


    <div class="footer__inner">

        <!-- Company block --------------------------------------------------- -->
        <address class="footer__company">

            <div class="footer__logo py-5">
                <img src="<?php echo esc_url(get_theme_file_uri('assets/img/footer-logo.svg')); ?>" alt="">
            </div>

            <div class="footer__company-group">
                <div class="footer__company-item">
                    <p class="footer__address py-3">
                        株式会社ＬｉＮＥ&nbsp;ＰＡＲＫ<br>
                        〒120-0005<br>
                        東京都足立区綾瀬&nbsp;2-27-4&nbsp;D1&nbsp;AYASE&nbsp;2F
                    </p>
                </div>
                <div class="footer__company-item">
                    <p class="footer__mail">
                        <i class="bi bi-send-fill"></i>
                        <a href="mailto:info@linepark.co.jp" data-nosnippet>info@linepark.co.jp</a>
                    </p>
                    <p class="footer__phone">
                        <i class="bi bi-telephone-fill"></i>
                        <a href="tel:03-4400-5584">03-4400-5584</a><br>
                        <span class="footer__time">受付：月-金&nbsp;9:00-17:00（土・日・祝日は閉所）</span>
                    </p>
                </div>
            </div>

            <!-- SNS -->
            <div class="footer__company-item footer__company-item-sns">
                <ul class="footer__sns-list">
                    <li class="footer__sns-item">
                        <a href="https://www.instagram.com/linepark.ayase/" target="_blank" aria-label="Instagram">
                            <img src="<?php echo esc_url(get_theme_file_uri('assets/img/common/instagram-icon.svg')); ?>" alt="">
                        </a>
                    </li>
                    <li class="footer__sns-item">
                        <a href="https://twitter.com/LiNEPARK_ayase" target="_blank" aria-label="X">
                            <img src="<?php echo esc_url(get_theme_file_uri('assets/img/common/x-icon.webp')); ?>" alt="">
                        </a>
                    </li>
                    <li class="footer__sns-item">
                        <a href="https://www.youtube.com/@fusafusachannel" target="_blank" aria-label="YouTube">
                            <img src="<?php echo esc_url(get_theme_file_uri('assets/img/common/youtube-icon.svg')); ?>" alt="">
                        </a>
                    </li>
                    <li class="footer__sns-item">
                        <a href="https://www.tiktok.com/@linepark.inc" target="_blank" aria-label="TikTok">
                            <img src="<?php echo esc_url(get_theme_file_uri('assets/img/common/tictok-icon.svg')); ?>" alt="">
                        </a>
                    </li>
                </ul>
            </div>

        </address>

        <!-- Footer-nav ------------------------------------------------------ -->
        <nav class="footer__nav">
            <ul class="footer__nav-list">
                <li class="footer__nav-item">
                    <a href="<?php echo esc_url(home_url('/about/')); ?>">ＬｉＮＥ&nbsp;ＰＡＲＫについて</a>
                </li>
                <li class="footer__nav-item">
                    <a href="<?php echo esc_url(home_url('/services/')); ?>">事業内容</a>
                    <ul class="footer__service-nav-list">
                        <li class="footer__service-nav-item">
                            <a href="<?php echo esc_url(home_url('/products-web/')); ?>">Web制作</a>
                        </li>
                        <li class="footer__service-nav-item">
                            <a href="<?php echo esc_url(home_url('/products-design/')); ?>">デザイン制作</a>
                        </li>
                        <li class="footer__service-nav-item">
                            <a href="<?php echo esc_url(home_url('/products-movie/')); ?>">動画編集</a>
                        </li>
                        <li class="footer__service-nav-item">
                            <!-- TODO: スラッグ確定後に変更してください -->
                            <a href="<?php echo esc_url(home_url('/products-lightwork/')); ?>">軽作業</a>
                        </li>
                        <li class="footer__service-nav-item">
                            <!-- TODO: スラッグ確定後に変更してください -->
                            <a href="<?php echo esc_url(home_url('/products-others/')); ?>">その他の業務</a>
                        </li>
                    </ul>
                </li>
                <li class="footer__nav-item">
                    <a href="<?php echo esc_url(home_url('/access/')); ?>">アクセス</a>
                </li>
                <li class="footer__nav-item">
                    <a href="<?php echo esc_url(home_url('/news/')); ?>">お知らせ</a>
                </li>
                <li class="footer__nav-item">
                    <a href="<?php echo esc_url(home_url('/join/')); ?>">利用者募集</a>
                </li>
                <li class="footer__nav-item">
                    <a href="<?php echo esc_url(home_url('/associates/')); ?>">協力企業</a>
                </li>
                <li class="footer__nav-item">
                    <a href="<?php echo esc_url(home_url('/contact/')); ?>">お問い合わせ</a>
                </li>
                <li class="footer__nav-item">
                    <a href="<?php echo esc_url(home_url('/privacy/')); ?>">プライバシーポリシー</a>
                </li>
            </ul>
        </nav>

    </div><!-- /.footer__inner -->


    <small class="footer__copyright">
        &copy;&nbsp;2022&nbsp;<?php bloginfo('name'); ?> Inc.
    </small>

</footer>

<?php wp_footer(); ?>
</body>

</html>