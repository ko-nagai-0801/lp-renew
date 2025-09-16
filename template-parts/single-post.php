<?php

/**
 * 個別投稿 本体（“戻る”動線最適化 + 前後リンクをゴースト化）
 * template-parts/single-post.php
 *
 * @package LP_WP_Theme
 * @since 1.0.0
 *
 * 更新履歴:
 * - 1.4.1 (2025-09-16): 戻り遷移時に sessionStorage の lpNewsBack を削除（次ページへ汚染防止）。
 * - 1.4.0 (2025-09-16): from_page / from_q の復元を撤去。referrer + sessionStorage で戻る最適化。
 * - 1.3.0 (2025-09-05): 前後リンクを青系ゴーストボタン（.c-ghost-btn）に。
 * - 1.2.0 (2025-09-03): 一覧復元（※本版で撤去）。
 * - 1.1.0: 日付表示を「更新日/投稿日」に変更。
 * - 1.0.0: 初版
 */
if (!defined('ABSPATH')) exit;

/**
 * 一覧（投稿ページ）のベースURLを取得
 * - 設定＞表示設定の「投稿ページ」優先
 * - なければ /news/（存在すれば）→ なければ post アーカイブ or ホーム
 */
function lp_get_news_base_url(): string
{
    $id = (int) get_option('page_for_posts');
    if ($id) return get_permalink($id);
    $news = get_page_by_path('news');
    if ($news && $news->post_status === 'publish') return get_permalink($news);
    return (get_post_type_archive_link('post') ?: home_url('/'));
}

// 使用フラグ（パーマリンク構造が“きれい”か）
$pretty   = get_option('permalink_structure') ? 'true' : 'false';
// 戻り先のデフォルト（/news/ など）
$newsBase = lp_get_news_base_url();
?>

