<?php
/**
 * News Indexページ用テンプレート本体
 * template-parts/page-news-index.php
 * 
 * @package LP_WP_Theme
 * @since 1.0.0
 */
if (!defined('ABSPATH')) exit;
?>

<main class="page page--news-index" role="main">
  <?php
    // サブヒーロー
    get_template_part('template-parts/news-index-section', 'hero');

    // 一覧
    get_template_part('template-parts/news-index-section', 'list');

    // 共通 CTA 
    get_template_part('template-parts/section', 'contact-cta');
  ?>
</main>
