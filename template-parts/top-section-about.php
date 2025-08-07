<?php

/**
 * template-parts/top-section-about.php
 */
if (! defined('ABSPATH')) {
    exit;
}
?>
<section id="about" class="about section section__card container-fluid" aria-labelledby="about__heading">
    <p class="about__big-text" data-text="ABOUT US" aria-hidden="true"></p>

    <div class="about__inner ">


        <!-- ===== About Text ===== -->
        <div class="about__content">
            <?php
            get_template_part(
                'components/section-header',
                null,
                [
                    'id' => '', // 見出しID（任意）
                    'sub' => 'About us', // 小見出し
                    'title' => 'ＬｉＮＥ ＰＡＲＫについて', // メイン見出し
                    'tag' => 'h2', // h1〜h6（省略可）
                    'extra_class' => 'about__header' // 追加クラス（任意）
                ]
            );
            ?>
            <h3 class="about__catch section__catch">誰もが当たり前のことを、当たり前にできる世界へ</h3>

            <div class="about__text section__text">
                <p>できること、できないことには必ず環境要因があると私たちは考えます。</p>
                <p>「自分が正しい」ではなく、多様な人々が共存していることを理解し、<br>
                一人ひとりの気持ちを共有しながら共に進んでいく――。</p>
                <p>私たちはそんな世界の実現を理想とし、<br>
                    その歯車の一つとなることを目指しています。</p>
            </div>
            <?php
            get_template_part(
                'components/cta',
                null,
                [
                    'url' => home_url('/about/'),
                    'label' => 'About Us',
                    'variant' => 'primary', // 'primary' or 'white'
                    'extra_class' => 'about__cta' // 追加クラス
                ]
            );
            ?>
        </div><!-- /.about__content -->


        <!-- ===== About images ===== -->
        <div class="about__visual parallax" data-parallax-speed="0.45">
            <figure class="about__image">
                <img src="<?php echo esc_url(get_theme_file_uri('assets/img/about-img-main.webp')); ?>"
                    alt="ＬｉＮＥ ＰＡＲＫのワークスペース" loading="lazy">
            </figure>
        </div><!-- /.about__visual -->






    </div><!-- /.about__inner -->
</section>