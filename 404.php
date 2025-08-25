<?php

/**
 * 404 Not Found
 * theme root /404.php
 * BEMのみ / Bootstrapは container のみ
 */
if (!defined('ABSPATH')) exit;

get_header(); ?>

<main class="page page--notfound" role="main">
    <section class="section notfound" aria-labelledby="notfound-heading">
        <div class="container">

            <header class="section__header notfound__header">
                <p class="section__sub">Oops!</p>
                <h1 id="notfound-heading" class="section__title">ページが見つかりませんでした</h1>
            </header>

            <!-- 大きな 404 ビジュアル -->
            <div class="notfound__hero" aria-hidden="true">
                <span class="notfound__digit" style="--i:0">4</span>
                <span class="notfound__digit" style="--i:1">0</span>
                <span class="notfound__digit" style="--i:2">4</span>
            </div>

            <p class="notfound__lead">
                入力したURLが間違っているか、ページが移動・削除された可能性があります。
            </p>

            <!-- アクション群 -->
            <nav class="notfound__actions" aria-label="便利なリンク">
                <a class="notfound__btn notfound__btn--primary" href="<?php echo esc_url(home_url('/')); ?>">トップへ戻る</a>
                <a class="notfound__btn" href="javascript:history.back()">ひとつ前のページへ</a>
                <a class="notfound__btn" href="<?php echo esc_url(home_url('/contact/')); ?>">お問い合わせ</a>
                <a class="notfound__btn" href="<?php echo esc_url(home_url('/sitemap/')); ?>">サイトマップ</a>
            </nav>

            <!-- 検索フォーム（BEMで自前マークアップ） -->
            <form class="notfound__search" role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>">
                <label class="notfound__search-label" for="nf-s">サイト内検索</label>
                <div class="notfound__search-row">
                    <input class="notfound__search-input" id="nf-s" type="search" name="s" placeholder="キーワードを入力" inputmode="search" />
                    <button class="notfound__search-submit" type="submit">検索</button>
                </div>
            </form>

            <!-- 最新のお知らせ/投稿（最大6件） -->
            <?php
            // CPT 'notice' があれば優先、なければ post
            $type = post_type_exists('notice') ? 'notice' : 'post';
            $recent = new WP_Query([
                'post_type'      => $type,
                'post_status'    => 'publish',
                'posts_per_page' => 6,
                'no_found_rows'  => true,
            ]);
            ?>
            <?php if ($recent->have_posts()) : ?>
                <section class="notfound__recent" aria-labelledby="nf-recent-heading">
                    <h2 id="nf-recent-heading" class="notfound__recent-title">最近の更新</h2>
                    <ul class="notfound__recent-list" role="list">
                        <?php while ($recent->have_posts()) : $recent->the_post(); ?>
                            <li class="notfound__recent-item" role="listitem">
                                <a class="notfound__recent-link" href="<?php the_permalink(); ?>">
                                    <time class="notfound__recent-date" datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                                        <?php echo esc_html(get_the_date('Y.m.d')); ?>
                                    </time>
                                    <span class="notfound__recent-text"><?php the_title(); ?></span>
                                </a>
                            </li>
                        <?php endwhile;
                        wp_reset_postdata(); ?>
                    </ul>
                </section>
            <?php endif; ?>

        </div>
    </section>
</main>

<?php get_footer();
