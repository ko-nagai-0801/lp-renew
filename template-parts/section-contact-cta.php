<?php
/**
 * Contact-CTA section  – BEM
 * /wp-content/themes/LP_WP_Theme/template-parts/section-contact-cta.php
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
?>

<section id="contact" class="contact-cta">
  <div class="contact-cta__inner">

    <h2 class="contact-cta__title">
      <span class="contact-cta__sub">Contact&nbsp;us</span>
      お問い合わせ
    </h2>

    <p class="contact-cta__message">
      仕事のご依頼やご見学についてなど<br>お気軽にご相談ください
    </p>

    <div class="contact-cta__link">
      <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"
         class="button button--primary">
        Contact&nbsp;us
      </a>
    </div>

  </div><!-- /.contact-cta__inner -->
</section>
