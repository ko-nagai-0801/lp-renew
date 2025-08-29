<?php

/**
 * TOPページ NEWS セクション（最新投稿5件）
 * template-parts/top-section-news.php
 *
 * @package LP_WP_Theme
 * @since 1.0.0
 */
if (!defined('ABSPATH')) exit;
?>

<section id="news" class="news section section__card container" tabindex="-1">
  <div class="news__inner">

    <?php
    // セクション見出し
    get_template_part(
      'components/section-header',
      null,
      [
        'id'          => '',
        'sub'         => 'NEWS',
        'title'       => 'お知らせ',
        'tag'         => 'h2',
        'extra_class' => 'news__header'
      ]
    );
    ?>

    <div class="news__body">
      <ul class="news__list">
        <?php
        $cat = get_category_by_slug('news');
        // ▼ 最新投稿を取得（5件 必要に応じて調整）
        $args = [
          'post_type'           => 'post',
          'posts_per_page'      => 5,
          'orderby'             => 'date',
          'order'               => 'DESC',
          'post_status'         => 'publish',
          'ignore_sticky_posts' => true,
          'no_found_rows'       => true,
        ];
        if ($cat && ! is_wp_error($cat)) {
          $args['cat'] = (int) $cat->term_id; // slugがある時だけ絞り込み
        }
        $news_q = new WP_Query($args);

        if ($news_q->have_posts()) :
          while ($news_q->have_posts()) : $news_q->the_post();
            $permalink    = get_permalink();
            $date_machine = get_the_date('Y-m-d'); // datetime属性用
            $date_human   = get_the_date('Y.m.d'); // 表示用
            $title        = get_the_title();

            // バッジ表示：最初のカテゴリ名。無ければ「お知らせ」
            $label = 'お知らせ';
            $cats  = get_the_category();
            if (!empty($cats)) {
              $label = $cats[0]->name;
            }
        ?>
            <li class="news__item">
              <a href="<?php echo esc_url($permalink); ?>" class="news__link">
                <img class="news__icon" src="<?php echo esc_url(get_theme_file_uri('assets/img/neonlogo-02.webp')); ?>" alt="">
                <div class="news__content">
                  <time datetime="<?php echo esc_attr($date_machine); ?>" class="news__date">
                    <?php echo esc_html($date_human); ?>
                  </time>
                  <span class="news__label"><?php echo esc_html($label); ?></span>
                  <p class="news__text"><?php echo esc_html($title); ?></p>
                </div>
              </a>
            </li>
          <?php
          endwhile;
          wp_reset_postdata();
        else :
          // 投稿が無い場合のフォールバック
          ?>
          <li class="news__item">
            <div class="news__link" aria-disabled="true">
              <img class="news__icon" src="<?php echo esc_url(get_theme_file_uri('assets/img/neonlogo-02.webp')); ?>" alt="">
              <div class="news__content">
                <time class="news__date"><?php echo esc_html(date_i18n('Y.m.d')); ?></time>
                <span class="news__label">お知らせ</span>
                <p class="news__text">現在、掲載中のお知らせはありません。</p>
              </div>
            </div>
          </li>
        <?php endif; ?>
      </ul>
    </div>

  </div><!-- /.news__inner -->

  <?php
  // 「もっと見る」などのCTA
  get_template_part(
    'components/cta-gradient',
    null,
    [
      'url'         => home_url('/news/'),
      'label'       => 'NEWS',
      'variant'     => 'primary',
      'extra_class' => 'news__cta'
    ]
  );
  ?>
</section>