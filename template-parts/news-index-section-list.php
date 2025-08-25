<?php

/**
 * NEWS Index 一覧ループ
 * template-parts/news-index-section-list.php
 * 優先度:
 *  - CPT 'notice' があればそれを優先
 *  - 無ければ post（カテゴリー 'news' があれば優先、無ければ全投稿）
 *  - ページネーション対応
 * BEMブロック: .news-index
 */
if (!defined('ABSPATH')) exit;

$container_id = 'news-index-list';

// 現在ページ
$paged = max(1, (int) get_query_var('paged'), (int) get_query_var('page'));

// 投稿タイプ判定
$post_type = post_type_exists('notice') ? 'notice' : 'post';

// ベース引数
$args = [
    'post_type'      => $post_type,
    'post_status'    => 'publish',
    'paged'          => $paged,
    'posts_per_page' => get_option('posts_per_page'),
    'no_found_rows'  => false,
];

// post の場合は 'news' カテゴリーを優先
if ($post_type === 'post') {
    $news_cat = get_category_by_slug('news');
    if ($news_cat && !is_wp_error($news_cat)) {
        $args['cat'] = (int) $news_cat->term_id;
    }
}

// 検索（任意）
$s = isset($_GET['s']) ? sanitize_text_field(wp_unslash($_GET['s'])) : '';
if ($s !== '') $args['s'] = $s;

$q = new WP_Query($args);
?>
<section class="section news-index" id="<?php echo esc_attr($container_id); ?>">
    <div class="container">

        <?php
        // 見出し（共通コンポーネント）
        get_template_part('components/section-header', null, [
            'id'      => 'news-index-heading',
            'sub'     => 'NEWS',
            'title'   => 'お知らせ',
            'tag'     => 'h2',
            'variant' => 'news-index',
        ]);
        ?>

        <!-- 任意：簡易検索 -->
        <form class="news-index__filter" method="get" action="<?php echo esc_url(get_permalink()); ?>">
            <label class="news-index__filter-label" for="news-index-search">キーワード検索</label>
            <input
                class="news-index__filter-input"
                id="news-index-search"
                type="search"
                name="s"
                value="<?php echo esc_attr($s); ?>"
                placeholder="キーワードを入力"
                inputmode="search">
            <button class="news-index__filter-submit" type="submit">検索</button>
        </form>

        <?php if ($q->have_posts()) : ?>
            <div class="news-index__list" role="list">
                <?php while ($q->have_posts()) : $q->the_post(); ?>
                    <article class="news-index__item" role="listitem">
                        <a class="news-index__link" href="<?php the_permalink(); ?>">
                            <time class="news-index__date" datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                                <?php echo esc_html(get_the_date('Y.m.d')); ?>
                            </time>
                            <h3 class="news-index__title"><?php the_title(); ?></h3>
                            <p class="news-index__excerpt">
                                <?php echo esc_html(wp_trim_words(get_the_excerpt() ?: wp_strip_all_tags(get_the_content()), 40, '…')); ?>
                            </p>
                        </a>
                    </article>
                <?php endwhile;
                wp_reset_postdata(); ?>
            </div>

            <?php
            // ページネーション
            $big = 999999999;
            $links = paginate_links([
                'base'      => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
                'format'    => '?paged=%#%',
                'current'   => $paged,
                'total'     => (int) $q->max_num_pages,
                'mid_size'  => 2,
                'prev_text' => '前へ',
                'next_text' => '次へ',
                'type'      => 'list',
            ]);
            if ($links) :
            ?>
                <nav class="news-index__pager" aria-label="ニュースのページ送り">
                    <?php echo $links; ?>
                </nav>
            <?php endif; ?>

        <?php else : ?>
            <p class="news-index__empty">現在、お知らせはありません。</p>
        <?php endif; ?>
    </div>
</section>