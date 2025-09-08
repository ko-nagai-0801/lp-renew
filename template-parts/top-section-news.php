<?php
/**
 * TOPページ NEWS セクション（最新投稿5件）
 * template-parts/top-section-news.php
 *
 * 概要:
 *  - 「お知らせ」最新5件をカード風で表示
 *  - 並び順: ★投稿日（post_date）DESC に統一
 *  - 表示日付: ★投稿日（get_the_date）を使用
 *  - カテゴリ 'news' のみ表示（post時）
 *
 * @package LP_WP_Theme
 * @since 1.0.0
 *
 * 更新履歴:
 * - 1.1.0 (2025-09-08): 並び順を投稿日DESCに変更、表示日付も投稿日に統一、ignore_sticky_postsを追加。
 * - 1.0.0: 初版
 */
if (!defined('ABSPATH')) exit;
?>

<section id="news" class="news section section__card container" tabindex="-1">
  <div class="news__inner">

    <?php
    // ===== セクション見出し =====
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
        // ▼ “news” カテゴリを取得（postの場合に限定表示したい）
        $cat = get_category_by_slug('news');

        // ▼ 基本クエリ（ビルダーを通すが、この後で必ず投稿日ソートに上書き）
        $args = lp_news_build_query_args([
          'post_type'      => 'post',
          'posts_per_page' => 5,
          'no_found_rows'  => true, // 件数固定・ページャ不要なので true
        ]);

        // ▼ カテゴリ限定（存在する場合のみ）
        if ($cat && !is_wp_error($cat)) {
          $args['cat'] = (int) $cat->term_id;
        }

        // ▼ ★ここで“必ず”投稿日順（新しい順）に統一
        $args['orderby'] = 'date';    // post_date
        $args['order']   = 'DESC';    // 新しい順
        $args['ignore_sticky_posts'] = true; // 固定表示の影響を無効化して一貫した順序に

        $news_q = new WP_Query($args);

        if ($news_q->have_posts()):
          while ($news_q->have_posts()): $news_q->the_post();
            $post_id   = get_the_ID();
            $permalink = get_permalink();

            // ラベル/バッジ（「更新」バッジは残すが、表示する日付は“投稿日”で統一）
            $label = lp_news_get_label($post_id, 'post', 'お知らせ');
            $flags = lp_news_get_flags($post_id);

            // ★ 表示日付は投稿日
            $date_machine = get_the_date('c');
            $date_human   = get_the_date('Y.m.d');

            // タイトル（空対策）
            $title = get_the_title();
            if (trim(wp_strip_all_tags((string) $title)) === '') $title = 'No Title';
        ?>
            <li class="news__item">
              <a href="<?php echo esc_url($permalink); ?>" class="news__link">
                <img class="news__icon" src="<?php echo esc_url(get_theme_file_uri('assets/img/neonlogo-02.webp')); ?>" alt="">
                <div class="news__content">
                  <time datetime="<?php echo esc_attr($date_machine); ?>" class="news__date"><?php echo esc_html($date_human); ?></time>
                  <span class="news__label"><?php echo esc_html($label); ?></span>
                  <?php if (!empty($flags['new'])): ?>
                    <span class="news__badge news__badge--new" aria-label="新着">NEW</span>
                  <?php elseif (!empty($flags['updated'])): ?>
                    <span class="news__badge news__badge--updated" aria-label="更新">更新</span>
                  <?php endif; ?>
                  <p class="news__text"><?php echo esc_html($title); ?></p>
                </div>
              </a>
            </li>
        <?php
          endwhile;
          wp_reset_postdata();
        else:
        ?>
          <!-- 空表示（今日の日付を便宜的に表示） -->
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
  // セクション末尾のCTA
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
