<?php
/**
 * Associate Companyページ用テンプレート本体
 * template-parts/page-associate.php
 * @package LP_WP_Theme
 * @since 1.0.0
 */
if (!defined('ABSPATH')) exit;
?>

<main class="page page--associates" role="main">
  <?php
    // サブヒーロー
    get_template_part('template-parts/associates-section', 'hero');


    // 共通CTA・ページトップ
    get_template_part('template-parts/section', 'contact-cta');
    get_template_part('includes/to-top');
  ?>
</main>