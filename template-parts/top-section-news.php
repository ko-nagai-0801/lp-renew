<?php
/**
 * News section – BEM
 * template-parts/section-news.php
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
?>

<section id="news" class="news section section__card container" tabindex="-1">
  <div class="news__inner">

  <?php
  get_template_part(
    'components/section-header',
    null,
    [
      'id' => '', // 見出しID（任意）
      'sub' => 'NEWS', // 小見出し
      'title' => 'お知らせ', // メイン見出し
      'tag' => 'h2', // h1〜h6（省略可）
      'extra_class' => 'news__header' // 追加クラス（任意）
    ]
  );
  ?>

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
                <img class="news__icon" src="<?php echo esc_url( get_theme_file_uri( 'assets/img/neonlogo-02.webp' ) ); ?>" alt="">
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
