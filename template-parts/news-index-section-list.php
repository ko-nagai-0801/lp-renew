<?php
/**
 * NEWS Index 一覧ループ
 * template-parts/news-index-section-list.php
 *  - 抜粋スニペット + ヒット強調（ひら/カナ・半/全・大/小・数字半全 無視で一致）
 *  - 本文後半ヒットもタイトル下にヒット周辺を表示
 *  - ページネーションは検索語を保持しつつ #news-index へ
 *  - 総ページ数が 1 でもページャ表示
 *  - ★ 更新があれば日付表示は更新日に、並び順も更新日（modified）基準
 * 
 * @package LP_WP_Theme
 * @since 1.0.0
 */
if (!defined('ABSPATH')) exit;

/* ===== クエリ準備（orderby = modified） ==================================== */

$paged     = max(1, (int) get_query_var('paged'), (int) get_query_var('page'));
$post_type = post_type_exists('notice') ? 'notice' : 'post';
$q_word    = isset($_GET['q']) ? sanitize_text_field(wp_unslash($_GET['q'])) : '';

$q_kv   = mb_convert_kana($q_word, 'KV', 'UTF-8');
$q_hira = lp_to_hiragana($q_kv);
$q_kata = lp_to_katakana($q_hira);
$q_fold = lp_fold_for_search($q_word);

$args = lp_news_build_query_args([
  'post_type'      => $post_type,
  'paged'          => $paged,
  'posts_per_page' => get_option('posts_per_page'),
  'no_found_rows'  => false,         // ページネーションあり
]);

if ($post_type === 'post') {
  $news_cat = get_category_by_slug('news');
  if ($news_cat && !is_wp_error($news_cat)) $args['cat'] = (int) $news_cat->term_id;
}

if ($q_word !== '') {
  $cands_raw = [$q_word, $q_hira, $q_kata, $q_fold];
  $seen = []; $cands = [];
  foreach ($cands_raw as $v) {
    $v = lp_normalize_spaces($v);
    if ($v === '' || isset($seen[$v])) continue;
    $seen[$v] = true; $cands[] = $v;
  }
  $args['s'] = implode(' ', $cands);
}

$q = new WP_Query($args);
$has_posts = ((int) $q->found_posts) > 0;
?>

<section id="news-index" class="news section container" tabindex="-1">
  <div class="news__inner">

    <?php
    get_template_part('components/section-header', null, [
      'id' => '',
      'sub' => 'NEWS',
      'title' => 'お知らせ',
      'tag' => 'h2',
      'extra_class' => 'news__header'
    ]);
    ?>

    <form class="news__filter" method="get" action="<?php echo esc_url(get_permalink()); ?>">
      <label class="news__filter-label" for="news-index-search">キーワード検索</label>
      <div class="news__filter-row">
        <input class="news__filter-input" id="news-index-search" type="search" name="q"
          value="<?php echo esc_attr($q_word); ?>" placeholder="キーワードを入力" inputmode="search">
        <button class="news__filter-submit" type="submit">検索</button>
      </div>
    </form>

    <div class="news__body">
      <ul class="news__list">
        <?php if ($q->have_posts()) : while ($q->have_posts()) : $q->the_post();
          $post_id   = get_the_ID();
          $permalink = get_permalink();

          // 共通ヘルパー
          $label       = lp_news_get_label($post_id, $post_type, 'お知らせ');
          $flags       = lp_news_get_flags($post_id); // ['new'=>, 'updated'=>, 'significant'=>]
          $disp_date   = lp_news_get_display_date($post_id);
          $date_machine= $disp_date['machine'];
          $date_human  = $disp_date['human'];

          // タイトル＆検索ハイライト
          $title_raw  = get_the_title();
          $title_html = $q_word !== '' ? lp_news_highlight_html($title_raw, $q_word) : esc_html($title_raw);

          // 本文スニペット
          $plain = lp_news_plain_content($post_id);
          [$main_snip, $extra_snips] = lp_build_main_and_extra_snippets($plain, $q_word, 140, 60, 3);
          $excerpt_html = $q_word !== '' ? lp_news_highlight_html($main_snip, $q_word) : esc_html($main_snip);
          $extra_htmls  = [];
          foreach ($extra_snips as $sn) { $extra_htmls[] = $q_word !== '' ? lp_news_highlight_html($sn, $q_word) : esc_html($sn); }
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

                <p class="news__text"><?php echo $title_html; ?></p>

                <?php if ($excerpt_html !== ''): ?>
                  <p class="news__excerpt"><?php echo $excerpt_html; ?></p>
                <?php endif; ?>

                <?php if (!empty($extra_htmls)): ?>
                  <ul class="news__matches" aria-label="本文の他の一致箇所">
                    <?php foreach ($extra_htmls as $eh): ?>
                      <li class="news__match"><?php echo $eh; ?></li>
                    <?php endforeach; ?>
                  </ul>
                <?php endif; ?>
              </div>
            </a>
          </li>
        <?php endwhile; wp_reset_postdata(); else: ?>
          <li class="news__empty" role="status" aria-live="polite">
            <p class="news__empty-text">
              <?php echo ($q_word !== '') ? '「' . esc_html($q_word) . '」を含むお知らせは見つかりませんでした。' : '現在、掲載中のお知らせはありません。'; ?>
            </p>
          </li>
        <?php endif; ?>
      </ul>
    </div>

    <?php if ($q_word !== ''): ?>
      <p class="news__return"><a href="<?php echo esc_url(get_permalink()); ?>">検索をクリアして一覧へ戻る</a></p>
    <?php endif; ?>

    <?php if ($has_posts):
      $query_args = []; if ($q_word !== '') $query_args['q'] = $q_word;
      $big   = 999999999; $total = max(1, (int)$q->max_num_pages);
      $links = paginate_links([
        'base'         => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
        'format'       => '?paged=%#%',
        'current'      => $paged,
        'total'        => $total,
        'mid_size'     => 2,
        'prev_text'    => '前へ',
        'next_text'    => '次へ',
        'type'         => 'list',
        'add_args'     => $query_args,
        'add_fragment' => '#news-index',
      ]);
      if (empty($links) && $total === 1) {
        $links = '<ul class="page-numbers"><li><span class="page-numbers current" aria-current="page">1</span></li></ul>';
      }
    ?>
      <?php if ($links): ?>
        <nav class="news__pager" aria-label="ニュースのページ送り"><?php echo $links; ?></nav>
      <?php endif; ?>
    <?php endif; ?>

  </div><!-- /.news__inner -->
</section>
