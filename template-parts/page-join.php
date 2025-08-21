<?php
/**
 * Join Usページ用テンプレート本体
 * template-parts/page-join.php
 * @package LP_WP_Theme
 * @since 1.0.0
 */
if (!defined('ABSPATH')) exit;
?>

<main class="page page--join" role="main">
  <?php
    // サブヒーロー
    get_template_part('template-parts/join-section', 'hero');

    // 募集要項（B型向け）
    get_template_part('template-parts/join-section', 'details');

    // 共通CTA・ページトップ
    get_template_part('template-parts/section', 'contact-cta');
    get_template_part('includes/to-top');
  ?>
</main>
