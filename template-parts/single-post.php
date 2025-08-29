<?php

/**
 * 個別投稿 本体
 * template-parts/single-post.php
 *
 * @package LP_WP_Theme
 * @since 1.0.0
 */
if (!defined('ABSPATH')) exit;
?>

<main class="page page--single" role="main">
    <?php
    get_template_part('template-parts/single-section', 'hero');
    ?>

    <section class="section">
        <div class="container">

            <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                    <?php
                    $date_machine = get_the_date('c');
                    $date_human   = get_the_date('Y.m.d');
                    $is_updated   = (get_the_modified_time('U') !== get_the_time('U'));
                    $cats         = get_the_category();
                    $primary_cat  = !empty($cats) ? $cats[0]->name : 'お知らせ';
                    ?>

                    <article id="post-<?php the_ID(); ?>" <?php post_class('entry'); ?>>

                        <header class="entry__header section__header">
                            <p class="section__sub"><?php echo esc_html($primary_cat); ?></p>
                            <h1 class="entry__title section__title"><?php the_title(); ?></h1>
                            <div class="entry__meta">
                                <time class="entry__date" datetime="<?php echo esc_attr($date_machine); ?>">
                                    投稿日：<?php echo esc_html($date_human); ?>
                                </time>
                                <?php if ($is_updated): ?>
                                    <span class="entry__updated">（更新：<?php echo esc_html(get_the_modified_date('Y.m.d')); ?>）</span>
                                <?php endif; ?>
                            </div>
                        </header>


                        <?php if (has_post_thumbnail()): ?>
                            <figure class="entry__thumb">
                                <?php the_post_thumbnail('large', ['class' => 'entry__img', 'loading' => 'lazy']); ?>
                            </figure>
                        <?php endif; ?>

                        <div class="entry__content">
                            <?php the_content();
                            wp_link_pages(['before' => '<nav class="entry__pager" aria-label="ページ">', 'after' => '</nav>']); ?>
                        </div>

                        <?php if ($tags = get_the_tags()): ?>
                            <footer class="entry__footer">
                                <ul class="entry__tags">
                                    <?php foreach ($tags as $t): ?>
                                        <li><a href="<?php echo esc_url(get_tag_link($t->term_id)); ?>">#<?php echo esc_html($t->name); ?></a></li>
                                    <?php endforeach; ?>
                                </ul>
                            </footer>
                        <?php endif; ?>

                        <nav class="entry__nav" aria-label="前後の記事">
                            <div class="entry__nav-prev"><?php previous_post_link('%link', '← 前の記事'); ?></div>
                            <div class="entry__nav-next"><?php next_post_link('%link', '次の記事 →'); ?></div>
                        </nav>

                    </article>
            <?php endwhile;
            endif; ?>

            <div class="entry__back c-cta mt-5">
                <?php get_template_part('components/cta-gradient', null, [
                    'url' => home_url('/news/'),
                    'label' => '一覧へ戻る',
                    'variant' => 'primary',
                    'extra_class' => 'entry__back-cta'
                ]); ?>
            </div>

        </div><!-- /.container -->
    </section>

    <?php
    get_template_part('template-parts/section', 'contact-cta');
    get_template_part('includes/to-top');
    ?>
</main>