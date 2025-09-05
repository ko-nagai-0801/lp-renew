<?php

/**
 * Services Index ページ用テンプレート本体
 * template-parts/page-services-index.php
 * 
 * @package LP_WP_Theme
 * @since 1.0.0
 */
if (!defined('ABSPATH')) exit;
?>

<main class="page page--services-index" role="main">
    <?php
    // サブヒーロー
    get_template_part('template-parts/services-index-section', 'hero');

    get_template_part('template-parts/services-index-section', 'list', [
        'parent_id' => get_the_ID(),
        'sub'       => 'Services',
        'title'     => get_the_title(),
        'catch'     => has_excerpt() ? get_the_excerpt() : '',
        'title_tag' => 'h2', // 見出しタグの変更可
        'posts_per_page' => -1, // 表示件数
        'empty_text' => '公開中のサービスはありません。', // 空時メッセージ
    ]);

    // 共通CTA
    get_template_part('template-parts/section', 'contact-cta');
    ?>
</main>