<?php

/**
 * About section – simple two-column
 */
if (! defined('ABSPATH')) {
    exit;
}
?>
<section id="about" class="about section section__card container" aria-labelledby="about__heading">
    <div class="about__inner ">


        <!-- ===== Left : About images ===== -->
        <div class="about__visual js-shrink-in-section">
            <img src="<?php echo esc_url(get_theme_file_uri('assets/img/about-img-01.webp')); ?>" alt="" class="about__images about__image-01" loading="lazy">
            <img src="<?php echo esc_url(get_theme_file_uri('assets/img/about-img-02.webp')); ?>" alt="" class="about__images about__image-02" loading="lazy">
            <img src="<?php echo esc_url(get_theme_file_uri('assets/img/about-img-03.webp')); ?>" alt="" class="about__images about__image-03" loading="lazy">
        </div>

        <!-- ===== Right : Text block ===== -->
        <div class="about__content">
            <?php
            get_template_part(
                'components/section-header',
                null,
                [
                    'id' =>'',// 見出しID（任意）
                    'sub' => 'About us', // 小見出し
                    'title' => 'ＬｉＮＥ ＰＡＲＫ について', // メイン見出し
                    'tag' => 'h2', // h1〜h6（省略可）
                    'extra_class' => 'about__header' // 追加クラス（任意）
                ]
            );
            ?>
            <h3 class="about__catch section__catch">誰もが当たり前のことを、当たり前にできる世界へ</h3>

            <div class="about__text section__text">
                <p>できること、できないことには必ず環境要因があると私たちは考えます。</p>
                <p>「自分が正しい」ではなく、多様な人々が共存していることを理解し、
                    ひとり一人の気持ちを共有しながら共に進んでいく――。</p>
                <p>私たちはそんな世界の実現を理想とし、その歯車の一つとなることを目指しています。</p>
            </div>
            <?php
            get_template_part(
                'components/cta',
                null,
                [
                    'url' => home_url('/about/'),
                    'label' => 'View More',
                    'variant' => 'primary', // 'primary' or 'white'
                    'extra_class' => 'about__cta' // 必要に応じて追加クラス
                ]
            );
            ?>
        </div><!-- /.about__content -->




    </div><!-- /.about__inner -->
</section>