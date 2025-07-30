<?php

/**
 * About section – simple two-column
 */
if (! defined('ABSPATH')) {
    exit;
}
?>
<section id="about" class="about section container" aria-labelledby="about__heading">
    <div class="about__inner ">

        
        <!-- ===== Left : About images ===== -->
        <div class="about__visual js-shrink-in-section">
            <img src="<?php echo esc_url(get_theme_file_uri('img/about-img-01.webp')); ?>" alt="" class="about__images about__image-01" loading="lazy">
            <img src="<?php echo esc_url(get_theme_file_uri('img/about-img-02.webp')); ?>" alt="" class="about__images about__image-02" loading="lazy">
            <img src="<?php echo esc_url(get_theme_file_uri('img/about-img-03.webp')); ?>" alt="" class="about__images about__image-03" loading="lazy">
        </div>

        <!-- ===== Right : Text block ===== -->
        <div class="about__content">
            <header class="about__header section__header">
                <p class="about__sub section__sub">About&nbsp;us</p>
                <h2 id="about__heading" class="about__title section__title">ＬｉＮＥ&nbsp;ＰＡＲＫ について</h2>
            </header>

            <h3 class="about__catch section__catch">誰もが当たり前のことを、当たり前にできる世界へ</h3>

            <div class="about__text section__text">
                <p>できること、できないことには必ず環境要因があると私たちは考えます。</p>
                <p>「自分が正しい」ではなく、多様な人々が共存していることを理解し、
                    ひとり一人の気持ちを共有しながら共に進んでいく――。</p>
                <p>私たちはそんな世界の実現を理想とし、その歯車の一つとなることを目指しています。</p>
            </div>

            <footer class="about__cta u-cta">
                <a href="<?php echo esc_url(home_url('/about/')); ?>"
                    class="button button--primary">
                    View&nbsp;More <i class="fa-solid fa-caret-right" aria-hidden="true"></i>
                </a>
            </footer>
        </div><!-- /.about__content -->




    </div><!-- /.about__inner -->
</section>