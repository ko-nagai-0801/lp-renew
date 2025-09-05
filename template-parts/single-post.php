<?php

/**
 * 個別投稿 本体（“戻る”ボタン対応）
 * template-parts/single-post.php
 *
 * @package LP_WP_Theme
 * @since 1.0.0
 *
 * 更新履歴:
 * - 1.2.0 (2025-09-03): from_page / from_q を解釈して「← 一覧に戻る」ボタンを追加。
 *                       戻り先は /news/page/N/（もしくは ?paged=N）+ 必要なら ?q=… ＋ #news-index。
 * - 1.1.0: 日付表示を「更新日：yyyy.mm.dd（投稿日：yyyy.mm.dd）」に変更。
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

// from_page / from_q から “戻るURL” を生成（#news-indexへ）
function lp_build_back_to_news_url(): string
{
    $base      = lp_get_news_base_url();
    $from_page = isset($_GET['from_page']) ? max(0, (int) $_GET['from_page']) : 0;
    $from_q    = isset($_GET['from_q']) ? sanitize_text_field(wp_unslash($_GET['from_q'])) : '';

    // ページ番号
    if ($from_page >= 2) {
        if (get_option('permalink_structure')) {
            $base = trailingslashit($base) . user_trailingslashit('page/' . $from_page, 'paged');
        } else {
            $base = add_query_arg('paged', $from_page, $base);
        }
    }
    // 検索クエリを復元したい場合
    if ($from_q !== '') $base = add_query_arg('q', $from_q, $base);

    // 一覧セクションへスクロール
    return $base . '#news-index';
}
?>

<main class="page page--single" role="main">
    <?php
    // サブヒーロー（タイトル等）
    get_template_part('template-parts/single-section', 'hero');
    ?>

    <section class="section">
        <div class="container">



            <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                    <?php
                    /* =========================================================
         * 日付・カテゴリ等の基礎データ
         * ---------------------------------------------------------
         * - “有意な更新”の定義は inc/news-functions.php の
         *   lp_news_has_significant_update() に従う（ε=60秒など）。
         * - 同関数が未定義の場合は「公開時刻と更新時刻が異なる」で代替。
         * ======================================================= */
                    $pub_machine = get_the_date('c');     // 例: 2025-09-02T12:34:56+09:00
                    $pub_human   = get_the_date('Y.m.d'); // 例: 2025.09.02
                    $mod_machine = get_the_modified_date('c');
                    $mod_human   = get_the_modified_date('Y.m.d');

                    // “有意な更新”があれば true（フィルタで閾値可変）
                    $has_sig_update = function_exists('lp_news_has_significant_update')
                        ? lp_news_has_significant_update()
                        : (get_the_modified_time('U') !== get_the_time('U'));

                    // カテゴリ（post のみ先頭カテゴリ、無い場合はフォールバック）
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
                                    <!-- 更新が“有意”な場合は更新日を先に出す -->
                                    <time class="entry__date entry__date--updated" datetime="<?php echo esc_attr($mod_machine); ?>">
                                        更新日：<?php echo esc_html($mod_human); ?>
                                    </time>
                                    <span class="entry__date-sep"> （</span>
                                    <time class="entry__date entry__date--published" datetime="<?php echo esc_attr($pub_machine); ?>">
                                        投稿日：<?php echo esc_html($pub_human); ?>
                                    </time>
                                    <span class="entry__date-sep">）</span>
                                <?php else: ?>
                                    <!-- 通常は投稿日のみ -->
                                    <time class="entry__date entry__date--published" datetime="<?php echo esc_attr($pub_machine); ?>">
                                        投稿日：<?php echo esc_html($pub_human); ?>
                                    </time>
                                <?php endif; ?>
                            </div>
                        </header>

                        <!-- アイキャッチ -->
                        <?php if (has_post_thumbnail()): ?>
                            <figure class="entry__thumb">
                                <?php
                                // 遅延読み込みでLCPを抑制（必要に応じて 'decoding' => 'async' など調整）
                                the_post_thumbnail('large', [
                                    'class'   => 'entry__img',
                                    'loading' => 'lazy',
                                ]);
                                ?>
                            </figure>
                        <?php endif; ?>

                        <!-- 本文 -->
                        <div class="entry__content">
                            <?php
                            the_content();

                            // ページ分割された投稿用のページャ
                            wp_link_pages([
                                'before' => '<nav class="entry__pager" aria-label="ページ">',
                                'after'  => '</nav>',
                            ]);
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



                        <!-- 前後ナビ -->
                        <nav class="entry__nav" aria-label="前後の記事">
                            <div class="entry__nav-prev"><?php previous_post_link('%link', '← 前の記事'); ?></div>
                            <div class="entry__nav-next"><?php next_post_link('%link', '次の記事 →'); ?></div>
                        </nav>

                    </article>
            <?php endwhile;
            endif; ?>

            <?php
            // 戻り先URL（from_page / from_q を考慮）
            $back_url = lp_build_back_to_news_url();
            ?>
            <div class="entry__backline my-4">
                <?php
                get_template_part('components/cta-ghost-black', null, [
                    'url'       => $back_url,
                    'label'     => '一覧へ戻る',
                    'with_icon' => true,                 // 左アイコンON
                    'icon'      => 'chevron-double-left', // ← これが「<<」
                    'size'      => 'sm',                 // 小さめ
                    'border_w'  => '1px',
                    'extra_class' => 'entry__backlink',  // 既存クラスを残すなら
                ]);
                ?>
            </div>

            <!-- 既存の NEWS CTA は一覧トップへ（そのままでもOK） -->
            <!-- <div class="entry__back c-cta mt-5">
                <?php
                get_template_part('components/cta-gradient', null, [
                    'url'         => lp_get_news_base_url(), // 一覧トップ
                    'label'       => 'NEWS',
                    'variant'     => 'primary',
                    'extra_class' => 'entry__back-cta',
                ]);
                ?>
            </div> -->

        </div><!-- /.container -->
    </section>

    <?php
    // 共通の問い合わせCTA / トップへ戻る
    get_template_part('template-parts/section', 'contact-cta');
    get_template_part('includes/to-top');
    ?>
</main>