<?php
/**
 * TOPページヒーローセクション
 * template-parts/top-section-hero.php
 * 
 * @package LP_WP_Theme
 * @since 1.0.0
 */
if (! defined('ABSPATH')) { exit; }
?>

<section class="hero">

  <!-- スライダー -->
  <div class="hero__slider swiper js-hero-slider">
    <div class="hero__wrapper swiper-wrapper">
      <?php
      $files = ['hero-01.webp', 'hero-02.webp', 'hero-03.webp']; // 画像ファイル名だけ列挙

      foreach ($files as $file) : // コロンでループ開始
        $url = esc_url(get_theme_file_uri("assets/img/$file"));
      ?>
        <div class="hero__slide swiper-slide">
          <div
            class="hero__bg"
            style="background-image:url('<?php echo $url; ?>');">
          </div>
        </div>
      <?php endforeach; ?> <!-- ループ終わり -->
    </div>
  </div>


  <?php
  /* -----------------------------
  Hero Title – アニメーション
------------------------------*/
  $animType = 'is-slide';

  $title   = 'No Border';
  $letters = preg_split('//u', $title, -1, PREG_SPLIT_NO_EMPTY);
  ?>
  <h1 class="hero__title" aria-label="<?php echo esc_html($title); ?>">
    <span class="hero__title-text <?php echo $animType; ?>">
      <?php foreach ($letters as $i => $char) : ?><span class="hero__letter" style="--i:<?php echo $i; ?>;" data-char="<?php echo esc_attr($char); ?>"><?php echo $char === ' ' ? '&nbsp;' : esc_html($char); ?></span><?php endforeach; ?>
    </span>
  </h1>

  <!-- スクロールヒント -->
  <button class="hero__scroll-btn" type="button" aria-label="Scroll">
    <span class="hero__scroll-circle">
      <span class="hero__scroll-arrow"></span>
    </span>
    <span class="hero__scroll-text">Scroll</span>
  </button>

</section>