<main class="page page--single" role="main">
    <?php get_template_part('template-parts/single-section', 'hero'); ?>

    <section class="section">
        <div class="container">

            <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                    <?php
                    // ===== 基礎メタ =====
                    $pub_machine = get_the_date('c');
                    $pub_human   = get_the_date('Y.m.d');
                    $mod_machine = get_the_modified_date('c');
                    $mod_human   = get_the_modified_date('Y.m.d');

                    $has_sig_update = function_exists('lp_news_has_significant_update')
                        ? lp_news_has_significant_update()
                        : (get_the_modified_time('U') !== get_the_time('U'));

                    $cats        = get_the_category();
                    $primary_cat = !empty($cats) ? $cats[0]->name : 'お知らせ';
                    ?>

                    <article id="post-<?php the_ID(); ?>" <?php post_class('entry'); ?>>

                        <!-- ヘッダー（見出し・メタ） -->
                        <header class="entry__header section__header">
                            <p class="section__sub"><?php echo esc_html($primary_cat); ?></p>
                            <h1 class="entry__title section__title"><?php the_title(); ?></h1>

                            <div class="entry__meta">
                                <?php if ($has_sig_update): ?>
                                    <time class="entry__date entry__date--updated" datetime="<?php echo esc_attr($mod_machine); ?>">
                                        更新日：<?php echo esc_html($mod_human); ?>
                                    </time>
                                    <span class="entry__date-sep"> （</span>
                                    <time class="entry__date entry__date--published" datetime="<?php echo esc_attr($pub_machine); ?>">
                                        投稿日：<?php echo esc_html($pub_human); ?>
                                    </time>
                                    <span class="entry__date-sep">）</span>
                                <?php else: ?>
                                    <time class="entry__date entry__date--published" datetime="<?php echo esc_attr($pub_machine); ?>">
                                        投稿日：<?php echo esc_html($pub_human); ?>
                                    </time>
                                <?php endif; ?>
                            </div>
                        </header>

                        <!-- 本文 -->
                        <div class="entry__content section__text">
                            <?php
                            the_content();
                            wp_link_pages(['before' => '<nav class="entry__pager" aria-label="ページ">', 'after' => '</nav>']);
                            ?>
                        </div>

                        <!-- タグ -->
                        <?php if ($tags = get_the_tags()): ?>
                            <footer class="entry__footer">
                                <ul class="entry__tags">
                                    <?php foreach ($tags as $t): ?>
                                        <li><a href="<?php echo esc_url(get_tag_link($t->term_id)); ?>">#<?php echo esc_html($t->name); ?></a></li>
                                    <?php endforeach; ?>
                                </ul>
                            </footer>
                        <?php endif; ?>

                        <?php
                        // 前後ナビ
                        $prev_post = get_adjacent_post(false, '', true);
                        $next_post = get_adjacent_post(false, '', false);
                        ?>
                        <nav class="entry__nav" aria-label="前後の記事">
                            <div class="entry__nav-prev">
                                <?php if ($prev_post): ?>
                                    <a class="c-ghost-btn c-ghost-btn--blue c-ghost-btn--sm c-ghost-btn--wipe-rtl" rel="prev"
                                        href="<?php echo esc_url(get_permalink($prev_post)); ?>">
                                        <i class="bi bi-chevron-left" aria-hidden="true"></i>
                                        <span>前の記事</span>
                                    </a>
                                <?php endif; ?>
                            </div>
                            <div class="entry__nav-next">
                                <?php if ($next_post): ?>
                                    <a class="c-ghost-btn c-ghost-btn--blue c-ghost-btn--sm c-ghost-btn--wipe-ltr" rel="next"
                                        href="<?php echo esc_url(get_permalink($next_post)); ?>">
                                        <span>次の記事</span>
                                        <i class="bi bi-chevron-right" aria-hidden="true"></i>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </nav>

                    </article>
            <?php endwhile;
            endif; ?>

            <?php
            // 戻り先（デフォルトはニュース一覧URL）
            $back_url = $newsBase;
            ?>
            <div class="entry__backline my-4">
                <?php
                // コンポーネントの中の <a> に JS を付与するため、識別用クラスを付与
                get_template_part('components/cta-ghost-black', null, [
                    'url'         => $back_url,
                    'label'       => '一覧へ戻る',
                    'with_icon'   => true,
                    'icon'        => 'chevron-double-left',
                    'size'        => 'sm',
                    'border_w'    => '1px',
                    'extra_class' => 'entry__backlink',
                ]);
                ?>
            </div>

            <script>
                // 一覧の状態を復元して戻す（URLを汚さない）
                (function() {
                    var wrap = document.querySelector('.entry__backlink');
                    if (!wrap) return;
                    var a = wrap.querySelector('a');
                    if (!a) return;

                    var built = null;
                    try {
                        var raw = sessionStorage.getItem('lpNewsBack');
                        if (raw) {
                            var st = JSON.parse(raw);
                            var url = st.base || <?php echo json_encode($newsBase); ?>;
                            if (st.q) url += (url.indexOf('?') >= 0 ? '&' : '?') + 'q=' + encodeURIComponent(st.q);
                            if (st.page && st.page >= 2) {
                                if (<?php echo $pretty; ?>) {
                                    if (!/\/$/.test(url)) url += '/';
                                    url += 'page/' + st.page + '/';
                                } else {
                                    url += (url.indexOf('?') >= 0 ? '&' : '?') + 'paged=' + st.page;
                                }
                            }
                            built = url + '#news-index';
                        }
                    } catch (e) {}

                    a.addEventListener('click', function(e) {
                        if (built) {
                            e.preventDefault();
                            try {
                                sessionStorage.removeItem('lpNewsBack');
                            } catch (e) {} // ← 保存値をクリア
                            location.href = built;
                            return;
                        }
                        // referrer が /news/ っぽいなら history.back()
                        try {
                            var ref = document.referrer;
                            if (ref) {
                                var u = new URL(ref);
                                if (u.host === location.host && /^\/news(\/|$)/.test(u.pathname)) {
                                    e.preventDefault();
                                    history.back();
                                    return;
                                }
                            }
                        } catch (e) {}
                        // それ以外は a.href（= /news/ ベース）へ通常遷移
                    });
                })();
            </script>

        </div><!-- /.container -->
    </section>

    <?php get_template_part('template-parts/section', 'contact-cta'); ?>
</main>