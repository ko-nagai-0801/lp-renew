<?php

/**
 * Hero section  (template-parts/section-hero.php)
 * BEM 命名
 */
if (! defined('ABSPATH')) {
  exit;
}
?>

<section class="hero">

  <!-- スライダー本体 -->
  <div class="hero__slider swiper js-hero-slider">
    <div class="hero__wrapper swiper-wrapper">
      <?php
      $hero_images = [
        'img/hero01.webp',
        'img/hero02.webp',
        'img/hero03.webp',
      ];
      foreach ($hero_images as $path) : ?>
        <div class="hero__slide swiper-slide">
          <div class="hero__bg"
            style="background-image:url('<?php echo esc_url(get_theme_file_uri($path)); ?>');">
          </div>
        </div>
      <?php endforeach; ?>
    </div>
    <!-- 必要ならページネーション / ナビボタン -->
  </div>

  <!-- タイトル -->
  <h1 class="hero__title">
    <span class="hero__title-text">No&nbsp;Border</span>
  </h1>

  <!-- スクロールヒント -->
  <button class="hero__scroll-btn" type="button" aria-label="Scroll">
    <span class="hero__scroll-circle">
      <span class="hero__scroll-arrow"></span>
    </span>
    <span class="hero__scroll-text">Scroll</span>
  </button>

</section>