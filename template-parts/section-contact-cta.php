<?php

/**
 * Contact-CTA section  – BEM
 * /wp-content/themes/LP_WP_Theme/template-parts/section-contact-cta.php
 */
if (! defined('ABSPATH')) {
  exit;
}
?>

<section id="contact" class="contact-cta section parallax" data-parallax-speed="0.4">

  <div class="contact__inner">
    <div class="contact__content">

      <?php
      get_template_part('components/section-header', null, [
        'sub'     => 'Contact Us',
        'title'   => 'お問い合わせ',
        'variant' => 'contact-cta', //追加クラス
      ]);
      ?>

      <p class="contact-cta__text section__text">
        仕事のご依頼やご見学についてなど<br>お気軽にご相談ください
      </p>

      <?php
      get_template_part(
        'components/cta-ghost',
        null,
        [
          'url' => home_url('/contact/'),
          'label' => 'Contact Us',
          'variant' => 'white', // 'primary' or 'white'
          'extra_class' => 'contact__cta' // 必要に応じて追加クラス
        ]
      );
      ?>

    </div><!-- /.contact__content -->
  </div><!-- /.contact__inner -->
</section>