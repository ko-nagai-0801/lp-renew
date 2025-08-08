<?php

/**
 * Contact-CTA section  – BEM
 * /wp-content/themes/LP_WP_Theme/template-parts/section-contact-cta.php
 */
if (! defined('ABSPATH')) {
  exit;
}
?>

<section id="contact" class="contact-cta parallax" data-parallax-speed="0.4">
  <div class="contact-cta__inner">

    <header class="section__header">
      <p class="section__sub">Contact</p>
      <h2 class="contact-cta__title">
        <span class="contact-cta__sub">Contact Us</span>
        お問い合わせ
      </h2>
    </header>

    <p class="contact-cta__message">
      仕事のご依頼やご見学についてなど<br>お気軽にご相談ください
    </p>

    <?php
    get_template_part(
      'components/cta',
      null,
      [
        'url' => home_url('/contact/'),
        'label' => 'Contact Us',
        'variant' => 'white', // 'primary' or 'white'
        'extra_class' => 'contact__cta' // 必要に応じて追加クラス
      ]
    );
    ?>

  </div><!-- /.contact-cta__inner -->
</section>