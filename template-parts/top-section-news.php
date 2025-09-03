<?php
/**
 * TOPページ NEWS セクション（最新投稿5件）
 * template-parts/top-section-news.php
 * - 並び順：更新日（modified）基準
 * - 表示日付：実質更新があれば更新日、なければ公開日
 * 
 * @package LP_WP_Theme
 * @since 1.0.0
 */
if (!defined('ABSPATH')) exit;
?>

<section id="news" class="news section section__card container" tabindex="-1">
  <div class="news__inner">

    <?php
    get_template_part('components/section-header', null, [
      'id'          => '',
      'sub'         => 'NEWS',
      'title'       => 'お知らせ',
      'tag'         => 'h2',
      'extra_class' => 'news__header'
    ]);
    ?>

    <div class="news__body">
      <ul class="news__list">
        <?php
        $cat  = get_category_by_slug('news');
        $args = lp_news_build_query_args([
          'post_type'           => 'post',
          'posts_per_page'      => 5,
          'no_found_rows'       => true,
        ]);
        if ($cat && !is_wp_error($cat)) $args['cat'] = (int) $cat->term_id;

        $news_q = new WP_Query($args);

        if ($news_q->have_posts()):
          while ($news_q->have_posts()): $news_q->the_post();
            $post_id   = get_the_ID();
            $permalink = get_permalink();

            $label       = lp_news_get_label($post_id, 'post', 'お知らせ');
            $flags       = lp_news_get_flags($post_id);
            $disp_date   = lp_news_get_display_date($post_id);
            $date_machine= $disp_date['machine'];
            $date_human  = $disp_date['human'];
            $title       = get_the_title();
        ?>
            <li class="news__item">
              <a href="<?php echo esc_url($permalink); ?>" class="news__link">
                <img class="news__icon" src="<?php echo esc_url(get_theme_file_uri('assets/img/neonlogo-02.webp')); ?>" alt="">
                <div class="news__content">
                  <time datetime="<?php echo esc_attr($date_machine); ?>" class="news__date"><?php echo esc_html($date_human); ?></time>
                  <span class="news__label"><?php echo esc_html($label); ?></span>
                  <?php if ($flags['new']): ?>
                    <span class="news__badge news__badge--new" aria-label="新着">NEW</span>
                  <?php elseif ($flags['updated']): ?>
                    <span class="news__badge news__badge--updated" aria-label="更新">更新</span>
                  <?php endif; ?>
                  <p class="news__text"><?php echo esc_html($title); ?></p>
                </div>
              </a>
            </li>
        <?php
          endwhile; wp_reset_postdata();
        else:
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
