<?php
/**
 * News section – BEM
 * template-parts/section-news.php
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
?>

<section id="news" class="news section container" tabindex="-1">
  <div class="news__inner">

    <header class="news__header">
      <h2 class="news__title"><span class="news__sub">News</span>お知らせ</h2>
    </header>

    <div class="news__body">
      <ul class="news__list">
        <?php
          $news_items = [
            [ 'date'=>'2024.11.13', 'href'=>'/news/new-year-holiday.php',
              'title'=>'年末年始休業のお知らせ' ],
            [ 'date'=>'2024.02.01', 'href'=>'/news/important-notice.php',
              'title'=>'【重要なお知らせ】<br>事業内容の変更につきまして' ],
            [ 'date'=>'2024.02.01', 'href'=>'/news/newspage02.php',
              'title'=>'見学について' ],
            [ 'date'=>'2024.02.01', 'href'=>'/news/newspage03.php',
              'title'=>'体験について' ],
            [ 'date'=>'2024.02.01', 'href'=>'/news/newspage04.php',
              'title'=>'面談について' ],
          ];

          foreach ( $news_items as $n ) : ?>
            <li class="news__item">
              <a href="<?php echo esc_url( home_url( $n['href'] ) ); ?>" class="news__link">
                <img src="<?php echo esc_url( get_theme_file_uri( 'img/neonlogo02.png' ) ); ?>" alt="">
                <div class="news__content">
                  <time datetime="<?php echo esc_attr( $n['date'] ); ?>"
                        class="news__date"><?php echo esc_html( $n['date'] ); ?></time>
                  <span class="news__label">お知らせ</span>
                  <p class="news__text"><?php echo wp_kses_post( $n['title'] ); ?></p>
                </div>
              </a>
            </li>
        <?php endforeach; ?>
      </ul>
    </div>

  </div><!-- /.news__inner -->
</section>
