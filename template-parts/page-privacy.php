<?php
/**
 * Privacy Policyページ用テンプレート本体
 * template-parts/page-privacy.php
 * @package LP_WP_Theme
 * @since 1.0.0
 */
if (!defined('ABSPATH')) exit;
?>

<main class="page page--privacy" role="main">
  <?php
    // サブヒーロー
    get_template_part('template-parts/privacy-section', 'hero');

    // 本文
    get_template_part('template-parts/privacy-section', 'body');

    // 共通CTA・ページトップ
    get_template_part('template-parts/section', 'contact-cta');
    get_template_part('includes/to-top');
  ?>
</main>
